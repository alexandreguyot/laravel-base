<?php

namespace App\Console\Commands;

use App\Services\NbaApiService;
use Illuminate\Console\Command;

class DebugEspnPlayer extends Command
{
    protected $signature = 'debug:espn {id? : ESPN Player ID (default: 4432573 = Paolo Banchero)}';
    protected $description = 'Debug: fetch ESPN player details and dump the result';

    public function handle(NbaApiService $nbaApi): int
    {
        $id = $this->argument('id') ?? '4432573';
        $this->info("Fetching ESPN athlete details for ID: {$id}...");

        $result = $nbaApi->getEspnPlayerDetails($id);

        if (empty($result)) {
            $this->error('No data returned.');
        } else {
            $this->table(
                ['Key', 'Value'],
                collect($result)->map(fn($v, $k) => [$k, is_array($v) ? json_encode($v) : $v])->values()->toArray()
            );
        }

        return self::SUCCESS;
    }
}
