<?php

namespace App\Http\Controllers\Web\Mentor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PenilaianController extends Controller
{
    public function index(Request $request)
    {
        $mentor = $request->user();

        // Sample mentees with their hafalan logs awaiting scoring
        $submissions = collect([
            ['id'=>1,'santri_id'=>1,'santri_name'=>'Ahmad Fauzi',   'asrama'=>'Al-Farabi',  'surah'=>'Al-Mulk',    'ayat_dari'=>1, 'ayat_sampai'=>30,'juz'=>29,'tanggal'=>now()->subDays(0)->format('Y-m-d'),'status'=>'pending',  'catatan'=>''],
            ['id'=>2,'santri_id'=>2,'santri_name'=>'Budi Santoso',  'asrama'=>'Al-Farabi',  'surah'=>'Al-Qalam',   'ayat_dari'=>1, 'ayat_sampai'=>52,'juz'=>29,'tanggal'=>now()->subDays(1)->format('Y-m-d'),'status'=>'pending',  'catatan'=>''],
            ['id'=>3,'santri_id'=>3,'santri_name'=>'Cahya Ramadhan','asrama'=>'Al-Ghazali', 'surah'=>'Al-Haqqah',  'ayat_dari'=>1, 'ayat_sampai'=>52,'juz'=>29,'tanggal'=>now()->subDays(1)->format('Y-m-d'),'status'=>'pending',  'catatan'=>''],
            ['id'=>4,'santri_id'=>4,'santri_name'=>'Dani Pratama',  'asrama'=>'Al-Ghazali', 'surah'=>'Al-Baqarah', 'ayat_dari'=>1, 'ayat_sampai'=>20,'juz'=>1, 'tanggal'=>now()->subDays(2)->format('Y-m-d'),'status'=>'graded',   'nilai'=>85,'grade'=>'B','catatan'=>'Tajwid perlu diperbaiki'],
            ['id'=>5,'santri_id'=>5,'santri_name'=>'Eko Wahyudi',   'asrama'=>'Ibnu Sina',  'surah'=>'Al-Fatiha',  'ayat_dari'=>1, 'ayat_sampai'=>7, 'juz'=>1, 'tanggal'=>now()->subDays(2)->format('Y-m-d'),'status'=>'graded',   'nilai'=>95,'grade'=>'A','catatan'=>'Sangat baik'],
            ['id'=>6,'santri_id'=>6,'santri_name'=>'Fahri Maulana', 'asrama'=>'Ibnu Sina',  'surah'=>'Yasin',      'ayat_dari'=>1, 'ayat_sampai'=>83,'juz'=>22,'tanggal'=>now()->subDays(3)->format('Y-m-d'),'status'=>'graded',   'nilai'=>78,'grade'=>'B','catatan'=>'Perlu latihan makhorijul huruf'],
        ]);

        $stats = [
            'pending' => $submissions->where('status', 'pending')->count(),
            'graded'  => $submissions->where('status', 'graded')->count(),
            'total'   => $submissions->count(),
            'avg_nilai'=> round($submissions->where('status','graded')->avg('nilai') ?? 0),
        ];

        return Inertia::render('Mentor/Penilaian', [
            'submissions' => $submissions->values(),
            'stats'       => $stats,
        ]);
    }

    public function store(Request $request, int $id)
    {
        $validated = $request->validate([
            'nilai'   => 'required|integer|min:0|max:100',
            'grade'   => 'required|in:A,B,C,D,E',
            'catatan' => 'nullable|string|max:500',
        ]);

        // TODO: update hafalan log in DB
        return back()->with('success', 'Penilaian berhasil disimpan.');
    }
}
