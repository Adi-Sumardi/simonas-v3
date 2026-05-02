<?php

namespace App\Http\Controllers\Web\Super;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Alumni;
use App\Models\Kegiatan;
use App\Models\UserEvent;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class SuperController extends Controller
{
    // ── Warga ─────────────────────────────────────────────────
    public function warga(Request $request)
    {
        $query = User::where('role', 'mahasiswa');

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('no_induk', 'like', "%{$request->search}%");
            });
        }

        if ($request->asrama) {
            $query->where('asrama', $request->asrama);
        }

        if ($request->status) {
            $query->where('status_warga', $request->status);
        }

        $warga = $query->paginate($request->per_page ?? 10)->withQueryString();

        return Inertia::render('Super/Warga', [
            'warga'   => $warga,
            'asramas' => ['Al-Farabi', 'Al-Ghazali', 'Ibnu Sina', 'Al-Kindi'],
            'stats'   => [
                'total'   => User::where('role', 'mahasiswa')->count(),
                'aktif'   => User::where('role', 'mahasiswa')->where('status_warga', 'aktif')->count(),
                'nonaktif'=> User::where('role', 'mahasiswa')->where('status_warga', 'nonaktif')->count(),
                'avg_skor'=> 85,
            ],
            'filters' => $request->only(['search', 'asrama', 'status', 'per_page']),
        ]);
    }

    // ── Mentor ────────────────────────────────────────────────
    public function mentor(Request $request)
    {
        $query = User::where('role', 'mentor')->with(['mentees']);

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        $mentors = $query->paginate($request->per_page ?? 10)->withQueryString();

        // Calculate active status and stats
        $mappedMentors = $mentors->getCollection()->map(function($m) {
            $lastAkademik = \App\Models\Akademik::where('nama_penilai', $m->name)->latest('updated_at')->first();
            $lastHafalan = \App\Models\HafalanLog::where('mentor_id', $m->id)->latest('updated_at')->first();

            $lastDate = collect([$lastAkademik?->updated_at, $lastHafalan?->updated_at])->filter()->max();
            $isActive = $lastDate && $lastDate->gt(now()->subDays(30));

            // Count assessments
            $categories = [];
            $counts = [
                'akademik'    => \App\Models\Akademik::where('nama_penilai', $m->name)->count(),
                'leadership'  => \App\Models\Leadership::where('nama_penilai', $m->name)->count(),
                'karakter'    => \App\Models\Karakter::where('nama_penilai', $m->name)->count(),
                'kreatif'     => \App\Models\Kreatif::where('nama_penilai', $m->name)->count(),
                'hafalan'     => \App\Models\HafalanLog::where('mentor_id', $m->id)->count(),
            ];

            foreach ($counts as $cat => $count) {
                if ($count > 0) $categories[] = ucfirst($cat);
            }

            return [
                'id'            => $m->id,
                'name'          => $m->name,
                'email'         => $m->email,
                'avatar'        => $m->avatar,
                'asrama'        => $m->asrama,
                'no_telp'       => $m->no_telp,
                'is_active'     => $isActive,
                'last_login'    => $m->last_login_at ? $m->last_login_at->format('d M Y, H:i') : 'Belum pernah login',
                'last_activity' => $lastDate ? $lastDate->format('d M Y') : 'Belum ada log',
                'total_nilai'   => array_sum($counts),
                'categories'    => $categories,
                'mentee_count'  => $m->mentees->count(),
                'mentees'       => $m->mentees->map(fn($st) => [
                    'id'     => $st->id,
                    'name'   => $st->name,
                    'asrama' => $st->asrama,
                    'avatar' => $st->avatar,
                ]),
            ];
        });

        $mentors->setCollection($mappedMentors);

        return Inertia::render('Super/Mentor', [
            'mentors' => $mentors,
            'stats'   => [
                'total'  => User::where('role', 'mentor')->count(),
                'active' => $mappedMentors->where('is_active', true)->count(),
            ],
            'filters' => $request->only(['search', 'per_page']),
        ]);
    }

    public function mentorAnalysis($id)
    {
        $mentor = User::where('id', $id)->where('role', 'mentor')->with(['mentees'])->firstOrFail();

        // 1. Mentor Activity Trend (last 6 months)
        $activityTrend = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $start = $month->copy()->startOfMonth();
            $end   = $month->copy()->endOfMonth();

            $count = \App\Models\Akademik::where('nama_penilai', $mentor->name)
                ->whereBetween('created_at', [$start, $end])->count();
            $count += \App\Models\Leadership::where('nama_penilai', $mentor->name)
                ->whereBetween('created_at', [$start, $end])->count();
            $count += \App\Models\Karakter::where('nama_penilai', $mentor->name)
                ->whereBetween('created_at', [$start, $end])->count();
            $count += \App\Models\Kreatif::where('nama_penilai', $mentor->name)
                ->whereBetween('created_at', [$start, $end])->count();
            $count += \App\Models\HafalanLog::where('mentor_id', $mentor->id)
                ->whereBetween('created_at', [$start, $end])->count();

            $activityTrend[] = [
                'month' => $month->format('M'),
                'count' => $count
            ];
        }

        // 2. Mentees Progress (Averages)
        $menteeStats = $mentor->mentees->map(function($st) {
            return [
                'name'   => $st->name,
                'avatar' => $st->avatar,
                'values' => [
                    ['subject' => 'Akademik',   'A' => (float)(\App\Models\Akademik::where('user_id', $st->id)->avg('nilai') ?? 0), 'fullMark' => 100],
                    ['subject' => 'Leadership', 'A' => (float)(\App\Models\Leadership::where('user_id', $st->id)->avg('nilai') ?? 0), 'fullMark' => 100],
                    ['subject' => 'Karakter',   'A' => (float)(\App\Models\Karakter::where('user_id', $st->id)->avg('nilai') ?? 0), 'fullMark' => 100],
                    ['subject' => 'Kreativitas','A' => (float)(\App\Models\Kreatif::where('user_id', $st->id)->avg('nilai') ?? 0), 'fullMark' => 100],
                    ['subject' => 'Hafalan',    'A' => (float)(\App\Models\HafalanLog::where('user_id', $st->id)->where('score', 'memtas')->count() * 10), 'fullMark' => 100],
                ]
            ];
        });

        return Inertia::render('Super/MentorAnalysis', [
            'mentor'        => $mentor,
            'activityTrend' => $activityTrend,
            'menteeStats'   => $menteeStats,
        ]);
    }

    // ── Alumni ────────────────────────────────────────────────
    public function alumni(Request $request)
    {
        // Alumni are users with role 'alumni'
        $query = User::where('role', 'alumni')->with(['alumni', 'profilRiwayats']);

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        if ($request->asrama) {
            $query->where('asrama', $request->asrama);
        }

        if ($request->angkatan) {
            $query->where('angkatan', $request->angkatan);
        }

        $users = $query->paginate($request->per_page ?? 12)->withQueryString();

        // Map User + Alumni data for frontend
        $mappedData = $users->getCollection()->map(function($user) {
            $alumniMeta = $user->alumni;
            $riwayats   = $user->profilRiwayats;

            return [
                'id'                  => $user->id,
                'nama'                => $user->name,
                'email'               => $user->email,
                'no_whatsapp'         => $user->no_telp ?? $alumniMeta?->no_whatsapp,
                'foto'                => $user->avatar,
                'asal_asrama'         => $user->asrama,
                'tahun_masuk_asrama'  => (int)$user->angkatan,
                'tahun_keluar_asrama' => $alumniMeta?->tahun_keluar_asrama,
                'pekerjaan_sekarang'  => $riwayats->where('tipe', 'pekerjaan')->where('masih_berlangsung', true)->first()?->posisi ?? $alumniMeta?->pekerjaan_sekarang,
                'alamat_domisili'     => $alumniMeta?->alamat_domisili ?? $user->alamat_sekarang,
                'bidang_keahlian'     => $alumniMeta?->bidang_keahlian,
                'nia'                 => $user->no_induk ?? $alumniMeta?->nia,
                'provinsi_asal'       => $user->provinsi ?? $alumniMeta?->provinsi_asal,
                'tanggal_lahir'       => $user->tgl_lahir ?? $alumniMeta?->tanggal_lahir,
                
                // Tabs data from profil_riwayats
                'pendidikan' => $riwayats->where('tipe', 'pendidikan')->map(fn($r) => [
                    'nama_sekolah'  => $r->judul,
                    'jenjang'       => $r->posisi,
                    'program_studi' => $r->deskripsi,
                    'tahun_lulus'   => $r->selesai ? date('Y', strtotime($r->selesai)) : 'Sekarang',
                ])->values(),
                
                'pekerjaan' => $riwayats->where('tipe', 'pekerjaan')->map(fn($r) => [
                    'nama_perusahaan' => $r->judul,
                    'jabatan'         => $r->posisi,
                    'tahun_masuk'     => $r->mulai ? date('Y', strtotime($r->mulai)) : '',
                    'tahun_keluar'    => $r->selesai ? date('Y', strtotime($r->selesai)) : ($r->masih_berlangsung ? 'Sekarang' : ''),
                ])->values(),
                
                'organisasi' => $riwayats->where('tipe', 'organisasi')->map(fn($r) => [
                    'nama_organisasi' => $r->judul,
                    'jabatan'         => $r->posisi,
                    'tahun_aktif'     => ($r->mulai ? date('Y', strtotime($r->mulai)) : '') . ($r->selesai ? ' - '.date('Y', strtotime($r->selesai)) : ''),
                ])->values(),
                
                'prestasi' => $riwayats->where('tipe', 'penghargaan')->map(fn($r) => [
                    'nama_prestasi' => $r->judul,
                    'penyelenggara' => $r->posisi,
                    'tahun'         => $r->mulai ? date('Y', strtotime($r->mulai)) : '',
                ])->values(),
            ];
        });

        $users->setCollection($mappedData);

        return Inertia::render('Super/Alumni', [
            'alumni'      => $users,
            'asrama_list' => ['Al-Farabi', 'Al-Ghazali', 'Ibnu Sina', 'Al-Kindi'],
            'stats'       => [
                'total'         => User::where('role', 'alumni')->count(),
                'hafidz'        => 0, // Placeholder
                'angkatan_list' => User::where('role', 'alumni')->whereNotNull('angkatan')->select('angkatan')->distinct()->orderBy('angkatan', 'desc')->pluck('angkatan'),
            ],
            'filters' => $request->only(['search', 'asrama', 'angkatan', 'per_page']),
        ]);
    }

    // ── Kegiatan ──────────────────────────────────────────────
    public function kegiatan(Request $request)
    {
        $query = Kegiatan::query();

        if ($request->status === 'upcoming') {
            $query->where('waktu', '>=', now());
        } elseif ($request->status === 'selesai') {
            $query->where('waktu', '<', now());
        }

        $kegiatan = $query->orderBy('waktu', 'desc')->paginate($request->per_page ?? 12)->withQueryString();

        return Inertia::render('Super/Kegiatan', [
            'kegiatan' => $kegiatan,
            'stats'    => [
                'upcoming' => Kegiatan::where('waktu', '>=', now())->count(),
                'selesai'  => Kegiatan::where('waktu', '<', now())->count(),
                'total'    => Kegiatan::count(),
            ],
            'filters' => $request->only(['status', 'per_page']),
        ]);
    }

    public function storeKegiatan(Request $request)
    {
        $data = $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'tujuan'        => 'nullable|string',
            'penyelenggara' => 'required|string|max:255',
            'jenis_kegiatan'=> 'required|string',
            'waktu'         => 'required|date',
            'tempat'        => 'required|string|max:255',
            'keterangan'    => 'nullable|string',
        ]);

        DB::transaction(function() use ($data) {
            $kegiatan = Kegiatan::create($data);

            $mahasiswa = User::where('role', 'mahasiswa')->get();
            $dt = new \DateTime($data['waktu']);
            
            foreach ($mahasiswa as $user) {
                UserEvent::create([
                    'user_id'      => $user->id,
                    'title'        => $data['nama_kegiatan'],
                    'date'         => $dt->format('Y-m-d'),
                    'time'         => $dt->format('H:i'),
                    'type'         => 'kegiatan',
                    'color'        => '#6366f1',
                    'desc'         => $data['keterangan'] ?? "Kegiatan Pesantren: {$data['nama_kegiatan']}",
                    'recurring'    => false,
                    'is_mandatory' => true,
                ]);
            }
        });

        return back()->with('success', 'Kegiatan berhasil dibuat dan disinkronkan ke kalender mahasiswa.');
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
