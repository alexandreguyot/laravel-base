<?php

namespace App\Console\Commands;

use App\Models\NbaPlayer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SyncNbaPlayers extends Command
{
    protected $signature   = 'nba:sync-players';
    protected $description = 'Synchronise tous les joueurs NBA depuis l\'API ESPN';

    // 30 équipes NBA avec leur ID ESPN
    private array $teamIds = [
        1  => 'ATL', 2  => 'BOS', 3  => 'NOP', 4  => 'CHI',
        5  => 'CLE', 6  => 'DAL', 7  => 'DEN', 8  => 'DET',
        9  => 'GSW', 10 => 'HOU', 11 => 'IND', 12 => 'LAC',
        13 => 'LAL', 14 => 'MIA', 15 => 'MIL', 16 => 'MIN',
        17 => 'BKN', 18 => 'NYK', 19 => 'ORL', 20 => 'PHI',
        21 => 'PHX', 22 => 'POR', 23 => 'SAC', 24 => 'SAS',
        25 => 'OKC', 26 => 'UTA', 27 => 'MEM', 28 => 'WAS',
        29 => 'TOR', 30 => 'NOP',  // NOP = 3 aussi, ESPN utilise 3
        // IDs supplémentaires ESPN corrects
    ];

    // IDs ESPN officiels des 30 équipes
    private array $espnTeamIds = [
        1, 2, 3, 4, 5, 6, 7, 8, 9, 10,
        11, 12, 13, 14, 15, 16, 17, 18, 19, 20,
        21, 22, 23, 24, 25, 26, 27, 28, 29, 30,
    ];

    public function handle(): int
    {
        $this->info('Récupération des équipes NBA depuis ESPN...');

        // Étape 1 : récupérer toutes les équipes
        $teamsResponse = Http::get('https://site.api.espn.com/apis/site/v2/sports/basketball/nba/teams', [
            'limit' => 32,
        ]);

        if (!$teamsResponse->successful()) {
            $this->error('Impossible de récupérer les équipes.');
            return self::FAILURE;
        }

        $teams = $teamsResponse->json('sports.0.leagues.0.teams', []);

        if (empty($teams)) {
            $this->error('Aucune équipe trouvée.');
            return self::FAILURE;
        }

        $this->info(count($teams) . ' équipes trouvées.');
        $bar   = $this->output->createProgressBar(count($teams));
        $bar->start();

        $total = 0;

        foreach ($teams as $teamData) {
            $team   = $teamData['team'];
            $teamId = $team['id'];
            $teamAbbr = $team['abbreviation'];
            $teamLogo = $team['logos'][0]['href'] ?? null;

            // Étape 2 : récupérer le roster de chaque équipe
            $rosterResponse = Http::get(
                "https://site.api.espn.com/apis/site/v2/sports/basketball/nba/teams/{$teamId}/roster"
            );

            if (!$rosterResponse->successful()) {
                $bar->advance();
                usleep(150000);
                continue;
            }

            $athletes = $rosterResponse->json('athletes', []);

            foreach ($athletes as $athlete) {
                $espnId   = $athlete['id'];
                $headshot = $athlete['headshot']['href']
                    ?? "https://a.espncdn.com/i/headshots/nba/players/full/{$espnId}.png";

                // Nationalité
                $country = null;
                foreach ($athlete['experience'] ?? [] as $exp) {}
                $birthplace = $athlete['birthPlace'] ?? [];
                $country    = $athlete['citizenship'] ?? ($birthplace['country'] ?? null);

                NbaPlayer::updateOrCreate(
                    ['espn_id' => $espnId],
                    [
                        'api_id'            => $espnId, // On utilise espn_id comme api_id aussi
                        'full_name'         => $athlete['fullName'] ?? $athlete['displayName'],
                        'first_name'        => $athlete['firstName'] ?? null,
                        'last_name'         => $athlete['lastName'] ?? null,
                        'headshot_url'      => $headshot,
                        'team_abbreviation' => $teamAbbr,
                        'team_logo'         => $teamLogo,
                        'position'          => $athlete['position']['abbreviation'] ?? null,
                        'country'           => $country,
                    ]
                );

                $total++;
            }

            $bar->advance();
            usleep(150000); // 150ms entre chaque équipe
        }

        $bar->finish();
        $this->newLine();
        $this->info("✅ {$total} joueurs synchronisés depuis ESPN.");

        return self::SUCCESS;
    }
}
