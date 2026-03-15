<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Metin2 Ignition') }} @hasSection('title') — @yield('title') @endif</title>
    <link rel="icon" type="image/png" sizes="32x32" href="/images/favicon.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/images/favicon.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/images/favicon.png">
    <link rel="apple-touch-icon" href="/images/favicon.png">
    <meta name="theme-color" content="#080608">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col" style="background-color: var(--color-game-bg); color: var(--color-game-text); font-family: var(--font-sans);">

    {{-- Navbar --}}
    @include('partials.nav')

    {{-- Page content --}}
    <main class="flex-1">
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('partials.footer')

</body>
</html>
