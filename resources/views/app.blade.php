<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title inertia>{{ config('app.name', 'SIMONAS') }}</title>
    <meta name="description" content="SIMONAS – Sistem Informasi Manajemen Asrama berbasis teknologi modern.">

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

    {{-- Critical inline CSS: background + splash screen (loads instantly, no CSS file needed) --}}
    <style>
        html, body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            background: linear-gradient(135deg, #f9f9f9 0%, #eef2ff 100%);
            font-family: system-ui, -apple-system, sans-serif;
        }
        #app-splash {
            position: fixed;
            inset: 0;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f9f9f9 0%, #eef2ff 100%);
            gap: 20px;
            transition: opacity 0.35s ease;
        }
        #app-splash.fade-out {
            opacity: 0;
            pointer-events: none;
        }
        .splash-logo {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .splash-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 16px rgba(37,99,235,0.35);
        }
        .splash-icon img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .splash-name {
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -0.5px;
            color: #1e3a8a;
        }
        .splash-spinner {
            width: 28px;
            height: 28px;
            border: 3px solid rgba(37,99,235,0.15);
            border-top-color: #2563eb;
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
        }
        .splash-hint {
            font-size: 13px;
            color: #64748b;
            margin-top: -8px;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>

    {{-- Fonts are self-hosted via npm (material-symbols + fontsource) --}}
    {{-- No external CDN calls needed --}}

    @viteReactRefresh
    @vite(['resources/css/app.css', 'resources/js/app.tsx'])
    @inertiaHead
</head>
<body class="font-body antialiased">

    {{-- Splash screen: pure HTML/CSS, shows instantly before JS/CSS bundle loads --}}
    <div id="app-splash" aria-hidden="true">
        <div class="splash-logo">
            <div class="splash-icon">
                <img src="/images/simonas_logo.png" alt="SIMONAS">
            </div>
            <span class="splash-name">SIMONAS</span>
        </div>
        <div class="splash-spinner"></div>
        <span class="splash-hint">Memuat halaman...</span>
    </div>

    @inertia
</body>
</html>
