@php
    use App\Support\LayoutHelper;
    $navLayout   = LayoutHelper::navLayout();
    $uiTheme     = LayoutHelper::uiTheme();
    $iconLibrary = LayoutHelper::iconLibrary();
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="{{ $uiTheme }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ trans('panel.site_title') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @stack('styles')
</head>

<body class="bg-base-200 text-base-content antialiased">

    @if($navLayout === 'left')
        {{-- ============================================================
             Layout SIDEBAR GAUCHE (DaisyUI Drawer)
             ============================================================ --}}
        <div class="drawer lg:drawer-open">
            <input id="admin-drawer" type="checkbox" class="drawer-toggle" />

            {{-- Contenu principal --}}
            <div class="drawer-content flex flex-col min-h-screen">
                {{-- Navbar du haut (mobile + user menu) --}}
                @include('layouts.partials.topbar')

                {{-- Contenu de la page --}}
                <main class="flex-1 p-4 md:p-6">
                    @if(session('status'))
                        <x-ui.alert variant="success" dismissible class="mb-4">
                            {{ session('status') }}
                        </x-ui.alert>
                    @endif

                    @yield('content')
                </main>

                @include('layouts.partials.footer')
            </div>

            {{-- Sidebar --}}
            <div class="drawer-side z-40">
                <label for="admin-drawer" aria-label="Fermer menu" class="drawer-overlay"></label>
                @include('layouts.partials.sidebar')
            </div>
        </div>

    @else
        {{-- ============================================================
             Layout TOP NAV
             ============================================================ --}}
        <div class="min-h-screen flex flex-col">
            @include('layouts.partials.topnav')

            <main class="flex-1 p-4 md:p-6">
                @if(session('status'))
                    <x-ui.alert variant="success" dismissible class="mb-4">
                        {{ session('status') }}
                    </x-ui.alert>
                @endif

                @yield('content')
            </main>

            @include('layouts.partials.footer')
        </div>
    @endif

    <form id="logoutform" action="{{ route('logout') }}" method="POST" class="hidden">
        @csrf
    </form>

    @livewireScripts
    @stack('scripts')
</body>
</html>
