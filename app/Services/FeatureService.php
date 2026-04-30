<?php

namespace App\Services;

use App\Models\FeatureFlag;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class FeatureService
{
    protected const CACHE_KEY = 'feature_flags';
    protected const CACHE_TTL = 300; // 5 minutes

    /**
     * Vérifie si une feature est activée.
     * Priorité : DB (cache) → config/features.php → false
     */
    public function isEnabled(string $key): bool
    {
        $flags = $this->getAllFlags();

        if (array_key_exists($key, $flags)) {
            return (bool) $flags[$key];
        }

        // Fallback sur le fichier de config
        return (bool) config("features.{$key}.enabled", false);
    }

    /**
     * Vérifie si une feature est désactivée.
     */
    public function isDisabled(string $key): bool
    {
        return ! $this->isEnabled($key);
    }

    /**
     * Active une feature (en DB + invalide le cache).
     */
    public function enable(string $key): void
    {
        $this->setEnabled($key, true);
    }

    /**
     * Désactive une feature (en DB + invalide le cache).
     */
    public function disable(string $key): void
    {
        $this->setEnabled($key, false);
    }

    /**
     * Bascule l'état d'une feature.
     */
    public function toggle(string $key): bool
    {
        $newState = ! $this->isEnabled($key);
        $this->setEnabled($key, $newState);
        return $newState;
    }

    /**
     * Synchronise tous les flags de config/features.php en base.
     * À appeler via artisan ou le seeder.
     */
    public function syncFromConfig(): void
    {
        $configFlags = config('features', []);

        foreach ($configFlags as $key => $definition) {
            FeatureFlag::updateOrCreate(
                ['key' => $key],
                [
                    'enabled'     => $definition['enabled'] ?? true,
                    'label'       => $definition['label'] ?? null,
                    'description' => $definition['description'] ?? null,
                    'group'       => $definition['group'] ?? null,
                ]
            );
        }

        $this->clearCache();
    }

    /**
     * Vide le cache des feature flags.
     */
    public function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    /**
     * Retourne tous les flags (clé => booléen) depuis le cache/DB.
     */
    protected function getAllFlags(): array
    {
        // Si la table n'existe pas encore (migrations non lancées), on retourne vide
        if (! Schema::hasTable('feature_flags')) {
            return [];
        }

        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            return FeatureFlag::all()->pluck('enabled', 'key')->toArray();
        });
    }

    /**
     * Enregistre l'état en DB et vide le cache.
     */
    protected function setEnabled(string $key, bool $enabled): void
    {
        $config = config("features.{$key}", []);

        FeatureFlag::updateOrCreate(
            ['key' => $key],
            [
                'enabled'     => $enabled,
                'label'       => $config['label'] ?? null,
                'description' => $config['description'] ?? null,
                'group'       => $config['group'] ?? null,
            ]
        );

        $this->clearCache();
    }
}
