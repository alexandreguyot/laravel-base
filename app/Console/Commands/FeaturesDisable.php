<?php

namespace App\Console\Commands;

use App\Services\FeatureService;
use Illuminate\Console\Command;

class FeaturesDisable extends Command
{
    protected $signature   = 'features:disable {key* : Clé(s) du feature flag} {--all : Désactiver tous les flags}';
    protected $description = 'Désactive un ou plusieurs feature flags';

    public function handle(FeatureService $features): int
    {
        if ($this->option('all')) {
            $keys = array_keys(config('features', []));
            foreach ($keys as $key) {
                $features->disable($key);
                $this->line("<fg=red>✗</> {$key} désactivé");
            }
            $this->info('Tous les feature flags ont été désactivés.');
            return self::SUCCESS;
        }

        $keys = $this->argument('key');

        foreach ($keys as $key) {
            if (! array_key_exists($key, config('features', []))) {
                $this->error("Flag inconnu : {$key}");
                continue;
            }

            $features->disable($key);
            $label = config("features.{$key}.label", $key);
            $this->line("<fg=red>✗</> {$label} (<fg=gray>{$key}</>) désactivé");
        }

        return self::SUCCESS;
    }
}
