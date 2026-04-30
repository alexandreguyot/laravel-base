<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Page Builder') — {{ config('app.name') }}</title>
    @vite(['resources/css/page-builder.css', 'resources/js/page-builder.js'])
</head>
<body class="pb-root">
    @yield('content')
</body>
</html>
