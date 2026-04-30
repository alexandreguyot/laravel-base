<?php

namespace App\Console\Commands;

use App\Models\FeatureFlag;
use App\Services\FeatureService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

class FeaturesSync extends Command
{
    protected $signature   = 'features:sync {--force : Écrase les valeurs DB par les valeurs config}';
    protected $description = 'Synchronise config/features.php → table feature_flags (insère les manquants)';

    public function handle(FeatureService $features): int
    {
        if (! Schema::hasTable('feature_flags')) {
            $this->error('La table feature_flags n\'existe pas. Lancez d\'abord : php artisan migrate');
            return self::FAILURE;
        }

        $configFlags = config('features', []);

        if (empty($configFlags)) {
            $this->warn('config/features.php est vide.');
            return self::FAILURE;
        }

        $created = 0;
        $skipped = 0;
        $updated = 0;

        foreach ($configFlags as $key => $def) {
            $existing = FeatureFlag::where('key', $key)->first();

            if ($existing) {
                if ($this->option('force')) {
                    $existing->update([
                        'enabled'     => $def['enabled'] ?? true,
                        'label'       => $def['label']       ?? null,
                        'description' => $def['description'] ?? null,
                        'group'       => $def['group']       ?? null,
                    ]);
                    $this->line("<fg=yellow>↻</> {$key} mis à jour (--force)");
                    $updated++;
                } else {
                    $this->line("<fg=gray>–</> {$key} déjà en DB, ignoré");
                    $skipped++;
                }
            } else {
                FeatureFlag::create([
                    'key'         => $key,
                    'enabled'     => $def['enabled'] ?? true,
                    'label'       => $def['label']       ?? null,
                    'description' => $def['description'] ?? null,
                    'group'       => $def['group']       ?? null,
                ]);
                $this->line("<fg=green>+</> {$key} créé");
                $created++;
            }
        }

        $features->clearCache();

        $this->line('');
        $this->info("Sync terminée — {$created} créés, {$updated} mis à jour, {$skipped} ignorés.");

        return self::SUCCESS;
    }
}
