<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;
use App\Models\NbaPlayer;

class NbaApiService
{
    private string $bdlBaseUrl = 'https://api.balldontlie.io/v1';
    private string $apiKey;

    private const TEAM_CONFERENCES = [
        'ATL' => 'East', 'BOS' => 'East', 'BKN' => 'East', 'CHA' => 'East',
        'CHI' => 'East', 'CLE' => 'East', 'DET' => 'East', 'IND' => 'East',
        'MIA' => 'East', 'MIL' => 'East', 'NY'  => 'East', 'NYK' => 'East',
        'ORL' => 'East', 'PHI' => 'East', 'TOR' => 'East', 'WAS' => 'East',
        'DAL' => 'West', 'DEN' => 'West', 'GS'  => 'West', 'GSW' => 'West',
        'HOU' => 'West', 'LAC' => 'West', 'LAL' => 'West', 'MEM' => 'West',
        'MIN' => 'West', 'NO'  => 'West', 'NOP' => 'West', 'OKC' => 'West',
        'PHX' => 'West', 'POR' => 'West', 'SAC' => 'West', 'SA'  => 'West',
        'SAS' => 'West', 'UTA' => 'West', 'UTAH' => 'West',
    ];

    private const TEAM_DIVISIONS = [
        'ATL' => 'Southeast', 'BOS' => 'Atlantic', 'BKN' => 'Atlantic', 'CHA' => 'Southeast',
        'CHI' => 'Central', 'CLE' => 'Central', 'DET' => 'Central', 'IND' => 'Central',
        'MIA' => 'Southeast', 'MIL' => 'Central', 'NY'  => 'Atlantic', 'NYK' => 'Atlantic',
        'ORL' => 'Southeast', 'PHI' => 'Atlantic', 'TOR' => 'Atlantic', 'WAS' => 'Southeast',
        'DAL' => 'Southwest', 'DEN' => 'Northwest', 'GS'  => 'Pacific', 'GSW' => 'Pacific',
        'HOU' => 'Southwest', 'LAC' => 'Pacific', 'LAL' => 'Pacific', 'MEM' => 'Southwest',
        'MIN' => 'Northwest', 'NO'  => 'Southwest', 'NOP' => 'Southwest', 'OKC' => 'Northwest',
        'PHX' => 'Pacific', 'POR' => 'Northwest', 'SAC' => 'Pacific', 'SA'  => 'Southwest',
        'SAS' => 'Southwest', 'UTA' => 'Northwest', 'UTAH' => 'Northwest',
    ];

    public function __construct()
    {
        $this->apiKey = config('services.balldontlie.key', '');
    }

    public function getPlayerStatsForDate(string $date): array
    {
        return Cache::remember("nba_stats_espn_{$date}", 3600, function () use ($date) {
            $formattedDate = str_replace('-', '', $date);
            $url = "https://site.api.espn.com/apis/site/v2/sports/basketball/nba/scoreboard?dates={$formattedDate}";

            $response = Http::get($url);
            if ($response->failed()) return [];

            $games = $response->json('events', []);
            $allStats = [];

            foreach ($games as $game) {
                $allStats = array_merge($allStats, $this->fetchEspnBoxScore($game['id']));
            }

            // Sort by eval descending
            usort($allStats, fn($a, $b) => ($b['eval'] ?? 0) - ($a['eval'] ?? 0));

            return $allStats;
        });
    }

    private function fetchEspnBoxScore(string $gameId): array
    {
        $url = "https://site.api.espn.com/apis/site/v2/sports/basketball/nba/summary?event={$gameId}";
        $response = Http::get($url);

        if ($response->failed()) return [];

        $boxscore = $response->json('boxscore.players', []);
        $playerStats = [];

        foreach ($boxscore as $teamData) {
            $teamName = $teamData['team']['displayName'] ?? 'Unknown';
            $teamAbbr = $teamData['team']['abbreviation'] ?? '';
            $teamLogo = $teamData['team']['logo'] ?? '';

            $labels = array_values((array)($teamData['statistics'][0]['names'] ?? []));

            $ptsIdx = array_search('PTS', $labels, true);
            $rebIdx = array_search('REB', $labels, true);
            $astIdx = array_search('AST', $labels, true);
            $minIdx = array_search('MIN', $labels, true);

            if ($ptsIdx === false || $rebIdx === false || $astIdx === false) {
                continue;
            }

            $stlIdx = array_search('STL', $labels, true);
            $blkIdx = array_search('BLK', $labels, true);
            $toIdx  = array_search('TO', $labels, true);
            $fgIdx  = array_search('FG', $labels, true);
            $fg3Idx = array_search('3PT', $labels, true);
            $ftIdx  = array_search('FT', $labels, true);

            foreach ($teamData['statistics'][0]['athletes'] as $athlete) {
                if (!isset($athlete['stats']) || empty($athlete['stats'])) continue;

                $stats = $athlete['stats'];
                $minutes = (int) ($stats[$minIdx] ?? 0);

                if ($minutes < 10) continue;

                $athleteData = $athlete['athlete'];
                $fullName = $athleteData['displayName'];

                $fgParts = explode('-', $stats[$fgIdx] ?? '0-0');
                $fgm = (int) $fgParts[0];
                $fga = (int) ($fgParts[1] ?? 0);

                $fg3Parts = explode('-', $stats[$fg3Idx] ?? '0-0');
                $fg3m = (int) $fg3Parts[0];
                $fg3a = (int) ($fg3Parts[1] ?? 0);

                $ftParts = explode('-', $stats[$ftIdx] ?? '0-0');
                $ftm = (int) $ftParts[0];
                $fta = (int) ($ftParts[1] ?? 0);

                $pts = (int) ($stats[$ptsIdx] ?? 0);
                $reb = (int) ($stats[$rebIdx] ?? 0);
                $ast = (int) ($stats[$astIdx] ?? 0);
                $stl = (int) ($stats[$stlIdx] ?? 0);
                $blk = (int) ($stats[$blkIdx] ?? 0);
                $to  = (int) ($stats[$toIdx] ?? 0);

                $eval = $pts + $reb + $ast + $stl + $blk - ($fga - $fgm) - ($fta - $ftm) - $to;

                $playerStats[] = [
                    'player' => [
                        'id' => $athleteData['id'],
                        'full_name' => $fullName,
                        'first_name' => '',
                        'last_name' => $fullName,
                        'position' => $athleteData['position']['abbreviation'] ?? '',
                        'position_name' => $athleteData['position']['displayName'] ?? '',
                        'jersey' => $athleteData['jersey'] ?? '',
                        'headshot' => $athleteData['headshot']['href'] ?? '',
                    ],
                    'team' => [
                        'full_name' => $teamName,
                        'abbreviation' => $teamAbbr,
                        'logo' => $teamLogo,
                        'conference' => self::TEAM_CONFERENCES[$teamAbbr] ?? '',
                        'division' => self::TEAM_DIVISIONS[$teamAbbr] ?? '',
                    ],
                    'pts' => $pts,
                    'reb' => $reb,
                    'ast' => $ast,
                    'stl' => $stl,
                    'blk' => $blk,
                    'turnover' => $to,
                    'min' => $minutes,
                    'fgm' => $fgm,
                    'fga' => $fga,
                    'fg3m' => $fg3m,
                    'fg3a' => $fg3a,
                    'ftm' => $ftm,
                    'fta' => $fta,
                    'eval' => $eval,
                ];
            }
        }

        return $playerStats;
    }

    /**
     * Récupère les infos détaillées d'un joueur ESPN (nationalité, taille, etc.)
     */
    public function getEspnPlayerDetails(string $espnPlayerId): array
    {
        $cacheKey = "espn_athlete_core_{$espnPlayerId}";

        return Cache::remember($cacheKey, 86400, function () use ($espnPlayerId) {
            $url = "https://sports.core.api.espn.com/v2/sports/basketball/leagues/nba/athletes/{$espnPlayerId}";
            $response = Http::get($url);

            if ($response->failed()) return [];

            $data = $response->json();

            return [
                'country' => $data['birthPlace']['country'] ?? '',
                'birth_city' => $data['birthPlace']['city'] ?? '',
                'birth_state' => $data['birthPlace']['state'] ?? '',
                'height' => $data['displayHeight'] ?? '',
                'weight' => $data['displayWeight'] ?? '',
                'age' => $data['age'] ?? '',
                'position' => $data['position']['abbreviation'] ?? '',
                'position_name' => $data['position']['displayName'] ?? '',
                'jersey' => $data['jersey'] ?? '',
                'headshot' => $data['headshot']['href'] ?? '',
                'college' => $data['college']['name'] ?? $data['college'] ?? '',
                'draft_year' => $data['draft']['year'] ?? '',
            ];
        });
    }

    public function searchPlayers(string $query): array
    {
        if (strlen($query) < 3) return [];

        return Cache::remember('nba_search_' . md5($query), 3600, function () use ($query) {
            $response = Http::withHeaders(['Authorization' => $this->apiKey])
                ->get("{$this->bdlBaseUrl}/players", [
                    'search' => $query,
                    'per_page' => 10,
                ]);

            return $response->json('data', []);
        });
    }

    public function getEligiblePlayers(?string $date = null): array
    {
        $date = $date ?? Carbon::yesterday()->format('Y-m-d');
        return $this->getPlayerStatsForDate($date);
    }

    /**
 * Sync les joueurs depuis les stats d'une nuit en base locale.
    * À appeler après getPlayerStatsForDate().
    */
    public function syncPlayersFromStats(array $stats): void
    {
        foreach ($stats as $stat) {
            $player = $stat['player'] ?? null;
            if (!$player || empty($player['id'])) continue;

            $teamAbbr = $stat['team']['abbreviation'] ?? null;
            NbaPlayer::updateOrCreate(
                ['api_id' => $player['id']],
                [
                    'espn_id'           => $player['espn_id'] ?? null,
                    'full_name'         => $player['full_name'] ?? ($player['first_name'] . ' ' . $player['last_name']),
                    'first_name'        => $player['first_name'] ?? null,
                    'last_name'         => $player['last_name'] ?? null,
                    'headshot_url'      => $player['headshot'] ?? null,
                    'team_abbreviation' => $teamAbbr,
                    'team_full_name'    => $stat['team']['full_name'] ?? null,
                    'team_logo'         => $stat['team']['logo'] ?? null,
                    'position'          => $player['position'] ?? null,
                    'country'           => $player['country'] ?? null,
                    'conference'        => self::TEAM_CONFERENCES[$teamAbbr] ?? null,
                ]
            );
        }
    }

    public function searchPlayersWithHeadshot(string $query): array
    {
        // On cherche d'abord dans la base locale ESPN
        $localResults = \App\Models\NbaPlayer::where('full_name', 'like', "%{$query}%")
            ->orWhere('last_name', 'like', "%{$query}%")
            ->orWhere('first_name', 'like', "%{$query}%")
            ->limit(8)
            ->get();

        if ($localResults->isNotEmpty()) {
            return $localResults->map(fn($p) => [
                'id'               => $p->api_id,
                'espn_id'          => $p->espn_id,
                'first_name'       => $p->first_name,
                'last_name'        => $p->last_name,
                'full_name'        => $p->full_name,
                'headshot'         => $p->headshot_url,
                'team_logo'        => $p->team_logo,
                'position'         => $p->position,
                'country'          => $p->country,
                'team' => [
                    'abbreviation' => $p->team_abbreviation,
                    'full_name'    => $p->team_full_name ?? $p->team_abbreviation,
                    'conference'   => $p->conference ?? self::TEAM_CONFERENCES[$p->team_abbreviation] ?? null,
                    'division'     => self::TEAM_DIVISIONS[$p->team_abbreviation] ?? null,
                ],
            ])->toArray();
        }

        // Fallback : appel API balldontlie si pas en base
        return $this->searchPlayers($query);
    }
}
