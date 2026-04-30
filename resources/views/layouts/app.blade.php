@php use App\Support\LayoutHelper; @endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      data-theme="{{ LayoutHelper::uiTheme() }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ trans('panel.site_title') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-base-200 flex items-center justify-center p-4 antialiased">

    <div class="w-full max-w-sm">
        <div class="text-center mb-8">
            <a href="/" class="text-3xl font-bold text-primary inline-flex items-center gap-2">
                <x-icon name="layout-dashboard" class="w-8 h-8" />
                {{ trans('panel.site_title') }}
            </a>
        </div>

        <div class="card bg-base-100 shadow-xl">
            <div class="card-body">
                @yield('content')
            </div>
        </div>
    </div>

    @livewireScripts
</body>
</html>
