<?php

namespace App\Http\Controllers\Web\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use App\Models\HafalanLog;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LeaderboardController extends Controller
{
    public function index(Request $request)
    {
        $asrama = $request->get('asrama', 'Semua');

        // Build real leaderboard from mahasiswa data
        $query = User::where('role', 'mahasiswa');

        if ($asrama !== 'Semua') {
            $query->where('asrama', $asrama);
        }

        $students = $query->get()->map(function ($u) {
            return [
                'user_id' => $u->id,
                'name'    => $u->name,
                'asrama'  => $u->asrama ?? '-',
                'avatar'  => $u->avatar,
                'points'  => $u->calculatePoints(),
            ];
        })
        ->sortByDesc('points')
        ->values()
        ->map(function ($entry, $index) {
            $entry['rank'] = $index + 1;
            return $entry;
        });

        $top3    = $students->take(3)->values()->toArray();
        $entries = $students->slice(3)->values()->toArray();

        $available_asrama = User::where('role', 'mahasiswa')
            ->whereNotNull('asrama')
            ->distinct()
            ->pluck('asrama')
            ->sort()
            ->values()
            ->toArray();

        // Current user's rank
        $current_user_rank = $students->firstWhere('user_id', auth()->id());

        return Inertia::render('Mahasiswa/Leaderboard', compact(
            'top3', 'entries', 'available_asrama', 'current_user_rank'
        ) + ['period' => 'weekly', 'asrama_filter' => $asrama]);
    }
}
