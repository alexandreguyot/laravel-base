<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

/**
 * Remplace `migrate:fresh` pour les environnements MySQL où
 * `dropAllTables()` échoue à cause des contraintes FK.
 *
 * Usage : php artisan migrate:safe-fresh [--seed] [--seeder=XxxSeeder]
 */
class SafeMigrateFresh extends Command
{
    protected $signature   = 'migrate:safe-fresh {--seed : Run seeders after migration} {--seeder=DatabaseSeeder : Seeder class to run}';
    protected $description = 'Drop all tables (FK-safe) then run all migrations';

    public function handle(): int
    {
        if (! $this->confirmInProduction()) {
            return self::FAILURE;
        }

        $this->dropAllTables();
        $this->runMigrations();

        if ($this->option('seed')) {
            $this->runSeeders();
        }

        return self::SUCCESS;
    }

    protected function confirmInProduction(): bool
    {
        if (app()->environment('production')) {
            return $this->confirm('<fg=red>⚠ Vous êtes en PRODUCTION. Toutes les données seront supprimées. Continuer ?</>', false);
        }
        return true;
    }

    protected function dropAllTables(): void
    {
        $this->info('Dropping all tables...');

        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        $tables = DB::select('SHOW FULL TABLES WHERE table_type = \'BASE TABLE\'');
        $prefix = DB::getTablePrefix();
        $dropped = 0;

        foreach ($tables as $row) {
            $table = array_values((array) $row)[0];
            // Strip prefix if present
            $tableName = $prefix ? preg_replace('/^' . preg_quote($prefix, '/') . '/', '', $table) : $table;
            DB::statement("DROP TABLE IF EXISTS `{$tableName}`");
            $this->line("  <fg=yellow>dropped</> {$tableName}");
            $dropped++;
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // Also wipe the migrations table tracking
        // (it was just dropped above along with other tables)

        $this->info("  → {$dropped} table(s) supprimée(s).\n");
    }

    protected function runMigrations(): void
    {
        $this->info('Running migrations...');
        Artisan::call('migrate', ['--force' => true], $this->output);
    }

    protected function runSeeders(): void
    {
        $seeder = $this->option('seeder') ?: 'DatabaseSeeder';
        $this->info("Running seeder: {$seeder}...");
        Artisan::call('db:seed', ['--class' => $seeder, '--force' => true], $this->output);
    }
}
