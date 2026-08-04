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

        // Radar profil penilaian (Akademik/Leadership/Karakter/Kreativitas/Hafalan)
        $avgVal = fn ($model) => (float) ($model::where('user_id', $user->id)
            ->selectRaw("AVG(NULLIF(nilai, '')::numeric) as avg_val")
            ->value('avg_val') ?? 0);

        $radarScores = [
            ['subject' => 'Akademik',    'A' => round($avgVal(\App\Models\Akademik::class), 1),   'fullMark' => 100],
            ['subject' => 'Leadership',  'A' => round($avgVal(\App\Models\Leadership::class), 1), 'fullMark' => 100],
            ['subject' => 'Karakter',    'A' => round($avgVal(\App\Models\Karakter::class), 1),   'fullMark' => 100],
            ['subject' => 'Kreativitas', 'A' => round($avgVal(\App\Models\Kreatif::class), 1),    'fullMark' => 100],
            ['subject' => 'Hafalan',     'A' => (float) (\App\Models\HafalanLog::where('user_id', $user->id)->where('score', 'memtas')->count() * 10), 'fullMark' => 100],
        ];

        return Inertia::render('Mahasiswa/Dashboard', compact(
            'shalat', 'study_hours', 'hafalan', 'points', 'recent_activities', 'radarScores'
        ));
    }
}
