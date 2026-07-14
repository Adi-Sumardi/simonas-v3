<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Guards the /integrations/yapinet/* routes with a static bearer token,
 * shared out-of-band with the external Yapinet portal (see config/services.php).
 * This is intentionally separate from Sanctum session/token auth used by the
 * mobile app and the web dashboard.
 */
class EnsureYapinetApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();
        $expected = config('services.yapinet.api_key');

        if (!$token || !$expected || !hash_equals((string) $expected, (string) $token)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}
