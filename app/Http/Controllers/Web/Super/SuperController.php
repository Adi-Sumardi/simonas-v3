<?php

namespace App\Http\Controllers\Web\Super;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SuperController extends Controller
{
    // ── Warga ─────────────────────────────────────────────────
    public function warga()
    {
        $warga = collect(range(1, 24))->map(fn($i) => [
            'id'      => $i,
            'nim'     => '2024' . str_pad($i, 4, '0', STR_PAD_LEFT),
            'name'    => ['Ahmad Fauzi','Budi Santoso','Cahya Ramadhan','Dani Pratama','Eko Wahyudi',
                          'Fahri Maulana','Galih Setiawan','Hendra Gunawan','Irfan Hakim','Joko Widodo',
                          'Kemal Aditya','Lukman Hakim','Miftah Rizky','Naufal Hasan','Omar Syarif',
                          'Pandu Wijaya','Qodir Rahman','Rizal Pratama','Syamsul Bahri','Taufik Hidayat',
                          'Umar Faruq','Vino Ramadhan','Wahyu Saputra','Zaid Alfarisi'][($i-1)],
            'asrama'  => ['Al-Farabi','Al-Ghazali','Ibnu Sina','Al-Kindi'][($i-1) % 4],
            'angkatan'=> 2022 + (($i - 1) % 3),
            'status'  => $i % 7 === 0 ? 'nonaktif' : 'aktif',
            'hafalan_juz' => rand(5, 25),
            'skor'    => rand(65, 98),
            'mentor'  => ['Ust. Ahmad Yani', 'Ust. Basyir Rohim', 'Ust. Chandra'][($i-1) % 3],
        ]);

        return Inertia::render('Super/Warga', [
            'warga'   => $warga->values(),
            'asramas' => ['Al-Farabi','Al-Ghazali','Ibnu Sina','Al-Kindi'],
            'stats'   => [
                'total'   => $warga->count(),
                'aktif'   => $warga->where('status','aktif')->count(),
                'nonaktif'=> $warga->where('status','nonaktif')->count(),
                'avg_skor'=> round($warga->avg('skor')),
            ],
        ]);
    }

    // ── Alumni ────────────────────────────────────────────────
    public function alumni()
    {
        $alumni = collect([
            ['id'=>1,'name'=>'Abdullah Karim','angkatan'=>2018,'asrama'=>'Al-Farabi',  'karir'=>'Software Engineer @ Gojek',         'kota'=>'Jakarta',   'avatar'=>null,'hafalan_juz'=>28,'linkedin'=>'#'],
            ['id'=>2,'name'=>'Bagas Nugroho', 'angkatan'=>2018,'asrama'=>'Al-Ghazali', 'karir'=>'Product Manager @ Tokopedia',       'kota'=>'Jakarta',   'avatar'=>null,'hafalan_juz'=>20,'linkedin'=>'#'],
            ['id'=>3,'name'=>'Chairul Umam',  'angkatan'=>2019,'asrama'=>'Ibnu Sina',  'karir'=>'Data Scientist @ Bukalapak',        'kota'=>'Bandung',   'avatar'=>null,'hafalan_juz'=>15,'linkedin'=>'#'],
            ['id'=>4,'name'=>'Dimas Pratama', 'angkatan'=>2019,'asrama'=>'Al-Kindi',   'karir'=>'Full Stack Developer @ Traveloka',  'kota'=>'Jakarta',   'avatar'=>null,'hafalan_juz'=>22,'linkedin'=>'#'],
            ['id'=>5,'name'=>'Eka Wijayanto', 'angkatan'=>2020,'asrama'=>'Al-Farabi',  'karir'=>'UI/UX Designer @ Grab',            'kota'=>'Surabaya',  'avatar'=>null,'hafalan_juz'=>18,'linkedin'=>'#'],
            ['id'=>6,'name'=>'Faris Abdillah','angkatan'=>2020,'asrama'=>'Al-Ghazali', 'karir'=>'Android Developer @ OVO',          'kota'=>'Yogyakarta','avatar'=>null,'hafalan_juz'=>25,'linkedin'=>'#'],
            ['id'=>7,'name'=>'Ghani Mufid',   'angkatan'=>2021,'asrama'=>'Ibnu Sina',  'karir'=>'Backend Engineer @ Shopee',         'kota'=>'Tangerang', 'avatar'=>null,'hafalan_juz'=>12,'linkedin'=>'#'],
            ['id'=>8,'name'=>'Hilman Fathoni', 'angkatan'=>2021,'asrama'=>'Al-Kindi',  'karir'=>'DevOps Engineer @ Dana',            'kota'=>'Malang',    'avatar'=>null,'hafalan_juz'=>30,'linkedin'=>'#'],
        ]);

        return Inertia::render('Super/Alumni', [
            'alumni'      => $alumni->values(),
            'asrama_list' => $alumni->pluck('asrama')->unique()->sort()->values(),
            'stats'       => [
                'total'         => $alumni->count(),
                'hafidz'        => $alumni->where('hafalan_juz', 30)->count(),
                'angkatan_list' => $alumni->pluck('angkatan')->unique()->sort()->values(),
            ],
        ]);
    }

    // ── Kegiatan ──────────────────────────────────────────────
    public function kegiatan()
    {
        $kegiatan = collect([
            ['id'=>1,'judul'=>'Ujian Tengah Semester','tanggal'=>now()->format('Y-m').'-15','waktu'=>'08:00','tempat'=>'Aula Utama','tipe'=>'akademik','status'=>'upcoming','peserta'=>120],
            ['id'=>2,'judul'=>'Seminar Kewirausahaan Islam','tanggal'=>now()->format('Y-m').'-18','waktu'=>'09:00','tempat'=>'Ruang Serbaguna','tipe'=>'kegiatan','status'=>'upcoming','peserta'=>80],
            ['id'=>3,'judul'=>'Tasmi\' Hafalan Bulanan','tanggal'=>now()->format('Y-m').'-20','waktu'=>'07:00','tempat'=>'Masjid Al-Hikmah','tipe'=>'hafalan','status'=>'upcoming','peserta'=>45],
            ['id'=>4,'judul'=>'Pekan Olahraga Santri','tanggal'=>now()->format('Y-m').'-22','waktu'=>'07:30','tempat'=>'Lapangan Utama','tipe'=>'olahraga','status'=>'upcoming','peserta'=>120],
            ['id'=>5,'judul'=>'Wisuda Angkatan 2021','tanggal'=>now()->subDays(10)->format('Y-m-d'),'waktu'=>'10:00','tempat'=>'Gedung Aula','tipe'=>'akademik','status'=>'selesai','peserta'=>35],
            ['id'=>6,'judul'=>'Lomba Kaligrafi','tanggal'=>now()->subDays(5)->format('Y-m-d'),'waktu'=>'08:00','tempat'=>'Aula Seni','tipe'=>'kegiatan','status'=>'selesai','peserta'=>60],
        ]);

        return Inertia::render('Super/Kegiatan', [
            'kegiatan' => $kegiatan->values(),
            'stats'    => [
                'upcoming' => $kegiatan->where('status','upcoming')->count(),
                'selesai'  => $kegiatan->where('status','selesai')->count(),
                'total'    => $kegiatan->count(),
            ],
        ]);
    }

    // ── Hafalan ───────────────────────────────────────────────
    public function hafalan()
    {
        $data = collect(range(1, 12))->map(fn($i) => [
            'id'        => $i,
            'name'      => ['Ahmad Fauzi','Budi Santoso','Cahya Ramadhan','Dani Pratama','Eko Wahyudi',
                            'Fahri Maulana','Galih Setiawan','Hendra Gunawan','Irfan Hakim','Joko Widodo',
                            'Kemal Aditya','Lukman Hakim'][($i-1)],
            'asrama'    => ['Al-Farabi','Al-Ghazali','Ibnu Sina','Al-Kindi'][($i-1) % 4],
            'juz'       => [12,8,20,6,15,18,10,25,7,14,22,28][($i-1)],
            'target_juz'=> 30,
            'last_setoran'=> now()->subDays(rand(0,10))->format('Y-m-d'),
            'mentor'    => ['Ust. Ahmad Yani','Ust. Basyir','Ust. Chandra'][($i-1) % 3],
            'status'    => $i % 5 === 0 ? 'perlu_perhatian' : ($i % 3 === 0 ? 'on_track' : 'baik'),
        ]);

        $juzDist = collect(range(1, 30))->map(fn($j) => [
            'juz'   => "Juz $j",
            'count' => $data->filter(fn($d) => (int)$d['juz'] === $j)->count(),
        ])->filter(fn($d) => $d['count'] > 0)->values();

        return Inertia::render('Super/Hafalan', [
            'hafalan'   => $data->values(),
            'juzDist'   => $juzDist,
            'stats' => [
                'avg_juz'        => round($data->avg('juz'), 1),
                'hafidz'         => $data->where('juz', 30)->count(),
                'perlu_perhatian'=> $data->where('status','perlu_perhatian')->count(),
                'total'          => $data->count(),
            ],
        ]);
    }

    // ── Leaderboard ───────────────────────────────────────────
    public function leaderboard()
    {
        $entries = collect(range(1, 20))->map(fn($i) => [
            'rank'   => $i,
            'id'     => $i,
            'name'   => ['Cahya Ramadhan','Hendra Gunawan','Lukman Hakim','Ahmad Fauzi','Eko Wahyudi',
                         'Galih Setiawan','Joko Widodo','Kemal Aditya','Budi Santoso','Dani Pratama',
                         'Fahri Maulana','Irfan Hakim','Miftah Rizky','Naufal Hasan','Omar Syarif',
                         'Pandu Wijaya','Qodir Rahman','Rizal Pratama','Syamsul Bahri','Taufik Hidayat'][($i-1)],
            'asrama' => ['Al-Farabi','Al-Ghazali','Ibnu Sina','Al-Kindi'][($i-1) % 4],
            'points' => max(100, 2500 - ($i * 95) + rand(-20,20)),
            'shalat' => rand(80,100),
            'hafalan'=> rand(60,98),
            'akademik'=> rand(70,97),
            'badge'  => $i === 1 ? '🥇' : ($i === 2 ? '🥈' : ($i === 3 ? '🥉' : null)),
        ]);

        return Inertia::render('Super/Leaderboard', [
            'entries' => $entries->values(),
            'asramas' => ['Al-Farabi','Al-Ghazali','Ibnu Sina','Al-Kindi'],
            'stats'   => [
                'top_asrama' => 'Al-Ghazali',
                'avg_points' => round($entries->avg('points')),
                'total'      => $entries->count(),
            ],
        ]);
    }

    // ── Laporan ───────────────────────────────────────────────
    public function laporan()
    {
        $months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
        $trendData = collect(range(0, 11))->map(fn($i) => [
            'bulan'   => $months[$i],
            'shalat'  => rand(75, 95),
            'hafalan' => rand(60, 88),
            'akademik'=> rand(70, 92),
            'kegiatan'=> rand(65, 85),
        ]);

        $asramaPerf = [
            ['asrama'=>'Al-Farabi',  'avg'=>82, 'warga'=>6],
            ['asrama'=>'Al-Ghazali', 'avg'=>88, 'warga'=>5],
            ['asrama'=>'Ibnu Sina',  'avg'=>79, 'warga'=>5],
            ['asrama'=>'Al-Kindi',   'avg'=>85, 'warga'=>5],
        ];

        return Inertia::render('Super/Laporan', [
            'trend'       => $trendData->values(),
            'asramaPerf'  => $asramaPerf,
            'stats' => [
                'total_warga'   => 120,
                'avg_skor'      => 84,
                'total_kegiatan'=> 24,
                'persen_aktif'  => 94,
            ],
        ]);
    }

    // ── Pengaturan ────────────────────────────────────────────
    public function pengaturan()
    {
        return Inertia::render('Super/Pengaturan', [
            'settings' => [
                'app_name'         => config('app.name', 'SIMONAS'),
                'app_url'          => config('app.url'),
                'mail_driver'      => env('MAIL_MAILER', 'smtp'),
                'google_oauth'     => !empty(env('GOOGLE_CLIENT_ID')),
                'shalat_target'    => 5,
                'hafalan_target'   => 30,
                'study_hour_target'=> 4,
                'point_shalat'     => 10,
                'point_hafalan'    => 25,
                'point_akademik'   => 15,
                'point_kegiatan'   => 20,
                'maintenance_mode' => false,
            ],
        ]);
    }
}
