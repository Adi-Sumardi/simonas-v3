<?php

namespace Database\Seeders;

use App\Models\Akademik;
use App\Models\Asrama;
use App\Models\HafalanLog;
use App\Models\Ipk;
use App\Models\Komponen;
use App\Models\User;
use App\Models\UserEvent;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Seed data warga (mahasiswa) asrama yang realistis untuk kebutuhan
 * demo/testing — termasuk data pendukung (Ipk, UserEvent shalat/kegiatan,
 * HafalanLog, Akademik) supaya field turunan seperti `ipk` rata-rata dan
 * `poin_simonas` (User::calculatePoints()) tidak nol/null untuk semua warga.
 * Dipakai untuk mengisi kartu ringkasan Simonas di portal Yapinet.
 */
class WargaDemoSeeder extends Seeder
{
    private const NAMES = [
        'Fatimah Az-Zahra', 'Yusuf Maulana', 'Aisyah Ramadhani', 'Ridwan Hakim',
        'Zainab Putri', 'Umar Faruq', 'Khadijah Salsabila', 'Ali Akbar',
        'Maryam Nur Azizah', 'Bilal Ramadhan', 'Hafshah Amelia', 'Zaid Alfarisi',
        'Halimah Tuzzahra', 'Ibrahim Nugroho', 'Ruqayyah Fadhilah', 'Hamzah Pratama',
        'Sumayyah Anindita', 'Salman Al-Farisi', 'Asma Kamila', 'Thalhah Wicaksono',
        'Zulaikha Permata', 'Mush\'ab Setiawan', 'Safiyyah Rahma', 'Anas Firmansyah',
    ];

    private const ALUMNI_NAMES = [
        'Abdurrahman Hidayat', 'Sarah Kusuma', 'Faisal Ramadhan', 'Nadia Islamiyah',
        'Yasir Abdullah', 'Zahra Aulia',
    ];

    public function run(): void
    {
        $asramaNames = Asrama::pluck('nama_asrama');
        if ($asramaNames->isEmpty()) {
            $asramaNames = collect(['Al-Farabi', 'Al-Ghazali', 'Ibnu Sina', 'Al-Kindi']);
        }

        $mentorIds = User::where('role', 'mentor')->pluck('id');
        $komponenAkademik = Komponen::where('aspek', 'Akademik')->pluck('id');

        $password = Hash::make('password');

        // ── Warga (mahasiswa) aktif/nonaktif, tersebar di tiap asrama ──────
        foreach (self::NAMES as $i => $name) {
            $asrama = $asramaNames[$i % $asramaNames->count()];
            $isActive = $i % 5 !== 0; // ~80% aktif, ~20% nonaktif (memicu status "Cuti")
            $tahunMasuk = 2020 + ($i % 5);
            $tglMasuk = "{$tahunMasuk}-08-01";
            $tglKeluar = $isActive ? null : sprintf('%d-06-30', $tahunMasuk + 2);

            $user = User::updateOrCreate(
                ['email' => 'warga' . ($i + 1) . '@simonas.test'],
                [
                    'name' => $name,
                    'password' => $password,
                    'role' => 'mahasiswa',
                    'asrama' => $asrama,
                    'status_warga' => $isActive ? 'aktif' : 'nonaktif',
                    'no_induk' => 1000 + $i,
                    'tgl_masuk' => $tglMasuk,
                    'tgl_keluar' => $tglKeluar,
                    'mentor_id' => $mentorIds->isNotEmpty() ? $mentorIds->random() : null,
                ]
            );

            // 1-3 nilai IPK per semester (dipakai untuk rata-rata `ipk`).
            $semesterCount = random_int(1, 3);
            for ($s = 1; $s <= $semesterCount; $s++) {
                Ipk::updateOrCreate(
                    ['user_id' => $user->id, 'tahun' => (string) ($tahunMasuk + $s - 1), 'semester' => $s % 2 === 1 ? 'Ganjil' : 'Genap'],
                    ['ip' => number_format(random_int(275, 395) / 100, 2), 'file' => 'seed-placeholder.pdf']
                );
            }

            // Shalat berjamaah tercatat (poin shalat) — sebagian besar warga aktif.
            if ($isActive || random_int(0, 1) === 0) {
                $shalatDates = collect(range(1, random_int(5, 25)))
                    ->map(fn ($d) => now()->subDays($d)->toDateString())
                    ->values()
                    ->all();

                UserEvent::updateOrCreate(
                    ['user_id' => $user->id, 'type' => 'shalat', 'title' => 'Shalat Berjamaah'],
                    [
                        'date' => now()->toDateString(),
                        'type' => 'shalat',
                        'color' => '#22c55e',
                        'recurring' => true,
                        'is_mandatory' => true,
                        'completed_at_dates' => $shalatDates,
                    ]
                );
            }

            // Kegiatan asrama tercatat (poin kegiatan) — sekitar separuh warga.
            if (random_int(0, 1) === 0) {
                $kegiatanDates = collect(range(1, random_int(2, 8)))
                    ->map(fn ($d) => now()->subDays($d * 3)->toDateString())
                    ->values()
                    ->all();

                UserEvent::updateOrCreate(
                    ['user_id' => $user->id, 'type' => 'kegiatan', 'title' => 'Kegiatan Asrama'],
                    [
                        'date' => now()->toDateString(),
                        'type' => 'kegiatan',
                        'color' => '#2563eb',
                        'recurring' => false,
                        'is_mandatory' => false,
                        'completed_at_dates' => $kegiatanDates,
                    ]
                );
            }

            // Setoran hafalan lulus (poin hafalan) — sekitar separuh warga.
            if (random_int(0, 1) === 0) {
                foreach (range(1, random_int(1, 3)) as $h) {
                    HafalanLog::create([
                        'user_id' => $user->id,
                        'mentor_id' => $mentorIds->isNotEmpty() ? $mentorIds->random() : null,
                        'surah' => collect(['Al-Baqarah', 'Ali Imran', 'An-Nisa', 'Al-Maidah', 'Yasin'])->random(),
                        'ayat_start' => random_int(1, 50),
                        'ayat_end' => random_int(51, 100),
                        'score' => 'memtas',
                        'tested_at' => now()->subDays(random_int(1, 60)),
                    ]);
                }
            }

            // Kegiatan akademik tercatat (poin akademik) — sekitar separuh warga.
            if ($komponenAkademik->isNotEmpty() && random_int(0, 1) === 0) {
                Akademik::create([
                    'user_id' => $user->id,
                    'komponen_id' => $komponenAkademik->random(),
                    'nama_warga' => $name,
                    'asrama' => $asrama,
                    'kegiatan' => collect(['Seminar Nasional Pendidikan', 'Workshop Riset Ilmiah', 'Kuliah Umum Tamu'])->random(),
                    'waktu' => now()->subDays(random_int(10, 200))->toDateString(),
                    'tempat' => 'Aula Kampus',
                    'nama_penilai' => 'Ust. Ahmad Yani',
                    'nilai' => collect(['A', 'A-', 'B+'])->random(),
                    'tipe_kegiatan' => 'Seminar',
                ]);
            }
        }

        // ── Alumni (role terpisah dari mahasiswa/warga aktif) ──────────────
        foreach (self::ALUMNI_NAMES as $i => $name) {
            $asrama = $asramaNames[$i % $asramaNames->count()];
            $tahunMasuk = 2015 + $i;

            User::updateOrCreate(
                ['email' => 'alumnidemo' . ($i + 1) . '@simonas.test'],
                [
                    'name' => $name,
                    'password' => $password,
                    'role' => 'alumni',
                    'asrama' => $asrama,
                    'status_warga' => 'nonaktif',
                    'no_induk' => 2000 + $i,
                    'tgl_masuk' => "{$tahunMasuk}-08-01",
                    'tgl_keluar' => sprintf('%d-06-30', $tahunMasuk + 4),
                ]
            );
        }

        $this->command?->info('Selesai seed ' . count(self::NAMES) . ' warga + ' . count(self::ALUMNI_NAMES) . ' alumni demo.');
    }
}
