<?php

namespace App\Providers;

use App\Models\FeatureFlag;
use App\Observers\FeatureFlagObserver;
use App\Services\FeatureService;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(FeatureService::class);
    }

    public function boot(): void
    {
        // ----------------------------------------------------------------
        // Pagination — vue DaisyUI par défaut
        // ----------------------------------------------------------------
        Paginator::defaultView('components.ui.pagination');
        Paginator::defaultSimpleView('components.ui.pagination');

        // ----------------------------------------------------------------
        // Observers
        // ----------------------------------------------------------------
        FeatureFlag::observe(FeatureFlagObserver::class);

        // ----------------------------------------------------------------
        // Directive @feature('key') ... @endfeature
        // Usage : @feature('crm') ... @elsefeature ... @endfeature
        // ----------------------------------------------------------------
        Blade::if('feature', function (string $key): bool {
            return app(FeatureService::class)->isEnabled($key);
        });

        // ----------------------------------------------------------------
        // Directive @disabled('key') — inverse
        // ----------------------------------------------------------------
        Blade::if('disabled', function (string $key): bool {
            return app(FeatureService::class)->isDisabled($key);
        });
    }
}
