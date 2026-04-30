<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function index(): View
    {
        return view('admin.settings.index', [
            'navLayout'       => $this->getSetting('nav_layout', config('project.nav_layout', 'left')),
            'uiTheme'         => $this->getSetting('ui_theme', config('project.ui_theme', 'light')),
            'iconLibrary'     => $this->getSetting('icon_library', config('project.icon_library', 'lucide')),
            'availableThemes' => config('project.available_themes', []),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nav_layout'   => ['required', 'in:left,top'],
            'ui_theme'     => ['required', 'in:' . implode(',', array_keys(config('project.available_themes', ['light' => 'Light'])))],
            'icon_library' => ['required', 'in:lucide,fontawesome,heroicons'],
        ]);

        foreach ($validated as $key => $value) {
            $this->saveSetting($key, $value);
        }

        Cache::forget('app_settings');

        return back()->with('status', 'Paramètres sauvegardés.');
    }

    /**
     * Endpoint AJAX pour le thème — appelé depuis JS (setTheme).
     * Si l'utilisateur est connecté, sauvegarde son thème personnel.
     * Sinon, met à jour le thème global.
     */
    public function updateTheme(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'theme'  => ['required', 'string', 'max:50'],
            'scope'  => ['sometimes', 'in:user,global'],
        ]);

        $scope = $validated['scope'] ?? 'user';

        if ($scope === 'user' && auth()->check()) {
            auth()->user()->update(['ui_theme' => $validated['theme']]);
        } else {
            $this->saveSetting('ui_theme', $validated['theme']);
            Cache::forget('app_settings');
        }

        return response()->json(['ok' => true, 'theme' => $validated['theme']]);
    }

    // ----------------------------------------------------------------
    // Helpers lecture/écriture en DB (table app_settings)
    // ----------------------------------------------------------------

    protected function getSetting(string $key, mixed $default = null): mixed
    {
        $settings = Cache::remember('app_settings', 300, function () {
            if (! \Illuminate\Support\Facades\Schema::hasTable('app_settings')) {
                return collect();
            }
            return \App\Models\AppSetting::all()->pluck('value', 'key');
        });

        return $settings->get($key, $default);
    }

    protected function saveSetting(string $key, string $value): void
    {
        if (! \Illuminate\Support\Facades\Schema::hasTable('app_settings')) {
            return;
        }

        \App\Models\AppSetting::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'type' => 'string']
        );
    }
}
