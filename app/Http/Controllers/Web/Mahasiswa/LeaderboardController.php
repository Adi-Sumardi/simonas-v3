<?php

namespace App\Http\Controllers\Web\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LeaderboardController extends Controller
{
    public function index(Request $request)
    {
        $asrama = $request->get('asrama', 'Semua');

        // TODO: load from LeaderboardSnapshot or compute view
        $top3 = [
            ['rank' => 1, 'user_id' => 1, 'name' => 'Zaid Al-Amin',   'asrama' => 'Asrama A', 'points' => 3120, 'badge' => 'MVP Asrama A'],
            ['rank' => 2, 'user_id' => 2, 'name' => 'Ahmad Farhan',   'asrama' => 'Asrama A', 'points' => 2840],
            ['rank' => 3, 'user_id' => 3, 'name' => 'Hassan Rizwan',  'asrama' => 'Asrama A', 'points' => 2615],
        ];

        $entries = [
            ['rank' => 4, 'user_id' => 4, 'name' => 'Siti Nurhaliza', 'asrama' => 'Asrama A', 'points' => 2450],
            ['rank' => 5, 'user_id' => 5, 'name' => 'Umar Khalid',    'asrama' => 'Asrama A', 'points' => 2320],
            ['rank' => 6, 'user_id' => 6, 'name' => 'Ibrahim Yusuf',  'asrama' => 'Asrama A', 'points' => 2180],
        ];

        $available_asrama = ['Asrama A', 'Asrama B', 'Asrama C', 'Asrama D'];

        $current_user_rank = [
            'rank' => 4, 'user_id' => auth()->id(),
            'name' => auth()->user()->name,
            'asrama' => 'Asrama A',
            'points' => 2450,
        ];

        return Inertia::render('Mahasiswa/Leaderboard', compact(
            'top3', 'entries', 'available_asrama', 'current_user_rank'
        ) + ['period' => 'weekly', 'asrama_filter' => $asrama]);
    }
}
