<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
        using: function () {
            $adminSubdomain = config('app.admin_subdomain');
            $baseUrl        = config('app.base_url');
            $adminDomain    = $adminSubdomain ? "{$adminSubdomain}.{$baseUrl}" : null;

            if ($adminDomain) {
                // Domaine public : laravel.test
                Route::middleware('web')
                    ->domain($baseUrl)
                    ->group(base_path('routes/web.php'));

                // Domaine admin : manager.laravel.test
                Route::middleware('web')
                    ->domain($adminDomain)
                    ->group(base_path('routes/manager.php'));
            } else {
                // Pas de sous-domaine : tout sur le même domaine
                Route::middleware('web')
                    ->group(base_path('routes/web.php'));

                Route::middleware('web')
                    ->group(base_path('routes/manager.php'));
            }

            // Routes API
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Faire confiance à NPM (reverse proxy)
        $middleware->trustProxies(at: '*');

        // Middleware web supplémentaires
        $middleware->web(append: [
            \App\Http\Middleware\AuthGates::class,
            \App\Http\Middleware\SetPreferredLocale::class,
            \App\Http\Middleware\ShareUnreadConversations::class,
        ]);

        // Middleware API supplémentaires
        $middleware->api(append: [
            \App\Http\Middleware\AuthGates::class,
        ]);

        // Alias de middleware
        $middleware->alias([
            'approved'   => \App\Http\Middleware\EnsureUserIsApproved::class,
            'feature'    => \App\Http\Middleware\CheckFeatureFlag::class,
            'two-factor' => \App\Http\Middleware\TwoFactorMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
