<?php

namespace App\Http\Controllers\Web\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HafalanController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // TODO: load from Hafalan model
        $hafalan = [
            'target_juz'       => 30,
            'current_juz'      => 13,
            'current_ayah'     => 0,
            'total_ayah'       => 2415,
            'streak_days'      => 14,
            'last_tasmi_at'    => null,
            'progress_percent' => 42,
            'current_surah'    => 'Ar-Ra\'d',
        ];

        $weekly = [
            'completed_pages' => 7,
            'target_pages'    => 10,
            'percent'         => 70,
        ];

        // TODO: load from HafalanLog model
        $logs = [];

        $quality = [
            'mutqin_percent'   => 85,
            'murajaah_percent' => 15,
        ];

        $murojaah_plan = [
            'surah'  => 'Ibrahim',
            'advice' => 'Focus on Surah Ibrahim today to maintain strength.',
        ];

        return Inertia::render('Mahasiswa/Hafalan', compact(
            'hafalan', 'weekly', 'logs', 'quality', 'murojaah_plan'
        ));
    }

    public function createLog()
    {
        return Inertia::render('Mahasiswa/HafalanLogForm');
    }

    public function storeLog(Request $request)
    {
        $request->validate([
            'surah'      => 'required|string|max:100',
            'ayat_start' => 'required|integer|min:1',
            'ayat_end'   => 'required|integer|gte:ayat_start',
        ]);

        // TODO: create HafalanLog record

        return redirect()->route('mahasiswa.hafalan.index')
            ->with('success', 'Log setoran berhasil dikirim, tunggu penilaian mentor.');
    }
}
