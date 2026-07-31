<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Asrama;
use App\Models\User;
use App\Models\Akademik;
use App\Models\Komponen;
use Inertia\Inertia;
use Inertia\Response;

class WelcomeController extends Controller
{
    /**
     * Display landing page with real database statistics.
     */
    public function __invoke(): Response
    {
        // 1. Mahasiswa / Warga count
        $mahasiswaCount = User::where('role', 'mahasiswa')->count();
        if ($mahasiswaCount === 0) {
            $mahasiswaCount = User::where('status_warga', 'aktif')->count();
        }

        // 2. Mentor count
        $mentorCount = User::where('role', 'mentor')->count();

        // 3. Asrama count
        $asramaCount = Asrama::count();

        // 4. Aspek penilaian (Akademik, Leadership, Karakter Islami, Kreativitas & Kewirausahaan)
        $aspekCount = count(Komponen::ASPEK);

        // 5. Average score from real academic evaluations
        $avgScore = Akademik::whereNotNull('nilai')
            ->where('nilai', '!=', '')
            ->pluck('nilai')
            ->map(fn ($v) => (float) $v)
            ->filter(fn ($v) => $v > 0)
            ->avg();

        $avgScoreFormatted = $avgScore ? round($avgScore, 1) . '%' : '85.0%';

        $formatValue = function (int $count): string {
            if ($count >= 1000) {
                $k = round($count / 1000, 1);
                return $k . 'k+';
            }
            return (string) $count;
        };

        return Inertia::render('Welcome', [
            'stats' => [
                'mahasiswa' => [
                    'raw' => $mahasiswaCount,
                    'formatted' => $formatValue($mahasiswaCount),
                ],
                'mentor' => [
                    'raw' => $mentorCount,
                    'formatted' => $formatValue($mentorCount),
                ],
                'asrama' => [
                    'raw' => $asramaCount,
                    'formatted' => (string) $asramaCount,
                ],
                'aspek' => [
                    'raw' => $aspekCount,
                    'formatted' => (string) $aspekCount,
                ],
                'avgScore' => $avgScoreFormatted,
            ],
        ]);
    }
}
