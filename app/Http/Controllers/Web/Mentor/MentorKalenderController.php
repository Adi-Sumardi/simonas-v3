<?php

namespace App\Http\Controllers\Web\Mentor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MentorKalenderController extends Controller
{
    public function index(Request $request)
    {
        $mentor = $request->user();

        $events = [
            ['id'=>1, 'title'=>'Setoran Hafalan — Ahmad Fauzi',    'date'=>now()->format('Y-m').'-'.str_pad(now()->day,2,'0',STR_PAD_LEFT), 'time'=>'08:00','type'=>'hafalan',  'color'=>'#10b981','santri'=>'Ahmad Fauzi',  'asrama'=>'Al-Farabi'],
            ['id'=>2, 'title'=>'Setoran Hafalan — Budi Santoso',   'date'=>now()->format('Y-m').'-'.str_pad(now()->day,2,'0',STR_PAD_LEFT), 'time'=>'09:00','type'=>'hafalan',  'color'=>'#10b981','santri'=>'Budi Santoso', 'asrama'=>'Al-Farabi'],
            ['id'=>3, 'title'=>'Mentoring Kelompok Al-Farabi',     'date'=>now()->format('Y-m').'-'.str_pad(now()->day+1,2,'0',STR_PAD_LEFT),'time'=>'16:00','type'=>'mentoring','color'=>'#2563eb','santri'=>null,           'asrama'=>'Al-Farabi'],
            ['id'=>4, 'title'=>'Setoran Hafalan — Cahya Ramadhan', 'date'=>now()->format('Y-m').'-'.str_pad(now()->day+2,2,'0',STR_PAD_LEFT),'time'=>'08:00','type'=>'hafalan',  'color'=>'#10b981','santri'=>'Cahya Ramadhan','asrama'=>'Al-Ghazali'],
            ['id'=>5, 'title'=>'Evaluasi Bulanan Santri',          'date'=>now()->format('Y-m').'-20','time'=>'10:00','type'=>'evaluasi',  'color'=>'#f59e0b','santri'=>null,'asrama'=>null],
            ['id'=>6, 'title'=>'Rapat Mentor Bulanan',             'date'=>now()->format('Y-m').'-25','time'=>'14:00','type'=>'rapat',    'color'=>'#8b5cf6','santri'=>null,'asrama'=>null],
            ['id'=>7, 'title'=>'Tasmi Hafalan — Eko Wahyudi',      'date'=>now()->format('Y-m').'-'.str_pad(now()->day+5,2,'0',STR_PAD_LEFT),'time'=>'07:30','type'=>'hafalan','color'=>'#10b981','santri'=>'Eko Wahyudi','asrama'=>'Ibnu Sina'],
        ];

        $upcoming = collect($events)
            ->filter(fn($e) => $e['date'] >= now()->format('Y-m-d'))
            ->sortBy('date')->values()->take(5);

        // Jadwal setoran reguler per santri
        $jadwal_setoran = [
            ['santri'=>'Ahmad Fauzi',   'hari'=>'Senin & Kamis', 'waktu'=>'08:00-09:00', 'asrama'=>'Al-Farabi'],
            ['santri'=>'Budi Santoso',  'hari'=>'Selasa & Jumat','waktu'=>'08:00-09:00', 'asrama'=>'Al-Farabi'],
            ['santri'=>'Cahya Ramadhan','hari'=>'Rabu & Sabtu',  'waktu'=>'07:30-08:30', 'asrama'=>'Al-Ghazali'],
            ['santri'=>'Dani Pratama',  'hari'=>'Senin & Rabu',  'waktu'=>'09:00-10:00', 'asrama'=>'Al-Ghazali'],
            ['santri'=>'Eko Wahyudi',   'hari'=>'Selasa & Kamis','waktu'=>'07:30-08:30', 'asrama'=>'Ibnu Sina'],
        ];

        return Inertia::render('Mentor/Kalender', [
            'events'         => $events,
            'upcoming'       => $upcoming,
            'jadwal_setoran' => $jadwal_setoran,
            'today'          => now()->format('Y-m-d'),
        ]);
    }
}
