<?php

namespace App\Support;

use App\Models\AppSetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class LayoutHelper
{
    /**
     * Thème DaisyUI actif.
     * Priorité : thème personnel de l'utilisateur → thème global → config → 'light'
     */
    public static function uiTheme(): string
    {
        // Thème personnel (colonne ui_theme sur users)
        if (auth()->check() && auth()->user()->ui_theme) {
            return auth()->user()->ui_theme;
        }

        // Thème global (app_settings)
        return static::getSetting('ui_theme', config('project.ui_theme', 'light'));
    }

    /**
     * Layout de navigation actif : 'left' | 'top'
     */
    public static function navLayout(): string
    {
        return static::getSetting('nav_layout', config('project.nav_layout', 'left'));
    }

    /**
     * Bibliothèque d'icônes active.
     */
    public static function iconLibrary(): string
    {
        return static::getSetting('icon_library', config('project.icon_library', 'lucide'));
    }

    protected static function getSetting(string $key, mixed $default = null): mixed
    {
        if (! Schema::hasTable('app_settings')) {
            return $default;
        }

        $settings = Cache::remember('app_settings', 300, function () {
            return AppSetting::all()->pluck('value', 'key');
        });

        return $settings->get($key, $default);
    }
}
