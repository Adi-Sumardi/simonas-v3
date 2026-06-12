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

        $perPage = min((int)($request->per_page ?? 10), 100);
        $warga = $query->select(['id','name','email','no_induk','asrama','status_warga','role','tgl_masuk','angkatan','avatar','no_telp'])
            ->paginate($perPage)->withQueryString();

        return Inertia::render('Super/Warga', [
            'warga'   => $warga,
            'asramas' => \App\Models\Asrama::orderBy('nama_asrama')->pluck('nama_asrama'),
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

        $perPage = min((int)($request->per_page ?? 10), 100);
        $mentors = $query->paginate($perPage)->withQueryString();

        // Bulk-compute per-mentor stats to avoid N+1
        $mentorIds   = $mentors->getCollection()->pluck('id');
        $mentorNames = $mentors->getCollection()->pluck('name');

        $bulkAkademik   = \App\Models\Akademik::whereIn('nama_penilai', $mentorNames)->selectRaw('nama_penilai, COUNT(*) as cnt, MAX(updated_at) as last_at')->groupBy('nama_penilai')->get()->keyBy('nama_penilai');
        $bulkLeadership = \App\Models\Leadership::whereIn('nama_penilai', $mentorNames)->selectRaw('nama_penilai, COUNT(*) as cnt')->groupBy('nama_penilai')->pluck('cnt', 'nama_penilai');
        $bulkKarakter   = \App\Models\Karakter::whereIn('nama_penilai', $mentorNames)->selectRaw('nama_penilai, COUNT(*) as cnt')->groupBy('nama_penilai')->pluck('cnt', 'nama_penilai');
        $bulkKreatif    = \App\Models\Kreatif::whereIn('nama_penilai', $mentorNames)->selectRaw('nama_penilai, COUNT(*) as cnt')->groupBy('nama_penilai')->pluck('cnt', 'nama_penilai');
        $bulkHafalan    = \App\Models\HafalanLog::whereIn('mentor_id', $mentorIds)->selectRaw('mentor_id, COUNT(*) as cnt, MAX(updated_at) as last_at')->groupBy('mentor_id')->get()->keyBy('mentor_id');

        $mappedMentors = $mentors->getCollection()->map(function($m) use ($bulkAkademik, $bulkLeadership, $bulkKarakter, $bulkKreatif, $bulkHafalan) {
            $lastAkademikAt = $bulkAkademik[$m->name]?->last_at ? \Carbon\Carbon::parse($bulkAkademik[$m->name]->last_at) : null;
            $lastHafalanAt  = $bulkHafalan[$m->id]?->last_at ? \Carbon\Carbon::parse($bulkHafalan[$m->id]->last_at) : null;
            $lastDate = collect([$lastAkademikAt, $lastHafalanAt])->filter()->max();
            $isActive = $lastDate && $lastDate->gt(now()->subDays(30));

            $counts = [
                'akademik'   => (int)($bulkAkademik[$m->name]?->cnt ?? 0),
                'leadership' => (int)($bulkLeadership[$m->name] ?? 0),
                'karakter'   => (int)($bulkKarakter[$m->name] ?? 0),
                'kreatif'    => (int)($bulkKreatif[$m->name] ?? 0),
                'hafalan'    => (int)($bulkHafalan[$m->id]?->cnt ?? 0),
            ];
            $categories = array_keys(array_filter($counts, fn($c) => $c > 0));
            $categories = array_map('ucfirst', $categories);

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
                'categories'    => array_values($categories),
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

        if ($request->tahun_dari) {
            $query->where('angkatan', '>=', $request->tahun_dari);
        }

        if ($request->tahun_sampai) {
            $query->where('angkatan', '<=', $request->tahun_sampai);
        }

        $perPage = min((int)($request->per_page ?? 12), 100);
        $users = $query->paginate($perPage)->withQueryString();

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

        $angkatanList = User::where('role', 'alumni')
            ->whereNotNull('angkatan')
            ->select('angkatan')
            ->distinct()
            ->orderBy('angkatan')
            ->pluck('angkatan')
            ->map(fn($a) => (int) $a)
            ->filter()
            ->values();

        return Inertia::render('Super/Alumni', [
            'alumni'      => $users,
            'asrama_list' => \App\Models\Asrama::orderBy('nama_asrama')->pluck('nama_asrama'),
            'stats'       => [
                'total'         => User::where('role', 'alumni')->count(),
                'hafidz'        => 0,
                'angkatan_list' => $angkatanList,
                'tahun_min'     => $angkatanList->first() ?? (int) now()->year,
                'tahun_max'     => $angkatanList->last()  ?? (int) now()->year,
            ],
            'filters' => $request->only(['search', 'asrama', 'tahun_dari', 'tahun_sampai', 'per_page']),
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

        $perPage = min((int)($request->per_page ?? 12), 100);
        $kegiatan = $query->orderBy('waktu', 'desc')->paginate($perPage)->withQueryString();

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

        $asramaNames = \App\Models\Asrama::orderBy('nama_asrama')->pluck('nama_asrama');

        return Inertia::render('Super/Leaderboard', [
            'entries' => $entries->values(),
            'asramas' => $asramaNames,
            'stats'   => [
                'top_asrama' => $asramaNames->first() ?? '-',
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

        $asramaPerf = \App\Models\Asrama::orderBy('nama_asrama')->get()->map(function($a) {
            $warga = \App\Models\User::where('role', 'mahasiswa')->where('asrama', $a->nama_asrama)->count();
            return [
                'asrama' => $a->nama_asrama,
                'avg'    => 80, // placeholder until real scoring is wired
                'warga'  => $warga,
            ];
        })->values()->toArray();

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
        $s = \App\Models\AppSetting::allValues();

        return Inertia::render('Super/Pengaturan', [
            'asramas'      => \App\Models\Asrama::with('jabatans')->orderBy('nama_asrama')->get(),
            'pointRules'   => \App\Models\PointRule::orderBy('id')->get(),
            'dailyTargets' => \App\Models\DailyTarget::orderBy('id')->get(),
            'activityTypes'=> \App\Models\PointRule::TYPES,
            'settings'     => [
                'app_name'         => config('app.name', 'SIMONAS'),
                'app_url'          => config('app.url'),
                'mail_driver'      => env('MAIL_MAILER', 'smtp'),
                'google_oauth'     => !empty(env('GOOGLE_CLIENT_ID')),
                'maintenance_mode' => (bool) ($s['maintenance_mode'] ?? false),
            ],
        ]);
    }

    public function updatePengaturan(Request $request)
    {
        $data = $request->validate([
            'maintenance_mode' => 'boolean',
        ]);

        \App\Models\AppSetting::setMany($data);

        return back()->with('success', 'Pengaturan berhasil disimpan.');
    }

    // ── Point Rules CRUD ──────────────────────────────────────
    public function storePointRule(Request $request)
    {
        $data = $request->validate([
            'label'         => 'required|string|max:100',
            'activity_type' => 'required|string|in:' . implode(',', array_keys(\App\Models\PointRule::TYPES)),
            'poin'          => 'required|integer|min:0',
            'unit'          => 'nullable|string|max:100',
        ]);
        \App\Models\PointRule::create(array_merge($data, ['is_active' => true]));
        \App\Models\PointRule::clearCache();
        return back()->with('success', 'Aturan poin berhasil ditambahkan.');
    }

    public function updatePointRule(Request $request, \App\Models\PointRule $rule)
    {
        $data = $request->validate([
            'label'         => 'required|string|max:100',
            'activity_type' => 'required|string|in:' . implode(',', array_keys(\App\Models\PointRule::TYPES)),
            'poin'          => 'required|integer|min:0',
            'unit'          => 'nullable|string|max:100',
        ]);
        $rule->update($data);
        \App\Models\PointRule::clearCache();
        return back()->with('success', 'Aturan poin berhasil diperbarui.');
    }

    public function destroyPointRule(\App\Models\PointRule $rule)
    {
        $rule->delete();
        \App\Models\PointRule::clearCache();
        return back()->with('success', 'Aturan poin dihapus.');
    }

    public function togglePointRule(\App\Models\PointRule $rule)
    {
        $rule->update(['is_active' => !$rule->is_active]);
        \App\Models\PointRule::clearCache();
        return back()->with('success', 'Status aturan poin diperbarui.');
    }

    // ── Daily Targets CRUD ────────────────────────────────────
    public function storeDailyTarget(Request $request)
    {
        $data = $request->validate([
            'label'       => 'required|string|max:100',
            'key'         => 'required|string|max:60|unique:daily_targets,key|alpha_dash',
            'value'       => 'required|integer|min:0',
            'unit'        => 'nullable|string|max:60',
            'description' => 'nullable|string|max:255',
        ]);
        \App\Models\DailyTarget::create(array_merge($data, ['is_active' => true]));
        \App\Models\DailyTarget::clearCache();
        return back()->with('success', 'Target berhasil ditambahkan.');
    }

    public function updateDailyTarget(Request $request, \App\Models\DailyTarget $target)
    {
        $data = $request->validate([
            'label'       => 'required|string|max:100',
            'value'       => 'required|integer|min:0',
            'unit'        => 'nullable|string|max:60',
            'description' => 'nullable|string|max:255',
        ]);
        $target->update($data);
        \App\Models\DailyTarget::clearCache();
        return back()->with('success', 'Target berhasil diperbarui.');
    }

    public function destroyDailyTarget(\App\Models\DailyTarget $target)
    {
        $target->delete();
        \App\Models\DailyTarget::clearCache();
        return back()->with('success', 'Target dihapus.');
    }

    public function toggleDailyTarget(\App\Models\DailyTarget $target)
    {
        $target->update(['is_active' => !$target->is_active]);
        \App\Models\DailyTarget::clearCache();
        return back()->with('success', 'Status target diperbarui.');
    }

    // ── Asrama CRUD ───────────────────────────────────────────
    public function storeAsrama(Request $request)
    {
        $data = $request->validate([
            'nama_asrama'    => 'required|string|max:100|unique:asramas,nama_asrama',
            'kapasitas'      => 'nullable|integer|min:1',
            'direktur'       => 'nullable|string|max:100',
            'ketua'          => 'nullable|string|max:100',
        ]);
        \App\Models\Asrama::create($data);
        return back()->with('success', 'Asrama berhasil ditambahkan.');
    }

    public function updateAsrama(Request $request, \App\Models\Asrama $asrama)
    {
        $data = $request->validate([
            'nama_asrama' => 'required|string|max:100|unique:asramas,nama_asrama,' . $asrama->id,
            'kapasitas'   => 'nullable|integer|min:1',
            'direktur'    => 'nullable|string|max:100',
            'ketua'       => 'nullable|string|max:100',
        ]);

        $oldName = $asrama->nama_asrama;
        $asrama->update($data);

        if ($oldName !== $data['nama_asrama']) {
            \App\Models\User::where('asrama', $oldName)->update(['asrama' => $data['nama_asrama']]);
        }

        return back()->with('success', 'Asrama berhasil diperbarui.');
    }

    public function destroyAsrama(\App\Models\Asrama $asrama)
    {
        \App\Models\User::where('asrama', $asrama->nama_asrama)->update(['asrama' => null]);
        $asrama->delete();
        return back()->with('success', 'Asrama berhasil dihapus.');
    }

    // ── Jabatan CRUD ──────────────────────────────────────────
    public function storeJabatan(Request $request, \App\Models\Asrama $asrama)
    {
        $data = $request->validate([
            'tahun'    => 'required|integer|min:2000|max:2100',
            'direktur' => 'nullable|string|max:100',
            'ketua'    => 'nullable|string|max:100',
        ]);
        $asrama->jabatans()->updateOrCreate(['tahun' => $data['tahun']], $data);
        return back()->with('success', 'Data jabatan berhasil disimpan.');
    }

    public function updateJabatan(Request $request, \App\Models\Asrama $asrama, \App\Models\AsramaJabatan $jabatan)
    {
        abort_if($jabatan->asrama_id !== $asrama->id, 403);
        $data = $request->validate([
            'tahun'    => 'required|integer|min:2000|max:2100',
            'direktur' => 'nullable|string|max:100',
            'ketua'    => 'nullable|string|max:100',
        ]);
        $jabatan->update($data);
        return back()->with('success', 'Data jabatan berhasil diperbarui.');
    }

    public function destroyJabatan(\App\Models\Asrama $asrama, \App\Models\AsramaJabatan $jabatan)
    {
        abort_if($jabatan->asrama_id !== $asrama->id, 403);
        $jabatan->delete();
        return back()->with('success', 'Data jabatan dihapus.');
    }
}
