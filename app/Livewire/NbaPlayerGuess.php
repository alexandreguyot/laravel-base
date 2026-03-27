<?php

namespace App\Livewire;

use App\Models\DailyChallenge;
use App\Services\NbaApiService;
use Livewire\Component;

class NbaPlayerGuess extends Component
{
    public string $search = '';
    public array $suggestions = [];
    public array $guesses = [];
    public array $playerStatsForDate = [];
    public int $attempt = 0;
    public int $maxAttempts = 7;
    public bool $gameWon = false;
    public bool $gameLost = false;
    public ?array $challenge = null;
    public bool $showRules = false;
    public string $error = '';

    public array $revealedHints = [];
    public array $newlyRevealedHints = [];

    public array $allChallenges = [];
    public int $currentChallengeIndex = 0;
    public int $totalChallenges = 0;

    private array $hintOrder = [
        1 => 'pts',
        2 => 'reb',
        3 => 'ast',
        4 => 'conference',
        5 => 'nationality',
        6 => 'team',
        7 => 'position',
    ];

    private function progressKey(): string
    {
        // Hash basé sur les IDs des challenges du jour
        // Si les challenges changent, la clé change → reset automatique
        $ids = implode('-', array_column($this->allChallenges, 'id'));
        return 'nba_guess_progress_' . md5($ids);
    }


    public function mount(): void
    {
        $dailyChallenges = DailyChallenge::forToday()->orderBy('id')->get();


        if ($dailyChallenges->isEmpty()) {
            $this->error = 'Pas de défi disponible aujourd\'hui. Revenez demain !';
            return;
        }

        $this->allChallenges = $dailyChallenges->map(function ($c) {
            $arr = $c->toArray();
            $arr['conference'] = $this->mapConference($arr['conference'] ?? '');
            $arr['nationality'] = $arr['country'] ?? 'USA';
            return $arr;
        })->toArray();

        $this->totalChallenges = count($this->allChallenges);

        // Charger les stats de la nuit (utiliser game_date si dispo, sinon date)
        $firstChallenge = $dailyChallenges->first();
        $statsDate = $firstChallenge->game_date
            ? $firstChallenge->game_date->format('Y-m-d')
            : $firstChallenge->date->format('Y-m-d');
        $this->playerStatsForDate = app(NbaApiService::class)->getPlayerStatsForDate($statsDate);
        app(NbaApiService::class)->syncPlayersFromStats($this->playerStatsForDate);

        // maxAttempts = 1 + nombre d'indices
        $this->maxAttempts = 1 + count($this->hintOrder);

        // Restore progress from session (which challenge index we're on)
        $sessionKey = 'nba_guess_progress_' . now()->toDateString();
        $this->currentChallengeIndex = session($this->progressKey(), 0);

        // Clamp index
        if ($this->currentChallengeIndex >= $this->totalChallenges) {
            $this->currentChallengeIndex = $this->totalChallenges - 1;
        }

        $this->loadChallenge($this->currentChallengeIndex);
    }

    private function loadChallenge(int $index): void
    {
        $this->currentChallengeIndex = $index;
        $this->challenge = $this->allChallenges[$index];

        // Restore state for this specific challenge from session
        $sessionKey = 'nba_guess_' . $this->challenge['id'];
        $savedState = session($sessionKey);

        if ($savedState) {
            $this->guesses = $savedState['guesses'] ?? [];
            $this->attempt = $savedState['attempt'] ?? 0;
            $this->gameWon = $savedState['game_won'] ?? false;
            $this->gameLost = $savedState['game_lost'] ?? false;
            $this->revealedHints = $savedState['revealed_hints'] ?? [];
        } else {
            $this->guesses = [];
            $this->attempt = 0;
            $this->gameWon = false;
            $this->gameLost = false;
            $this->revealedHints = [];
        }

        $this->newlyRevealedHints = [];
        $this->search = '';
        $this->suggestions = [];
    }

    public function focusSearch(): void
    {
        if (!empty($this->search) || !empty($this->suggestions)) {
            return;
        }

        // Afficher les joueurs de la nuit comme suggestions par défaut
        $alreadyGuessed = array_map(fn($g) => strtolower($g['name']), $this->guesses);

        $mapped = [];
        foreach ($this->playerStatsForDate as $stats) {
            $name = $stats['player']['full_name'] ?? '';
            if (in_array(strtolower($name), $alreadyGuessed)) continue;

            $teamAbbr = $stats['team']['abbreviation'] ?? '';
            $mapped[] = [
                'id'               => $stats['player']['id'] ?? 0,
                'name'             => $name,
                'team'             => $stats['team']['full_name'] ?? $teamAbbr,
                'team_abbreviation'=> $teamAbbr,
                'position'         => $stats['player']['position'] ?? '',
                'nationality'      => $stats['player']['country'] ?? 'USA',
                'country'          => $stats['player']['country'] ?? 'USA',
                'conference'       => $this->mapConference($stats['team']['conference'] ?? ''),
                'division'         => $stats['team']['division'] ?? '',
                'height'           => '',
                'jersey_number'    => $stats['player']['jersey'] ?? null,
                'headshot'         => $stats['player']['headshot'] ?? null,
                'espn_id'          => $stats['player']['id'] ?? null,
                'team_logo'        => $stats['team']['logo'] ?? null,
            ];
        }

        $this->suggestions = array_slice($this->applyConfirmedFilters($mapped), 0, 8);
    }

    public function updatedSearch(): void
    {
        if (strlen($this->search) < 3) {
            $this->suggestions = [];
            return;
        }

        $players = app(NbaApiService::class)->searchPlayersWithHeadshot($this->search);

        $mapped = array_map(function ($player) {
            $country    = $player['country'] ?? 'USA';
            $conference = $this->mapConference($player['team']['conference'] ?? '');

            return [
                'id'               => $player['id'],
                'name'             => $player['first_name'] . ' ' . $player['last_name'],
                'team'             => $player['team']['full_name'] ?? 'N/A',
                'team_abbreviation'=> $player['team']['abbreviation'] ?? '',
                'position'         => $player['position'] ?? '',
                'nationality'      => $country,
                'country'          => $country,
                'conference'       => $conference,
                'division'         => $player['team']['division'] ?? '',
                'height'           => $player['height'] ?? '',
                'jersey_number'    => $player['jersey_number'] ?? null,
                'headshot'         => $player['headshot'] ?? null,
                'espn_id'          => $player['espn_id'] ?? null,
                'team_logo'        => $player['team_logo'] ?? null,
            ];
        }, $players);

        $this->suggestions = array_slice($this->applyConfirmedFilters($mapped), 0, 8);
    }

    private function applyConfirmedFilters(array $players): array
    {
        $confirmedConference = null;
        $confirmedNationality = null;
        $confirmedTeam = null;

        foreach ($this->guesses as $guess) {
            if (($guess['feedback']['conference'] ?? '') === 'correct') {
                $confirmedConference = $guess['conference'];
            }
            if (($guess['feedback']['nationality'] ?? '') === 'correct') {
                $confirmedNationality = $guess['nationality'] ?? $guess['country'] ?? null;
            }
            if (($guess['feedback']['team'] ?? '') === 'correct') {
                $confirmedTeam = $guess['team_abbreviation'];
            }
        }

        if ($confirmedTeam) {
            $players = array_values(array_filter($players, fn($p) =>
                strtolower($p['team_abbreviation']) === strtolower($confirmedTeam)
            ));
        } elseif ($confirmedConference) {
            $players = array_values(array_filter($players, fn($p) =>
                strtolower($p['conference']) === strtolower($confirmedConference)
            ));
        }

        if ($confirmedNationality) {
            $players = array_values(array_filter($players, fn($p) =>
                strtolower($p['nationality']) === strtolower($confirmedNationality)
            ));
        }

        return $players;
    }

    public function selectPlayer(int $index): void
    {
        if ($this->gameWon || $this->gameLost || !$this->challenge) {
            return;
        }

        if (!isset($this->suggestions[$index])) {
            return;
        }

        $selected = $this->suggestions[$index];
        $nightStats = $this->findNightStatsForPlayer($selected['name']);

        $selected['pts'] = $nightStats['pts'] ?? 0;
        $selected['reb'] = $nightStats['reb'] ?? 0;
        $selected['ast'] = $nightStats['ast'] ?? 0;
        $selected['min'] = $nightStats['min'] ?? 0;
        $selected['fgm'] = $nightStats['fgm'] ?? 0;
        $selected['fga'] = $nightStats['fga'] ?? 0;
        $selected['fg3m'] = $nightStats['fg3m'] ?? 0;
        $selected['fg3a'] = $nightStats['fg3a'] ?? 0;
        $selected['ftm'] = $nightStats['ftm'] ?? 0;
        $selected['fta'] = $nightStats['fta'] ?? 0;
        $selected['stl'] = $nightStats['stl'] ?? 0;
        $selected['blk'] = $nightStats['blk'] ?? 0;
        $selected['turnover'] = $nightStats['turnover'] ?? 0;
        $selected['eval'] = $nightStats['eval'] ?? 0;
        $selected['espn_id'] = $nightStats['espn_id'] ?? null;
        $selected['headshot'] = $nightStats['headshot'] ?? null;
        $selected['team_logo'] = $nightStats['team_logo'] ?? null;

        // Check if already guessed (by name)
        foreach ($this->guesses as $guess) {
            if (strtolower($guess['name']) === strtolower($selected['name'])) {
                $this->search = '';
                $this->suggestions = [];
                return;
            }
        }

        $this->attempt++;

        // Check win (compare by name, not ID)
        $isCorrect = strtolower(trim($selected['name'])) === strtolower(trim($this->challenge['player_name']));

        // Compare with mystery player
        if ($isCorrect) {
            // Force all feedback to correct
            $feedback = [
                'conference' => 'correct',
                'team' => 'correct',
                'position' => 'correct',
                'nationality' => 'correct',
                'country' => 'correct',
                'height' => 'correct',
            ];
        } else {
            $feedback = $this->comparePlayers($selected);
        }
        $selected['feedback'] = $feedback;
        $this->guesses[] = $selected;

        if ($isCorrect) {
            $this->gameWon = true;
            // Reveal all remaining hints on win
            $this->newlyRevealedHints = [];
            foreach ($this->hintOrder as $hintKey) {
                if (!in_array($hintKey, $this->revealedHints)) {
                    $this->newlyRevealedHints[] = $hintKey;
                    $this->revealedHints[] = $hintKey;
                }
            }
        } elseif ($this->attempt >= $this->maxAttempts) {
            $this->gameLost = true;
            // Reveal all remaining hints on loss
            $this->newlyRevealedHints = [];
            foreach ($this->hintOrder as $hintKey) {
                if (!in_array($hintKey, $this->revealedHints)) {
                    $this->newlyRevealedHints[] = $hintKey;
                    $this->revealedHints[] = $hintKey;
                }
            }
        } else {
            // Reveal next hint
            $this->revealNextHint();
        }

        $this->search = '';
        $this->suggestions = [];
        $this->saveState();
    }

    private function comparePlayers(array $guessed): array
    {
        $challenge = $this->challenge;

        return [
            'conference' => $this->compareValue($guessed['conference'], $challenge['conference']),
            'team' => $this->compareValue($guessed['team_abbreviation'], $challenge['team_abbreviation']),
            'position' => $this->comparePosition($guessed['position'], $challenge['position']),
            'nationality' => $this->compareValue($guessed['nationality'] ?? $guessed['country'], $challenge['nationality'] ?? $challenge['country']),
            'country' => $this->compareValue($guessed['country'], $challenge['country'] ?? ''),
            'height' => $this->compareHeight($guessed['height'], $challenge['height']),
        ];
    }

    private function compareValue(?string $guessed, ?string $target): string
    {
        if (!$guessed || !$target) {
            return 'wrong';
        }

        return strtolower($guessed) === strtolower($target) ? 'correct' : 'wrong';
    }

    private function comparePosition(?string $guessed, ?string $target): string
    {
        if (!$guessed || !$target) {
            return 'wrong';
        }

        if (strtolower($guessed) === strtolower($target)) {
            return 'correct';
        }

        $guessedParts = explode('-', strtoupper($guessed));
        $targetParts = explode('-', strtoupper($target));

        if (count(array_intersect($guessedParts, $targetParts)) > 0) {
            return 'close';
        }

        return 'wrong';
    }

    private function mapConference(string $conference): string
    {
        $normalized = strtolower(trim($conference));

        if ($normalized === 'west') {
            return 'Ouest';
        }

        if ($normalized === 'east') {
            return 'Est';
        }

        if ($normalized === 'ouest' || $normalized === 'est') {
            return ucfirst($normalized);
        }

        return $conference;
    }

    private function compareHeight(?string $guessed, ?string $target): string
    {
        if (!$guessed || !$target) {
            return 'wrong';
        }

        if ($guessed === $target) {
            return 'correct';
        }

        $guessedInches = $this->heightToInches($guessed);
        $targetInches = $this->heightToInches($target);

        if ($guessedInches === $targetInches) {
            return 'correct';
        }

        $diff = abs($guessedInches - $targetInches);
        if ($diff <= 2) {
            return 'close';
        }

        return $guessedInches > $targetInches ? 'lower' : 'higher';
    }

    private function heightToInches(string $height): int
    {
        if (str_contains($height, '-')) {
            $parts = explode('-', $height);
            return ((int) $parts[0] * 12) + (int) ($parts[1] ?? 0);
        }

        return (int) $height;
    }

    private function findNightStatsForPlayer(string $playerName): array
    {
        $playerNameLower = strtolower(trim($playerName));

        foreach ($this->playerStatsForDate as $stats) {
            $fullName = strtolower(trim($stats['player']['full_name'] ?? ''));
            if ($fullName === $playerNameLower || str_contains($fullName, $playerNameLower) || str_contains($playerNameLower, $fullName)) {
                return [
                    'pts' => $stats['pts'] ?? 0,
                    'reb' => $stats['reb'] ?? 0,
                    'ast' => $stats['ast'] ?? 0,
                    'min' => $stats['min'] ?? 0,
                    'fgm' => $stats['fgm'] ?? 0,
                    'fga' => $stats['fga'] ?? 0,
                    'fg3m' => $stats['fg3m'] ?? 0,
                    'fg3a' => $stats['fg3a'] ?? 0,
                    'ftm' => $stats['ftm'] ?? 0,
                    'fta' => $stats['fta'] ?? 0,
                    'stl' => $stats['stl'] ?? 0,
                    'blk' => $stats['blk'] ?? 0,
                    'turnover' => $stats['turnover'] ?? 0,
                    'eval' => $stats['eval'] ?? 0,
                    'espn_id' => $stats['player']['id'] ?? null,
                    'headshot' => $stats['player']['headshot'] ?? null,
                    'team_logo' => $stats['team']['logo'] ?? null,
                    'team' => $stats['team']['full_name'] ?? null,
                    'team_abbreviation' => $stats['team']['abbreviation'] ?? null,
                    'conference' => $stats['team']['conference'] ?? null,
                    'division' => $stats['team']['division'] ?? null,
                    'position' => $stats['player']['position'] ?? null,
                ];
            }
        }

        return [];
    }

    private function revealNextHint(): void
    {
        $nextHint = $this->attempt;

        if (isset($this->hintOrder[$nextHint])) {
            $hintKey = $this->hintOrder[$nextHint];
            $this->revealedHints[] = $hintKey;
            $this->newlyRevealedHints = [$hintKey];
        } else {
            $this->newlyRevealedHints = [];
        }
    }

    private function saveState(): void
    {
        if (!$this->challenge) {
            return;
        }

        // Save state per challenge ID
        $sessionKey = 'nba_guess_' . $this->challenge['id'];
        session([$sessionKey => [
            'guesses' => $this->guesses,
            'attempt' => $this->attempt,
            'game_won' => $this->gameWon,
            'game_lost' => $this->gameLost,
            'revealed_hints' => $this->revealedHints,
        ]]);

        // Save current challenge index
        session([$this->progressKey() => $this->currentChallengeIndex]);
    }

    public function nextChallenge(): void
    {
        if ($this->currentChallengeIndex + 1 >= $this->totalChallenges) {
            return;
        }

        $nextIndex = $this->currentChallengeIndex + 1;
        session([$this->progressKey() => $nextIndex]);
        $this->loadChallenge($nextIndex);
    }

    public function toggleRules(): void
    {
        $this->showRules = !$this->showRules;
    }

    public function getShareText(): string
    {
        if (!$this->challenge) {
            return '';
        }

        $emoji = $this->gameWon ? '🏀' : '❌';
        $score = $this->gameWon ? "{$this->attempt}/{$this->maxAttempts}" : "X/{$this->maxAttempts}";

        $lines = ["Daily Dunk {$emoji} {$score}"];

        foreach ($this->guesses as $guess) {
            $line = '';
            foreach (['nationality', 'position', 'conference', 'team'] as $key) {
                $status = $guess['feedback'][$key] ?? 'wrong';
                $line .= match ($status) {
                    'correct' => '🟩',
                    'close' => '🟧',
                    default => '🟥',
                };
            }
            $lines[] = $line;
        }

        return implode("\n", $lines);
    }

    public function render()
    {
        return view('livewire.nba-player-guess');
    }
}
