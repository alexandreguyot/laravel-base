<?php

namespace App\Observers;

use App\Models\FeatureFlag;
use App\Models\FeatureFlagAudit;
use Illuminate\Support\Facades\Cache;

class FeatureFlagObserver
{
    /**
     * Invalide le cache et enregistre l'audit à chaque modification.
     */
    public function updated(FeatureFlag $flag): void
    {
        Cache::forget('feature_flags');

        if ($flag->wasChanged('enabled')) {
            FeatureFlagAudit::create([
                'feature_key' => $flag->key,
                'old_value'   => $flag->getOriginal('enabled'),
                'new_value'   => $flag->enabled,
                'source'      => app()->runningInConsole() ? 'artisan' : 'ui',
                'user_id'     => auth()->id(),
                'user_name'   => auth()->user()?->name,
            ]);

            $flag->updateQuietly([
                'last_changed_by' => auth()->id(),
                'last_changed_at' => now(),
            ]);
        }
    }

    public function created(FeatureFlag $flag): void
    {
        Cache::forget('feature_flags');
    }

    public function deleted(FeatureFlag $flag): void
    {
        Cache::forget('feature_flags');
    }
}
