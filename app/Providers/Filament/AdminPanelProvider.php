<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->brandName(config('app.name'))
            // Couleurs alignées sur le thème DaisyUI "light"
            ->colors([
                'primary'   => Color::Blue,
                'gray'      => Color::Slate,
                'danger'    => Color::Red,
                'info'      => Color::Sky,
                'success'   => Color::Emerald,
                'warning'   => Color::Amber,
            ])
            ->darkMode(false)
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                \App\Filament\Widgets\StatsOverview::class,
                \App\Filament\Widgets\UsersChart::class,
                \App\Filament\Widgets\TasksByStatusChart::class,
                \App\Filament\Widgets\CrmChart::class,
            ])
            ->navigationGroups([
                NavigationGroup::make('Utilisateurs')->icon('heroicon-o-users'),
                NavigationGroup::make('Contenu')->icon('heroicon-o-document-text'),
                NavigationGroup::make('E-commerce')->icon('heroicon-o-shopping-bag'),
                NavigationGroup::make('CRM')->icon('heroicon-o-briefcase'),
                NavigationGroup::make('Formation')->icon('heroicon-o-academic-cap'),
                NavigationGroup::make('Tâches')->icon('heroicon-o-check-circle'),
                NavigationGroup::make('FAQ')->icon('heroicon-o-question-mark-circle'),
                NavigationGroup::make('Contacts')->icon('heroicon-o-address-book'),
                NavigationGroup::make('Paramètres')->icon('heroicon-o-cog'),
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
