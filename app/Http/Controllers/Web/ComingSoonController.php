<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

/**
 * Temporary stub controller for Super / Admin / Alumni dashboards.
 * Replace with real controllers in Phase 5.
 */
class ComingSoonController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        return Inertia::render('ComingSoon', [
            'role' => $user->role ?? 'unknown',
            'user' => [
                'name'  => $user->name,
                'email' => $user->email,
            ],
        ]);
    }
}
