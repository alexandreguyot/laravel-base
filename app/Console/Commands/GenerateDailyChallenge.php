<?php

namespace App\Console\Commands;

use App\Models\DailyChallenge;
use App\Models\PlayerGameStat;
use App\Services\NbaApiService;
use Illuminate\Console\Command;

class GenerateDailyChallenge extends Command
{
    protected $signature = 'daily:generate
        {--count=5 : Number of challenges to generate}
        {--date= : Date of games to fetch stats from (YYYY-MM-DD). Defaults to yesterday.}';

    protected $description = 'Generate daily NBA player challenges from a given game date';

    public function handle(NbaApiService $nbaApi): int
    {
        $today = now()->toDateString();
        $count = (int) $this->option('count');
        $gameDate = $this->option('date') ?? \Carbon\Carbon::yesterday()->format('Y-m-d');

        DailyChallenge::where('date', $today)->delete();

        $this->info($gameDate
            ? "Fetching eligible players for {$gameDate}..."
            : 'Fetching eligible players from last night...');

        $allPlayers = array_values($nbaApi->getPlayerStatsForDate($gameDate));

        if (empty($allPlayers)) {
            $this->error('No players found for this date.');
            return self::FAILURE;
        }

        $this->info(count($allPlayers) . ' players found. Saving all stats...');

        // Save all player stats for this game date
        PlayerGameStat::where('game_date', $gameDate)->delete();

        foreach ($allPlayers as $p) {
            PlayerGameStat::create([
                'game_date' => $gameDate,
                'player_api_id' => $p['player']['id'],
                'player_name' => $p['player']['full_name'],
                'team' => $p['team']['full_name'] ?? '',
                'team_abbreviation' => $p['team']['abbreviation'] ?? '',
                'team_logo' => $p['team']['logo'] ?? '',
                'conference' => $p['team']['conference'] ?? '',
                'division' => $p['team']['division'] ?? '',
                'position' => $p['player']['position'] ?? '',
                'headshot' => $p['player']['headshot'] ?? '',
                'jersey_number' => $p['player']['jersey'] ?? null,
                'pts' => $p['pts'] ?? 0,
                'reb' => $p['reb'] ?? 0,
                'ast' => $p['ast'] ?? 0,
                'eval' => $p['eval'] ?? 0,
                'stl' => $p['stl'] ?? 0,
                'blk' => $p['blk'] ?? 0,
                'min' => $p['min'] ?? '',
                'fgm' => $p['fgm'] ?? 0,
                'fga' => $p['fga'] ?? 0,
                'fg3m' => $p['fg3m'] ?? 0,
                'fg3a' => $p['fg3a'] ?? 0,
                'ftm' => $p['ftm'] ?? 0,
                'fta' => $p['fta'] ?? 0,
                'turnover' => $p['turnover'] ?? 0,
            ]);
        }

        $this->info(count($allPlayers) . ' player stats saved for ' . $gameDate . '.');

        // Filter eligible players (10+ min) for challenges
        $players = array_values(array_filter($allPlayers, function ($p) {
            $min = (int) ($p['min'] ?? 0);
            return $min >= 10;
        }));

        if (empty($players)) {
            $this->error('No eligible players found (10+ min).');
            return self::FAILURE;
        }

        $this->info(count($players) . ' eligible players for challenges.');

        $count = min($count, count($players));
        $keys = array_rand($players, $count);
        $selected = is_array($keys) ? array_map(fn($k) => $players[$k], $keys) : [$players[$keys]];

        DailyChallenge::where('is_active', true)->update(['is_active' => false]);

        foreach ($selected as $index => $player) {
            $espnId = $player['player']['id'];
            $teamAbbr = $player['team']['abbreviation'] ?? '';

            // Fetch extra details from ESPN (nationality, height...)
            $details = $nbaApi->getEspnPlayerDetails($espnId);

            $challenge = DailyChallenge::create([
                'date' => $today,
                'game_date' => $gameDate,
                'player_api_id' => $espnId,
                'player_name' => $player['player']['full_name'],
                'team' => $player['team']['full_name'] ?? '',
                'team_abbreviation' => $teamAbbr,
                'team_logo' => $player['team']['logo'] ?? '',
                'conference' => $player['team']['conference'] ?? '',
                'division' => $player['team']['division'] ?? '',
                'position' => $details['position'] ?? $player['player']['position'] ?? '',
                'country' => $details['country'] ?? 'USA',
                'height' => $details['height'] ?? '',
                'headshot' => $details['headshot'] ?? $player['player']['headshot'] ?? '',
                'jersey_number' => $details['jersey'] ?? $player['player']['jersey'] ?? null,
                'pts' => $player['pts'] ?? 0,
                'reb' => $player['reb'] ?? 0,
                'ast' => $player['ast'] ?? 0,
                'eval' => $player['eval'] ?? 0,
                'stl' => $player['stl'] ?? 0,
                'blk' => $player['blk'] ?? 0,
                'min' => $player['min'] ?? '',
                'fgm' => $player['fgm'] ?? 0,
                'fga' => $player['fga'] ?? 0,
                'fg3m' => $player['fg3m'] ?? 0,
                'fg3a' => $player['fg3a'] ?? 0,
                'ftm' => $player['ftm'] ?? 0,
                'fta' => $player['fta'] ?? 0,
                'turnover' => $player['turnover'] ?? 0,
                'is_active' => $index === 0,
            ]);

            $num = $index + 1;
            $country = $details['country'] ?: '?';
            $this->info("#{$num}: {$challenge->player_name} ({$teamAbbr}) — {$challenge->pts} PTS, {$challenge->position}, {$country}");
        }

        $this->info("{$count} challenges created for {$today}.");

        return self::SUCCESS;
    }
}
