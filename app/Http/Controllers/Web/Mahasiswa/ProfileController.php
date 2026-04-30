<?php

namespace App\Http\Controllers\Web\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Sample hafalan + activity stats — replace with real queries
        $stats = [
            'shalat_today'    => 4,
            'shalat_streak'   => 21,
            'hafalan_juz'     => 12,
            'hafalan_percent' => 24,
            'study_hours'     => 3.5,
            'points'          => 1420,
            'rank'            => 7,
            'badges'          => ['Hafidz Muda', 'Study Warrior', 'Prayer Champion'],
            'completion'      => 78,  // profile completion %
        ];

        $activities = [
            ['date' => now()->subDays(0)->format('Y-m-d'), 'type' => 'shalat',   'desc' => 'Shalat Isya berjamaah',         'points' => 10],
            ['date' => now()->subDays(0)->format('Y-m-d'), 'type' => 'hafalan',  'desc' => 'Setoran Surah Al-Mulk',          'points' => 25],
            ['date' => now()->subDays(1)->format('Y-m-d'), 'type' => 'akademik', 'desc' => 'Belajar Aljabar Linear 3 jam',   'points' => 15],
            ['date' => now()->subDays(1)->format('Y-m-d'), 'type' => 'leadership','desc' => 'Rapat OSIS — Ketua Seksi',       'points' => 20],
            ['date' => now()->subDays(2)->format('Y-m-d'), 'type' => 'shalat',   'desc' => 'Shalat Subuh berjamaah',         'points' => 10],
            ['date' => now()->subDays(3)->format('Y-m-d'), 'type' => 'kreativitas','desc' => 'Seminar Kewirausahaan',         'points' => 30],
        ];

        // Weekly radar scores
        $radar = [
            ['subject' => 'Shalat',       'value' => 88, 'fullMark' => 100],
            ['subject' => 'Akademik',     'value' => 82, 'fullMark' => 100],
            ['subject' => 'Hafalan',      'value' => 75, 'fullMark' => 100],
            ['subject' => 'Kepemimpinan', 'value' => 70, 'fullMark' => 100],
            ['subject' => 'Karakter',     'value' => 85, 'fullMark' => 100],
            ['subject' => 'Kreativitas',  'value' => 72, 'fullMark' => 100],
        ];

        return Inertia::render('Mahasiswa/Profile', [
            'user'       => $user->only('id', 'name', 'email', 'role', 'avatar', 'asrama', 'nim', 'angkatan'),
            'stats'      => $stats,
            'activities' => $activities,
            'radar'      => $radar,
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|unique:users,email,' . $request->user()->id,
            'asrama'  => 'nullable|string|max:100',
            'angkatan'=> 'nullable|string|max:10',
            'nim'     => 'nullable|string|max:30',
        ]);

        $request->user()->update($validated);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function updateAvatar(Request $request)
    {
        $request->validate(['avatar' => 'required|image|max:2048']);
        $path = $request->file('avatar')->store('avatars', 'public');
        $request->user()->update(['avatar' => '/storage/' . $path]);
        return back()->with('success', 'Foto profil diperbarui.');
    }
}
