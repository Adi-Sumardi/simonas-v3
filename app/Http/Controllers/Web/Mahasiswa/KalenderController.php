<?php

namespace App\Http\Controllers\Web\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class KalenderController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Sample events — replace with real DB queries
        $events = [
            // Shalat
            ['id'=>1,  'title'=>'Shalat Subuh Berjamaah',       'date'=>now()->format('Y-m').'-01', 'time'=>'04:30', 'type'=>'shalat',   'color'=>'#2563eb', 'recurring'=>true],
            ['id'=>2,  'title'=>'Shalat Isya Berjamaah',        'date'=>now()->format('Y-m').'-01', 'time'=>'19:30', 'type'=>'shalat',   'color'=>'#2563eb', 'recurring'=>true],
            // Hafalan
            ['id'=>3,  'title'=>'Setoran Hafalan — Al-Mulk',    'date'=>now()->format('Y-m').'-03', 'time'=>'08:00', 'type'=>'hafalan',  'color'=>'#10b981', 'desc'=>'Mentor: Ust. Ahmad'],
            ['id'=>4,  'title'=>'Setoran Hafalan — Al-Qalam',   'date'=>now()->format('Y-m').'-10', 'time'=>'08:00', 'type'=>'hafalan',  'color'=>'#10b981', 'desc'=>'Mentor: Ust. Ahmad'],
            // Akademik
            ['id'=>5,  'title'=>'UTS Matematika',               'date'=>now()->format('Y-m').'-'.str_pad(now()->day + 3, 2, '0', STR_PAD_LEFT), 'time'=>'08:00', 'type'=>'akademik', 'color'=>'#f59e0b', 'desc'=>'Ruang B201'],
            ['id'=>6,  'title'=>'Presentasi Tugas Akhir',       'date'=>now()->format('Y-m').'-20', 'time'=>'13:00', 'type'=>'akademik', 'color'=>'#f59e0b', 'desc'=>'Aula Pesantren'],
            // Kegiatan
            ['id'=>7,  'title'=>'Rapat OSIS Bulanan',           'date'=>now()->format('Y-m').'-'.str_pad(now()->day, 2, '0', STR_PAD_LEFT),     'time'=>'15:00', 'type'=>'kegiatan', 'color'=>'#8b5cf6', 'desc'=>'Ruang Sekretariat'],
            ['id'=>8,  'title'=>'Seminar Kewirausahaan',        'date'=>now()->format('Y-m').'-15', 'time'=>'09:00', 'type'=>'kegiatan', 'color'=>'#8b5cf6', 'desc'=>'Aula Utama'],
            ['id'=>9,  'title'=>'Pramuka & Leadership Camp',    'date'=>now()->format('Y-m').'-22', 'time'=>'07:00', 'type'=>'kegiatan', 'color'=>'#8b5cf6', 'desc'=>'Lapangan Pesantren'],
            // Jadwal Harian
            ['id'=>10, 'title'=>'Belajar Mandiri',              'date'=>now()->format('Y-m').'-'.str_pad(now()->day + 1, 2, '0', STR_PAD_LEFT), 'time'=>'20:00', 'type'=>'belajar',  'color'=>'#ec4899', 'desc'=>'Perpustakaan'],
            ['id'=>11, 'title'=>'Olahraga Pagi',                'date'=>now()->format('Y-m').'-'.str_pad(now()->day + 2, 2, '0', STR_PAD_LEFT), 'time'=>'05:30', 'type'=>'olahraga', 'color'=>'#14b8a6', 'desc'=>'Lapangan'],
            ['id'=>12, 'title'=>'Mentoring Kelompok',           'date'=>now()->format('Y-m').'-'.str_pad(now()->day + 5, 2, '0', STR_PAD_LEFT), 'time'=>'16:00', 'type'=>'hafalan',  'color'=>'#10b981', 'desc'=>'Ruang Mentor 3'],
        ];

        // Upcoming events (next 7 days)
        $upcoming = collect($events)
            ->filter(fn($e) => $e['date'] >= now()->format('Y-m-d'))
            ->sortBy('date')
            ->values()
            ->take(5);

        return Inertia::render('Mahasiswa/Kalender', [
            'events'   => $events,
            'upcoming' => $upcoming,
            'today'    => now()->format('Y-m-d'),
        ]);
    }
}
