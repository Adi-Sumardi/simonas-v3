<?php

namespace App\Http\Controllers\Web\Mentor;

use App\Http\Controllers\Controller;
use App\Models\HafalanLog;
use App\Models\Kegiatan;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MentorKalenderController extends Controller
{
    public function index(Request $request)
    {
        $mentor = $request->user();
        $menteeIds = User::where('mentor_id', $mentor->id)->pluck('id');

        // Build events from real hafalan log data (setoran submissions)
        $hafalanEvents = HafalanLog::whereIn('user_id', $menteeIds)
            ->with('user:id,name,asrama')
            ->orderByDesc('tested_at')
            ->take(30)
            ->get()
            ->map(fn($log) => [
                'id'     => 'hafalan-' . $log->id,
                'title'  => 'Setoran Hafalan — ' . ($log->user?->name ?? 'Warga'),
                'date'   => $log->tested_at?->format('Y-m-d') ?? $log->created_at->format('Y-m-d'),
                'time'   => $log->tested_at?->format('H:i') ?? '08:00',
                'type'   => 'hafalan',
                'color'  => $log->score === 'pending' ? '#f59e0b' : '#10b981',
                'warga' => $log->user?->name ?? null,
                'asrama' => $log->user?->asrama ?? null,
            ]);

        // Build events from kegiatan (global events synced by admin)
        $kegiatanEvents = Kegiatan::orderByDesc('id')
            ->take(20)
            ->get()
            ->map(function ($k) {
                $date = $k->created_at?->format('Y-m-d') ?? now()->format('Y-m-d');
                $time = '10:00';
                if (!empty($k->waktu)) {
                    try {
                        $dt = new \DateTime($k->waktu);
                        $date = $dt->format('Y-m-d');
                        $time = $dt->format('H:i');
                    } catch (\Throwable $e) {
                        // Fall back to created_at date if custom text format
                        $date = $k->created_at?->format('Y-m-d') ?? now()->format('Y-m-d');
                    }
                }
                return [
                    'id'     => 'kegiatan-' . $k->id,
                    'title'  => $k->nama_kegiatan ?? 'Kegiatan',
                    'date'   => $date,
                    'time'   => $time,
                    'type'   => 'kegiatan',
                    'color'  => '#8b5cf6',
                    'warga'  => null,
                    'asrama' => null,
                ];
            });

        $events = $hafalanEvents->merge($kegiatanEvents)->sortBy('date')->values()->toArray();

        $upcoming = collect($events)
            ->filter(fn($e) => $e['date'] >= now()->format('Y-m-d'))
            ->sortBy('date')->values()->take(5)->toArray();

        // Jadwal setoran: based on real mentees
        $jadwal_setoran = User::where('mentor_id', $mentor->id)
            ->select('id', 'name', 'asrama')
            ->get()
            ->map(fn($m) => [
                'warga'  => $m->name,
                'hari'   => 'Setiap hari',
                'waktu'  => '08:00-09:00',
                'asrama' => $m->asrama ?? '-',
            ])
            ->toArray();

        return Inertia::render('Mentor/Kalender', [
            'events'         => $events,
            'upcoming'       => $upcoming,
            'jadwal_setoran' => $jadwal_setoran,
            'today'          => now()->format('Y-m-d'),
        ]);
    }
}
