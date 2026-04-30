<?php

namespace App\Console\Commands;

use App\Services\FeatureService;
use Illuminate\Console\Command;

class FeaturesEnable extends Command
{
    protected $signature   = 'features:enable {key* : Clé(s) du feature flag} {--all : Activer tous les flags}';
    protected $description = 'Active un ou plusieurs feature flags';

    public function handle(FeatureService $features): int
    {
        if ($this->option('all')) {
            $keys = array_keys(config('features', []));
            if (empty($keys)) {
                $this->warn('Aucun flag trouvé dans config/features.php');
                return self::FAILURE;
            }

            foreach ($keys as $key) {
                $features->enable($key);
                $this->line("<fg=green>✓</> {$key} activé");
            }

            $this->info('Tous les feature flags ont été activés.');
            return self::SUCCESS;
        }

        $keys = $this->argument('key');

        foreach ($keys as $key) {
            if (! array_key_exists($key, config('features', []))) {
                $this->error("Flag inconnu : {$key}");
                continue;
            }

            $features->enable($key);
            $label = config("features.{$key}.label", $key);
            $this->line("<fg=green>✓</> {$label} (<fg=gray>{$key}</>) activé");
        }

        return self::SUCCESS;
    }
}
