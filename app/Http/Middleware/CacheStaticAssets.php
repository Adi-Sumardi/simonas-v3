<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CacheStaticAssets
{
    /**
     * Add long-lived cache headers to Vite-built assets.
     * These assets have content hashes in their filenames, so caching forever is safe.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $path = $request->path();

        // Vite build assets — hashed filename, safe to cache 1 year
        if (str_starts_with($path, 'build/assets/')) {
            $response->headers->set('Cache-Control', 'public, max-age=31536000, immutable');
            $response->headers->set('Vary', 'Accept-Encoding');
            return $response;
        }

        // PWA icons — cache 30 days
        if (str_starts_with($path, 'icons/') || $path === 'favicon.ico') {
            $response->headers->set('Cache-Control', 'public, max-age=2592000');
            return $response;
        }

        return $response;
    }
}
