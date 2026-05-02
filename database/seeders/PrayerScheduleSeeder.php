<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserEvent;
use Illuminate\Database\Seeder;

class PrayerScheduleSeeder extends Seeder
{
    public function run()
    {
        // 1. Clean up ALL existing shalat events first to avoid duplicates
        UserEvent::where('type', 'shalat')->delete();

        $students = User::where('role', 'mahasiswa')->get();

        // 2. Define clean prayer schedule
        $prayers = [
            ['title' => 'Subuh',    'time' => '04:45', 'color' => '#2563eb'],
            ['title' => 'Dzuhur',   'time' => '12:10', 'color' => '#2563eb'],
            ['title' => 'Ashar',    'time' => '15:30', 'color' => '#2563eb'],
            ['title' => 'Maghrib',  'time' => '18:15', 'color' => '#2563eb'],
            ['title' => 'Isya',     'time' => '19:30', 'color' => '#2563eb'],
        ];

        foreach ($students as $student) {
            foreach ($prayers as $prayer) {
                // Use first day of current month as starting point
                $startDate = now()->startOfMonth()->format('Y-m-d');

                UserEvent::create([
                    'user_id'   => $student->id,
                    'title'     => $prayer['title'],
                    'date'      => $startDate,
                    'time'      => $prayer['time'],
                    'type'      => 'shalat',
                    'color'     => $prayer['color'],
                    'desc'      => 'Jadwal sholat wajib harian (Otomatis)',
                    'recurring' => true,
                ]);
            }
        }
    }
}
