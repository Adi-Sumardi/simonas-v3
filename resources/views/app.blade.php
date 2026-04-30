<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title inertia>{{ config('app.name', 'SIMONAS') }}</title>
    <meta name="description" content="SIMONAS – Sistem Informasi Manajemen Pondok Pesantren berbasis teknologi modern.">

    {{-- PWA Meta Tags --}}
    <meta name="theme-color" content="#2563eb">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="SIMONAS">
    <meta name="msapplication-TileColor" content="#2563eb">
    <meta name="msapplication-tap-highlight" content="no">

    {{-- PWA Icons --}}
    <link rel="icon" type="image/png" sizes="192x192" href="/icons/icon-192x192.png">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="apple-touch-icon" href="/icons/icon-192x192.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/icons/icon-152x152.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/icons/icon-144x144.png">
    <link rel="apple-touch-icon" sizes="128x128" href="/icons/icon-128x128.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/icons/icon-72x72.png">

    {{-- Splash screens for iOS --}}
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

    {{-- Fonts are self-hosted via npm (material-symbols + fontsource) --}}
    {{-- No external CDN calls needed --}}

    @viteReactRefresh
    @vite(['resources/css/app.css', 'resources/js/app.tsx'])
    @inertiaHead
</head>
<body class="font-body antialiased">
    @inertia
</body>
</html>
