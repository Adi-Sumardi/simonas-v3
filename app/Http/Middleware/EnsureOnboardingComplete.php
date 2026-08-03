<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureOnboardingComplete
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (
            $user
            && $user->onboarding_completed_at === null
            && !$request->routeIs('onboarding.*')
            && !$request->routeIs('logout')
        ) {
            return redirect()->route('onboarding.show');
        }

        return $next($request);
    }
}
