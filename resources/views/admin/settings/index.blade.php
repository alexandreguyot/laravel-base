@extends('layouts.admin')
@section('content')

<div class="p-4 max-w-2xl space-y-6">

    <div>
        <h1 class="text-2xl font-bold">Paramètres de l'interface</h1>
        <p class="text-base-content/60 text-sm mt-1">Configuration globale de l'apparence de l'application.</p>
    </div>

    @if(session('status'))
        <div class="alert alert-success shadow">
            <x-icon name="circle-check" class="w-5 h-5" />
            <span>{{ session('status') }}</span>
        </div>
    @endif

    <form action="{{ route('settings.update') }}" method="POST" class="space-y-6">
        @csrf

        {{-- Navigation --}}
        <div class="card bg-base-100 shadow-sm">
            <div class="card-body">
                <h2 class="card-title text-base">
                    <x-icon name="layout-dashboard" class="w-4 h-4 text-primary" />
                    Navigation
                </h2>
                <p class="text-sm text-base-content/60">Choisissez la position du menu de navigation.</p>

                <div class="flex gap-4 mt-3">
                    <label class="cursor-pointer">
                        <input type="radio" name="nav_layout" value="left"
                            class="radio radio-primary radio-sm"
                            {{ $navLayout === 'left' ? 'checked' : '' }}>
                        <span class="ml-2 text-sm font-medium">Sidebar gauche</span>
                        <p class="text-xs text-base-content/50 ml-6">Menu vertical fixe à gauche</p>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="nav_layout" value="top"
                            class="radio radio-primary radio-sm"
                            {{ $navLayout === 'top' ? 'checked' : '' }}>
                        <span class="ml-2 text-sm font-medium">Barre du haut</span>
                        <p class="text-xs text-base-content/50 ml-6">Menu horizontal en haut de page</p>
                    </label>
                </div>
            </div>
        </div>

        {{-- Thème --}}
        <div class="card bg-base-100 shadow-sm">
            <div class="card-body">
                <h2 class="card-title text-base">
                    <x-icon name="palette" class="w-4 h-4 text-primary" />
                    Thème DaisyUI
                </h2>
                <p class="text-sm text-base-content/60">Thème par défaut de l'application.</p>

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mt-3">
                    @foreach($availableThemes as $value => $label)
                        <label class="cursor-pointer">
                            <input type="radio" name="ui_theme" value="{{ $value }}"
                                class="radio radio-primary radio-sm sr-only"
                                {{ $uiTheme === $value ? 'checked' : '' }}
                                onchange="window.setTheme('{{ $value }}')">
                            <div data-theme="{{ $value }}"
                                class="border-2 rounded-box p-3 text-center text-sm font-medium transition-all
                                    {{ $uiTheme === $value ? 'border-primary' : 'border-base-300 hover:border-primary/50' }}">
                                <div class="flex gap-1 justify-center mb-2">
                                    <span class="w-4 h-4 rounded-full bg-primary inline-block"></span>
                                    <span class="w-4 h-4 rounded-full bg-secondary inline-block"></span>
                                    <span class="w-4 h-4 rounded-full bg-accent inline-block"></span>
                                </div>
                                {{ $label }}
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Icônes --}}
        <div class="card bg-base-100 shadow-sm">
            <div class="card-body">
                <h2 class="card-title text-base">
                    <x-icon name="star" class="w-4 h-4 text-primary" />
                    Bibliothèque d'icônes
                </h2>
                <p class="text-sm text-base-content/60">Jeu d'icônes utilisé dans l'interface.</p>

                <select name="icon_library" class="select select-bordered w-full max-w-xs mt-3">
                    <option value="lucide"      {{ $iconLibrary === 'lucide'      ? 'selected' : '' }}>Lucide (recommandé)</option>
                    <option value="fontawesome" {{ $iconLibrary === 'fontawesome' ? 'selected' : '' }}>Font Awesome 5/6</option>
                    <option value="heroicons"   {{ $iconLibrary === 'heroicons'   ? 'selected' : '' }}>Heroicons</option>
                </select>
                <p class="text-xs text-base-content/40 mt-1">
                    Nécessite que la librairie choisie soit incluse dans le layout.
                </p>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="btn btn-primary gap-2">
                <x-icon name="device-floppy" class="w-4 h-4" />
                Enregistrer les paramètres
            </button>
        </div>
    </form>
</div>

@endsection
