@extends('layouts.page-builder')

@section('title', 'Builder — ' . $page->title)

@section('content')
<div id="pb-app"
     data-save-url="{{ route('page-builder.save', $page) }}"
     data-preview-url="{{ route('page-builder.preview', $page) }}"
     data-media-url="{{ route('page-builder.media', $page) }}"
     data-back-url="{{ route('content-pages.edit', $page) }}"
     data-public-url="{{ $page->slug ? route('page.show', $page->slug) : '' }}"
     data-css-url="{{ Vite::asset('resources/css/app.css') }}"
     data-initial-content="{{ json_encode($page->builder_content) }}"
>
    {{-- Top bar --}}
    <header id="pb-topbar">
        <div class="pb-topbar-left">
            <a href="{{ route('content-pages.edit', $page) }}" class="pb-btn pb-btn-ghost" title="Retour">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"/><path d="M12 5l-7 7 7 7"/></svg>
            </a>
            <span class="pb-page-title">{{ $page->title }}</span>
        </div>

        <div class="pb-topbar-center">
            {{-- Device buttons --}}
            <div id="pb-devices" class="pb-device-group">
                <button class="pb-btn pb-btn-device active" data-device="desktop" title="Desktop">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="3" rx="2"/><path d="M8 21h8"/><path d="M12 17v4"/></svg>
                </button>
                <button class="pb-btn pb-btn-device" data-device="tablet" title="Tablette">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="16" height="20" x="4" y="2" rx="2"/><line x1="12" x2="12.01" y1="18" y2="18"/></svg>
                </button>
                <button class="pb-btn pb-btn-device" data-device="mobile" title="Mobile">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="14" height="20" x="5" y="2" rx="2"/><line x1="12" x2="12.01" y1="18" y2="18"/></svg>
                </button>
            </div>

            {{-- History --}}
            <button id="pb-undo" class="pb-btn pb-btn-ghost" title="Annuler (Ctrl+Z)">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 7v6h6"/><path d="M21 17a9 9 0 0 0-9-9 9 9 0 0 0-6 2.3L3 13"/></svg>
            </button>
            <button id="pb-redo" class="pb-btn pb-btn-ghost" title="Rétablir (Ctrl+Y)">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 7v6h-6"/><path d="M3 17a9 9 0 0 1 9-9 9 9 0 0 1 6 2.3L21 13"/></svg>
            </button>
        </div>

        <div class="pb-topbar-right">
            @if($page->slug)
            <a href="{{ route('page.show', $page->slug) }}" target="_blank" class="pb-btn pb-btn-ghost" title="Voir la page publique">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" x2="21" y1="14" y2="3"/></svg>
                Voir
            </a>
            @endif
            <button id="pb-save" class="pb-btn pb-btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                Sauvegarder
            </button>
            <span id="pb-save-status" class="pb-save-status"></span>
        </div>
    </header>

    {{-- Main area --}}
    <div id="pb-workspace">
        {{-- Left panel: blocks + layers tabs --}}
        <aside id="pb-panel-left">
            <div class="pb-panel-tabs">
                <button class="pb-tab active" data-tab="blocks">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                    Blocs
                </button>
                <button class="pb-tab" data-tab="layers">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/></svg>
                    Calques
                </button>
            </div>
            <div id="pb-blocks" class="pb-panel-content active"></div>
            <div id="pb-layers" class="pb-panel-content"></div>
        </aside>

        {{-- Canvas --}}
        <main id="pb-canvas-wrap">
            <div id="pb-canvas"></div>
        </main>

        {{-- Right panel: style manager + traits --}}
        <aside id="pb-panel-right">
            <div class="pb-panel-tabs">
                <button class="pb-tab active" data-tab="styles">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14"/><path d="M4.93 4.93a10 10 0 0 0 0 14.14"/></svg>
                    Styles
                </button>
                <button class="pb-tab" data-tab="traits">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><line x1="16" x2="8" y1="13" y2="13"/><line x1="16" x2="8" y1="17" y2="17"/><line x1="10" x2="8" y1="9" y2="9"/></svg>
                    Attributs
                </button>
            </div>
            <div id="pb-styles" class="pb-panel-content active"></div>
            <div id="pb-traits" class="pb-panel-content"></div>
            <div id="pb-selectors" class="pb-selectors-wrap"></div>
        </aside>
    </div>

    {{-- Toast notification --}}
    <div id="pb-toast" class="pb-toast hidden"></div>
</div>

@php
    $pbConfig = json_encode([
        'saveUrl'        => route('page-builder.save', $page),
        'previewUrl'     => route('page-builder.preview', $page),
        'mediaUrl'       => route('page-builder.media', $page),
        'cssUrl'         => rescue(fn() => Vite::asset('resources/css/app.css'), asset('css/app.css')),
        'csrfToken'      => csrf_token(),
        'initialContent' => $page->builder_content,
    ], JSON_HEX_TAG | JSON_HEX_AMP);
@endphp
<script>
    // Configuration passed to page-builder.js via DOM
    window.PB_CONFIG = {!! $pbConfig !!};
</script>
@endsection
