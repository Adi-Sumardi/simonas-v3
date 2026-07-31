import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';
import { VitePWA } from 'vite-plugin-pwa';
import path from 'path';

export default defineConfig({
    build: {
        // Keep font files as separate assets (not inlined) so SW can cache them
        assetsInlineLimit: 0,
        // No manualChunks — Vite default handles React + dependents correctly.
        // Manual splitting caused "useState/forwardRef undefined" race conditions.
    },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.tsx'],
            refresh: true,
        }),
        react(),
        VitePWA({
            registerType: 'autoUpdate',
            injectRegister: 'auto',
            devOptions: { enabled: true },
            includeAssets: ['favicon.ico', 'icons/*.png'],
            manifest: {
                name: 'SIMONAS – Digital Asrama YAPI',
                short_name: 'SIMONAS',
                description: 'Sistem Informasi Manajemen Asrama',
                theme_color: '#2563eb',
                background_color: '#f0f4ff',
                display: 'standalone',
                orientation: 'portrait-primary',
                scope: '/',
                start_url: '/dashboard',
                icons: [
                    { src: '/icons/icon-72x72.png',   sizes: '72x72',   type: 'image/png' },
                    { src: '/icons/icon-96x96.png',   sizes: '96x96',   type: 'image/png' },
                    { src: '/icons/icon-128x128.png', sizes: '128x128', type: 'image/png' },
                    { src: '/icons/icon-144x144.png', sizes: '144x144', type: 'image/png' },
                    { src: '/icons/icon-152x152.png', sizes: '152x152', type: 'image/png' },
                    { src: '/icons/icon-192x192.png', sizes: '192x192', type: 'image/png', purpose: 'any maskable' },
                    { src: '/icons/icon-384x384.png', sizes: '384x384', type: 'image/png' },
                    { src: '/icons/icon-512x512.png', sizes: '512x512', type: 'image/png', purpose: 'any maskable' },
                ],
            },
            workbox: {
                // Precache JS/CSS/HTML only — large fonts handled via runtime cache below
                globPatterns: ['**/*.{js,css,html,ico,png,svg}'],
                // Allow up to 5 MB per file (Material Symbols is ~4 MB)
                maximumFileSizeToCacheInBytes: 5 * 1024 * 1024,
                // Network-first for Inertia pages (always fresh)
                runtimeCaching: [
                    {
                        urlPattern: ({ url }) =>
                            url.pathname.startsWith('/dashboard') ||
                            url.pathname.startsWith('/mahasiswa') ||
                            url.pathname.startsWith('/mentor') ||
                            url.pathname.startsWith('/super'),
                        handler: 'NetworkFirst',
                        options: {
                            cacheName: 'inertia-pages',
                            expiration: { maxAgeSeconds: 60 * 60 }, // 1 hour
                        },
                    },
                    {
                        // Cache ALL font files (woff2/woff/ttf) — CacheFirst, 1 year
                        urlPattern: /\.(woff2?|ttf|eot)(\?.*)?$/i,
                        handler: 'CacheFirst',
                        options: {
                            cacheName: 'fonts-cache',
                            expiration: {
                                maxAgeSeconds: 60 * 60 * 24 * 365, // 1 year
                                maxEntries: 30,
                            },
                            cacheableResponse: { statuses: [0, 200] },
                        },
                    },
                    {
                        urlPattern: /\/icons\/.*/,
                        handler: 'CacheFirst',
                        options: {
                            cacheName: 'icons-cache',
                            expiration: { maxAgeSeconds: 60 * 60 * 24 * 30 }, // 30 days
                        },
                    },
                ],
            },
        }),
    ],
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources/js'),
        },
    },
});
