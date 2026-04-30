<?php

namespace App\Http\Controllers\Web\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Shalat stat (placeholder — sesuaikan dengan model real Anda)
        $shalat = [
            'completed' => 4,
            'total'     => 5,
            'next_prayer' => 'Isha (19:15)',
        ];

        // Study hours (dari model AktivitasBelajar atau sejenisnya)
        $study_hours = [
            'today'  => 3.5,
            'target' => 5,
        ];

        // Hafalan progress
        $hafalan = [
            'progress_percent' => 75,
            'current_surah'    => 'Al-Kahf',
            'current_juz'      => 15,
            'current_page'     => 302,
        ];

        // Leaderboard points
        $points = [
            'total'   => 1240,
            'rank'    => 4,
            'to_next' => 160,
        ];

        // Recent activities (ambil dari aktivitas model atau log model)
        $recent_activities = [];

        return Inertia::render('Mahasiswa/Dashboard', compact(
            'shalat', 'study_hours', 'hafalan', 'points', 'recent_activities'
        ));
    }
}
