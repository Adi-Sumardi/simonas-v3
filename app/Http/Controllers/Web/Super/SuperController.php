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
    // Target jumlah aktivitas (akademik+leadership+karakter+kreatif) per bulan.
    private const MONTHLY_ACTIVITY_TARGET = 120;

    // ── Warga ─────────────────────────────────────────────────
    public function storeWarga(Request $request)
    {
        $data = $request->validate([
            'name'                 => 'required|string|max:255',
            'email'                => 'required|email|max:255|unique:users,email',
            'password'             => 'required|string|min:8',
            'no_induk'             => 'nullable|string|max:50',
            'asrama'               => 'nullable|string|max:255',
            'status_warga'         => 'nullable|string|in:aktif,nonaktif',
            'angkatan'             => 'nullable|string|max:10',
            'semester'             => 'nullable|integer|min:1|max:14',
            'tingkat_keanggotaan'  => 'nullable|string|in:percobaan,tetap,senior',
            'tgl_mulai_percobaan'  => 'nullable|date',
            'tgl_akhir_percobaan'  => 'nullable|date',
        ]);

        $warga = User::create([
            'name'                 => $data['name'],
            'email'                => $data['email'],
            'password'             => \Illuminate\Support\Facades\Hash::make($data['password']),
            'role'                 => 'mahasiswa',
            'no_induk'             => $data['no_induk'] ?? null,
            'asrama'               => $data['asrama'] ?? null,
            'status_warga'         => $data['status_warga'] ?? 'aktif',
            'angkatan'             => $data['angkatan'] ?? null,
            'semester'             => $data['semester'] ?? null,
            'tingkat_keanggotaan'  => $data['tingkat_keanggotaan'] ?? 'percobaan',
            'tgl_mulai_percobaan'  => $data['tgl_mulai_percobaan'] ?? null,
            'tgl_akhir_percobaan'  => $data['tgl_akhir_percobaan'] ?? null,
        ]);
        $warga->assignRole('mahasiswa');

        return back()->with('success', "Warga '{$warga->name}' berhasil ditambahkan.");
    }

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

        if ($request->semester) {
            $query->where('semester', $request->semester);
        }

        if ($request->tingkat) {
            $query->where('tingkat_keanggotaan', $request->tingkat);
        }

        if ($request->percobaan_min_bulan) {
            $query->whereNotNull('tgl_mulai_percobaan')
                ->where('tgl_mulai_percobaan', '<=', now()->subMonths((int) $request->percobaan_min_bulan));
        }

        $perPage = min((int)($request->per_page ?? 10), 100);
        $warga = $query->select(['id','name','email','no_induk','asrama','status_warga','tingkat_keanggotaan','semester','tgl_mulai_percobaan','tgl_akhir_percobaan','role','tgl_masuk','angkatan','avatar','no_telp'])
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
            'filters' => $request->only(['search', 'asrama', 'status', 'per_page', 'semester', 'tingkat', 'percobaan_min_bulan']),
        ]);
    }

    public function wargaDetail($id, Request $request)
    {
        $warga = User::where('role', 'mahasiswa')->findOrFail($id);

        $radarFrom = $request->query('radar_from') ?: now()->startOfMonth()->toDateString();
        $radarTo   = $request->query('radar_to')   ?: now()->endOfMonth()->toDateString();

        return Inertia::render('Super/WargaDetail', [
            'warga' => $warga,
            'stats' => [
                'akademik'   => \App\Models\Akademik::where('user_id', $warga->id)->count(),
                'leadership' => \App\Models\Leadership::where('user_id', $warga->id)->count(),
                'karakter'   => \App\Models\Karakter::where('user_id', $warga->id)->count(),
                'kreatif'    => \App\Models\Kreatif::where('user_id', $warga->id)->count(),
                'points'     => $warga->calculatePoints(),
            ],
            'radarScores' => $warga->radarScores($radarFrom, $radarTo),
            'radarRange'  => ['from' => $radarFrom, 'to' => $radarTo],
            'ipks'      => \App\Models\Ipk::where('user_id', $warga->id)->orderByDesc('semester')->get(),
            'hafalan'   => \App\Models\Hafalan::where('user_id', $warga->id)->first(),
            'akademiks'   => $this->mapAktivitasFile(\App\Models\Akademik::where('user_id', $warga->id)->latest()->limit(10)->get(), 'akademiks'),
            'leaderships' => $this->mapAktivitasFile(\App\Models\Leadership::where('user_id', $warga->id)->latest()->limit(10)->get(), 'leaderships'),
            'karakters'   => $this->mapAktivitasFile(\App\Models\Karakter::where('user_id', $warga->id)->latest()->limit(10)->get(), 'karakters'),
            'kreatifs'    => $this->mapAktivitasFile(\App\Models\Kreatif::where('user_id', $warga->id)->latest()->limit(10)->get(), 'kreatifs'),
        ]);
    }

    /**
     * Ganti kolom `file` (nama file asli, BLOB tersimpan di `file_data`) jadi
     * URL yang bisa dibuka lewat FileController, plus `file_name` buat deteksi
     * tipe (mis. PDF) di frontend.
     */
    private function mapAktivitasFile(\Illuminate\Support\Collection $rows, string $table): \Illuminate\Support\Collection
    {
        return $rows->map(function ($r) use ($table) {
            $arr = $r->toArray();
            $arr['file_name'] = $r->file;
            $arr['file'] = $r->file_data ? route('files.show', ['table' => $table, 'id' => $r->id]) : null;
            return $arr;
        });
    }

    public function wargaEdit($id)
    {
        $warga = User::where('role', 'mahasiswa')->findOrFail($id);

        return Inertia::render('Super/WargaEdit', [
            'warga'   => $warga,
            'asramas' => \App\Models\Asrama::orderBy('nama_asrama')->pluck('nama_asrama'),
        ]);
    }

    public function wargaUpdate(Request $request, $id)
    {
        $warga = User::where('role', 'mahasiswa')->findOrFail($id);

        $data = $request->validate([
            'name'                 => 'required|string|max:255',
            'email'                => 'required|email|max:255|unique:users,email,' . $warga->id,
            'no_induk'             => 'nullable|string|max:50',
            'asrama'               => 'nullable|string|max:255',
            'status_warga'         => 'nullable|string|in:aktif,nonaktif',
            'tgl_masuk'            => 'nullable|date',
            'angkatan'             => 'nullable|string|max:10',
            'no_telp'              => 'nullable|string|max:30',
            'alamat'               => 'nullable|string|max:500',
            'semester'             => 'nullable|integer|min:1|max:14',
            'tingkat_keanggotaan'  => 'nullable|string|in:percobaan,tetap,senior',
            'tgl_mulai_percobaan'  => 'nullable|date',
            'tgl_akhir_percobaan'  => 'nullable|date',
        ]);

        $warga->update($data);

        return redirect()->route('super.warga.detail', $warga->id)->with('success', 'Data warga berhasil diperbarui.');
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
            $akademikRow  = $bulkAkademik->get($m->name);
            $hafalanRow   = $bulkHafalan->get($m->id);
            $lastAkademikAt = $akademikRow?->last_at ? \Carbon\Carbon::parse($akademikRow->last_at) : null;
            $lastHafalanAt  = $hafalanRow?->last_at ? \Carbon\Carbon::parse($hafalanRow->last_at) : null;
            $lastDate = collect([$lastAkademikAt, $lastHafalanAt])->filter()->max();
            $isActive = $lastDate && $lastDate->gt(now()->subDays(30));

            $counts = [
                'akademik'   => (int) ($akademikRow?->cnt ?? 0),
                'leadership' => (int) $bulkLeadership->get($m->name, 0),
                'karakter'   => (int) $bulkKarakter->get($m->name, 0),
                'kreatif'    => (int) $bulkKreatif->get($m->name, 0),
                'hafalan'    => (int) ($hafalanRow?->cnt ?? 0),
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
            // CAST nilai (varchar) to numeric for PostgreSQL compatibility
            $avgVal = fn ($model) => (float) ($model::where('user_id', $st->id)
                ->selectRaw("AVG(NULLIF(nilai, '')::numeric) as avg_val")
                ->value('avg_val') ?? 0);
            return [
                'name'   => $st->name,
                'avatar' => $st->avatar,
                'values' => [
                    ['subject' => 'Akademik',   'A' => $avgVal(\App\Models\Akademik::class),   'fullMark' => 100],
                    ['subject' => 'Leadership', 'A' => $avgVal(\App\Models\Leadership::class), 'fullMark' => 100],
                    ['subject' => 'Karakter',   'A' => $avgVal(\App\Models\Karakter::class),   'fullMark' => 100],
                    ['subject' => 'Kreativitas','A' => $avgVal(\App\Models\Kreatif::class),    'fullMark' => 100],
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
            'wajib_absen'   => 'nullable|boolean',
            'waktu'         => 'required|date',
            'tempat'        => 'required|string|max:255',
            'keterangan'    => 'nullable|string',
        ]);
        $data['wajib_absen'] = $request->boolean('wajib_absen');

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
                    'desc'         => $data['keterangan'] ?? "Kegiatan Asrama: {$data['nama_kegiatan']}",
                    'recurring'    => false,
                    'is_mandatory' => true,
                ]);
            }
        });

        return back()->with('success', 'Kegiatan berhasil dibuat dan disinkronkan ke kalender mahasiswa.');
    }

    public function updateKegiatan(Request $request, Kegiatan $kegiatan)
    {
        $data = $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'tujuan'        => 'nullable|string',
            'penyelenggara' => 'required|string|max:255',
            'jenis_kegiatan'=> 'required|string',
            'wajib_absen'   => 'nullable|boolean',
            'waktu'         => 'required|date',
            'tempat'        => 'required|string|max:255',
            'keterangan'    => 'nullable|string',
        ]);
        $data['wajib_absen'] = $request->boolean('wajib_absen');

        $kegiatan->update($data);

        return back()->with('success', 'Kegiatan berhasil diperbarui.');
    }

    public function destroyKegiatan(Kegiatan $kegiatan)
    {
        $kegiatan->delete();

        return back()->with('success', 'Kegiatan berhasil dihapus.');
    }

    public function kegiatanAttendance(Kegiatan $kegiatan)
    {
        $items = $kegiatan->attendances()
            ->with('user:id,name,avatar,asrama')
            ->orderByDesc('waktu_absen')
            ->get()
            ->map(fn (\App\Models\KegiatanAttendance $a) => [
                'id'          => $a->id,
                'user'        => $a->user->only(['id', 'name', 'avatar', 'asrama']),
                'asrama'      => $a->asrama,
                'waktu_absen' => $a->waktu_absen->format('Y-m-d H:i'),
                'latitude'    => $a->latitude,
                'longitude'   => $a->longitude,
                'alamat'      => $a->alamat,
                'selfie_url'  => $a->file_selfie_data ? route('files.show', ['table' => 'kegiatan_attendances', 'id' => $a->id, 'slot' => 'selfie']) : null,
                'lokasi_url'  => $a->file_lokasi_data ? route('files.show', ['table' => 'kegiatan_attendances', 'id' => $a->id, 'slot' => 'lokasi']) : null,
            ]);

        return Inertia::render('Super/KegiatanAttendance', [
            'kegiatan' => $kegiatan,
            'items'    => $items,
            'asramas'  => \App\Models\Asrama::orderBy('nama_asrama')->pluck('nama_asrama'),
            'warga'    => User::where('role', 'mahasiswa')->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function storeKegiatanAttendance(Request $request, Kegiatan $kegiatan)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $mahasiswa = User::findOrFail($data['user_id']);

        \App\Models\KegiatanAttendance::firstOrCreate(
            ['kegiatan_id' => $kegiatan->id, 'user_id' => $mahasiswa->id],
            ['asrama' => $mahasiswa->asrama, 'waktu_absen' => now(), 'dicatat_oleh' => $request->user()->id]
        );

        return back()->with('success', 'Kehadiran berhasil dicatat.');
    }

    public function destroyKegiatanAttendance(\App\Models\KegiatanAttendance $attendance)
    {
        $attendance->delete();

        return back()->with('success', 'Data kehadiran berhasil dihapus.');
    }

    // ── Hafalan ───────────────────────────────────────────────
    public function hafalan()
    {
        $students = User::where('role', 'mahasiswa')->with(['hafalan', 'mentor'])->get();

        $data = $students->map(function ($u) {
            $h = $u->hafalan;
            $juz    = $h->current_juz ?? 0;
            $target = $h->target_juz ?? 30;
            $lastTasmi  = $h?->last_tasmi_at;
            $daysSince  = $lastTasmi ? $lastTasmi->diffInDays(now()) : null;

            $status = match (true) {
                $daysSince === null || $daysSince > 14 => 'perlu_perhatian',
                $juz >= $target                        => 'baik',
                default                                 => 'on_track',
            };

            return [
                'id'           => $u->id,
                'name'         => $u->name,
                'asrama'       => $u->asrama ?? '-',
                'juz'          => $juz,
                'target_juz'   => $target,
                'last_setoran' => $lastTasmi?->format('Y-m-d') ?? '-',
                'mentor'       => $u->mentor->name ?? '-',
                'status'       => $status,
            ];
        });

        $juzDist = collect(range(1, 30))->map(fn($j) => [
            'juz'   => "Juz $j",
            'count' => $data->filter(fn($d) => (int)$d['juz'] === $j)->count(),
        ])->filter(fn($d) => $d['count'] > 0)->values();

        return Inertia::render('Super/Hafalan', [
            'hafalan'   => $data->values(),
            'juzDist'   => $juzDist,
            'stats' => [
                'avg_juz'        => $data->count() ? round($data->avg('juz'), 1) : 0,
                'hafidz'         => $data->where('juz', '>=', 30)->count(),
                'perlu_perhatian'=> $data->where('status', 'perlu_perhatian')->count(),
                'total'          => $data->count(),
            ],
        ]);
    }

    // ── Leaderboard ───────────────────────────────────────────
    public function leaderboard(Request $request)
    {
        $from = $request->query('from') ?: now()->startOfMonth()->toDateString();
        $to   = $request->query('to')   ?: now()->endOfMonth()->toDateString();

        $students   = \App\Models\User::where('role', 'mahasiswa')->get();
        $studentIds = $students->pluck('id');

        // Total POIN (dari Komponen Penilaian: Jenis Kegiatan x Level yang dipilih),
        // bukan lagi jumlah aktivitas mentah.
        $countByUser = function (string $model) use ($studentIds, $from, $to) {
            return $model::whereIn('user_id', $studentIds)
                ->whereBetween('waktu', [$from, $to])
                ->selectRaw('user_id, SUM(poin) as cnt')
                ->groupBy('user_id')
                ->pluck('cnt', 'user_id');
        };

        $akademikCounts   = $countByUser(\App\Models\Akademik::class);
        $leadershipCounts = $countByUser(\App\Models\Leadership::class);
        $karakterCounts   = $countByUser(\App\Models\Karakter::class);
        $kreatifCounts    = $countByUser(\App\Models\Kreatif::class);

        $hafalanCounts = \App\Models\HafalanLog::whereIn('user_id', $studentIds)
            ->where('score', 'memtas')
            ->whereBetween('tested_at', [$from, $to])
            ->selectRaw('user_id, COUNT(*) as cnt')
            ->groupBy('user_id')
            ->pluck('cnt', 'user_id');

        $shalatEvents = \App\Models\UserEvent::whereIn('user_id', $studentIds)
            ->where('type', 'shalat')
            ->get()
            ->groupBy('user_id');

        $entries = $students->map(function ($u) use ($akademikCounts, $leadershipCounts, $karakterCounts, $kreatifCounts, $hafalanCounts, $shalatEvents, $from, $to) {
            $akademik   = (int) ($akademikCounts[$u->id] ?? 0);
            $leadership = (int) ($leadershipCounts[$u->id] ?? 0);
            $karakter   = (int) ($karakterCounts[$u->id] ?? 0);
            $kreatif    = (int) ($kreatifCounts[$u->id] ?? 0);
            $total      = $akademik + $leadership + $karakter + $kreatif;

            $shalat = ($shalatEvents[$u->id] ?? collect())->sum(function ($e) use ($from, $to) {
                return collect($e->completed_at_dates ?? [])
                    ->filter(fn ($d) => $d >= $from && $d <= $to)
                    ->count();
            });

            return [
                'id'         => $u->id,
                'name'       => $u->name,
                'asrama'     => $u->asrama ?? '-',
                'points'     => $total,
                'shalat'     => $shalat,
                'hafalan'    => (int) ($hafalanCounts[$u->id] ?? 0),
                'akademik'   => $akademik,
                'leadership' => $leadership,
                'karakter'   => $karakter,
                'kreatif'    => $kreatif,
                'total'      => $total,
                'target'     => self::MONTHLY_ACTIVITY_TARGET,
                'terpenuhi'  => $total >= self::MONTHLY_ACTIVITY_TARGET,
            ];
        })
            ->sortByDesc('points')
            ->values()
            ->map(function ($e, $i) {
                $rank = $i + 1;
                $e['rank']  = $rank;
                $e['badge'] = $rank === 1 ? '🥇' : ($rank === 2 ? '🥈' : ($rank === 3 ? '🥉' : null));
                return $e;
            });

        $asramaNames = \App\Models\Asrama::orderBy('nama_asrama')->pluck('nama_asrama');

        $topAsrama = $entries->groupBy('asrama')
            ->map(fn ($g, $nama) => ['asrama' => $nama, 'avg' => $g->avg('points')])
            ->sortByDesc('avg')
            ->first();

        return Inertia::render('Super/Leaderboard', [
            'entries' => $entries,
            'asramas' => $asramaNames,
            'stats'   => [
                'top_asrama' => $topAsrama['asrama'] ?? '-',
                'avg_points' => $entries->isNotEmpty() ? round($entries->avg('points')) : 0,
                'total'      => $entries->count(),
            ],
            'filters' => [
                'from' => $from,
                'to'   => $to,
            ],
            'monthlyTarget' => self::MONTHLY_ACTIVITY_TARGET,
        ]);
    }

    // ── Laporan ───────────────────────────────────────────────
    public function laporan(Request $request)
    {
        $months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];

        $trendData = collect(range(11, 0))->map(function ($i) use ($months) {
            $month = now()->subMonths($i);
            $start = $month->copy()->startOfMonth();
            $end   = $month->copy()->endOfMonth();

            $shalat = (int) UserEvent::where('type', 'shalat')->whereBetween('created_at', [$start, $end])
                ->get()->sum(fn ($e) => count($e->completed_at_dates ?? []));
            $kegiatan = (int) UserEvent::where('type', 'kegiatan')->whereBetween('created_at', [$start, $end])
                ->get()->sum(fn ($e) => count($e->completed_at_dates ?? []));
            $hafalan  = \App\Models\HafalanLog::where('score', 'memtas')->whereBetween('created_at', [$start, $end])->count();
            $akademik = \App\Models\Akademik::whereBetween('created_at', [$start, $end])->count();

            return [
                'bulan'    => $months[$month->month - 1],
                'shalat'   => $shalat,
                'hafalan'  => $hafalan,
                'akademik' => $akademik,
                'kegiatan' => $kegiatan,
            ];
        });

        $asramaPerf = \App\Models\Asrama::orderBy('nama_asrama')->get()->map(function ($a) {
            $warga = User::where('role', 'mahasiswa')->where('asrama', $a->nama_asrama)->get();
            return [
                'asrama' => $a->nama_asrama,
                'avg'    => $warga->isEmpty() ? 0 : round($warga->avg(fn ($u) => $u->calculatePoints())),
                'warga'  => $warga->count(),
            ];
        })->values()->toArray();

        $totalWarga = User::where('role', 'mahasiswa')->count();
        $avgPoints  = $totalWarga
            ? round(User::where('role', 'mahasiswa')->get()->avg(fn ($u) => $u->calculatePoints()))
            : 0;

        // ── Rekap Aktivitas Warga ───────────────────────────────
        $rekapFrom   = $request->query('rekap_from') ?: now()->startOfMonth()->toDateString();
        $rekapTo     = $request->query('rekap_to')   ?: now()->endOfMonth()->toDateString();
        $rekapAsrama = $request->query('rekap_asrama');
        $rekapPage   = (int) $request->query('rekap_page', 1);
        $rekapPerPage = min((int) $request->query('rekap_per_page', 10), 100);

        $rekapQuery = User::where('role', 'mahasiswa');
        if ($rekapAsrama) {
            $rekapQuery->where('asrama', $rekapAsrama);
        }
        $rekapUsers = $rekapQuery->select(['id', 'name', 'asrama'])->get();
        $rekapUserIds = $rekapUsers->pluck('id');

        // Total POIN (Komponen Penilaian: Jenis Kegiatan x Level), bukan jumlah aktivitas mentah.
        $countByUser = function (string $model) use ($rekapUserIds, $rekapFrom, $rekapTo) {
            return $model::whereIn('user_id', $rekapUserIds)
                ->whereBetween('waktu', [$rekapFrom, $rekapTo])
                ->selectRaw('user_id, SUM(poin) as cnt')
                ->groupBy('user_id')
                ->pluck('cnt', 'user_id');
        };

        $akademikCounts   = $countByUser(\App\Models\Akademik::class);
        $leadershipCounts = $countByUser(\App\Models\Leadership::class);
        $karakterCounts   = $countByUser(\App\Models\Karakter::class);
        $kreatifCounts    = $countByUser(\App\Models\Kreatif::class);

        $rekapAll = $rekapUsers->map(function ($u) use ($akademikCounts, $leadershipCounts, $karakterCounts, $kreatifCounts) {
            $akademik   = (int) ($akademikCounts[$u->id] ?? 0);
            $leadership = (int) ($leadershipCounts[$u->id] ?? 0);
            $karakter   = (int) ($karakterCounts[$u->id] ?? 0);
            $kreatif    = (int) ($kreatifCounts[$u->id] ?? 0);

            $total = $akademik + $leadership + $karakter + $kreatif;

            return [
                'id'         => $u->id,
                'name'       => $u->name,
                'asrama'     => $u->asrama ?? '-',
                'akademik'   => $akademik,
                'leadership' => $leadership,
                'karakter'   => $karakter,
                'kreatif'    => $kreatif,
                'total'      => $total,
                'target'     => self::MONTHLY_ACTIVITY_TARGET,
                'terpenuhi'  => $total >= self::MONTHLY_ACTIVITY_TARGET,
            ];
        })->sortByDesc('total')->values();

        $rekapTotal = $rekapAll->count();
        $rekapItems = $rekapAll->slice(($rekapPage - 1) * $rekapPerPage, $rekapPerPage)->values();

        // ── Statistik Penerimaan Beasiswa Per Bulan (12 bulan terakhir) ──
        $beasiswaTrend = collect(range(11, 0))->map(function ($i) use ($months) {
            $month = now()->subMonths($i);
            $count = \App\Models\Beasiswa::where('status', 'approved')
                ->whereYear('tanggal_diterima', $month->year)
                ->whereMonth('tanggal_diterima', $month->month)
                ->count();
            $nominal = \App\Models\Beasiswa::where('status', 'approved')
                ->whereYear('tanggal_diterima', $month->year)
                ->whereMonth('tanggal_diterima', $month->month)
                ->sum('nominal');

            return [
                'bulan'   => $months[$month->month - 1] . ' ' . $month->format('y'),
                'jumlah'  => $count,
                'nominal' => (float) $nominal,
            ];
        })->values();

        $beasiswaRecent = \App\Models\Beasiswa::with('user:id,name,asrama')
            ->latest('tanggal_diajukan')
            ->take(15)
            ->get()
            ->map(fn ($b) => [
                'id'               => $b->id,
                'nama_warga'       => $b->user->name ?? '-',
                'asrama'           => $b->user->asrama ?? '-',
                'nama_beasiswa'    => $b->nama_beasiswa,
                'sumber'           => $b->sumber,
                'nominal'          => (float) $b->nominal,
                'tanggal_diajukan' => $b->tanggal_diajukan->format('Y-m-d'),
                'tanggal_diterima' => $b->tanggal_diterima?->format('Y-m-d'),
                'status'           => $b->status,
            ]);

        return Inertia::render('Super/Laporan', [
            'trend'       => $trendData->values(),
            'asramaPerf'  => $asramaPerf,
            'stats' => [
                'total_warga'    => $totalWarga,
                'avg_skor'       => $avgPoints,
                'total_kegiatan' => \App\Models\Kegiatan::count(),
                'persen_aktif'   => $totalWarga ? round(User::where('role', 'mahasiswa')->where('status_warga', 'aktif')->count() / $totalWarga * 100) : 0,
            ],
            'rekap' => [
                'items'    => $rekapItems,
                'total'    => $rekapTotal,
                'page'     => $rekapPage,
                'per_page' => $rekapPerPage,
                'last_page'=> (int) max(1, ceil($rekapTotal / $rekapPerPage)),
            ],
            'rekapFilters' => [
                'asrama' => $rekapAsrama,
                'from'   => $rekapFrom,
                'to'     => $rekapTo,
            ],
            'asramas' => \App\Models\Asrama::orderBy('nama_asrama')->pluck('nama_asrama'),
            'monthlyTarget' => self::MONTHLY_ACTIVITY_TARGET,
            'beasiswa' => [
                'trend'         => $beasiswaTrend,
                'recent'        => $beasiswaRecent,
                'wargaOptions'  => User::where('role', 'mahasiswa')->orderBy('name')->get(['id', 'name']),
            ],
        ]);
    }

    // ── Beasiswa ──────────────────────────────────────────────
    public function storeBeasiswa(Request $request)
    {
        $data = $request->validate([
            'user_id'          => 'required|exists:users,id',
            'nama_beasiswa'    => 'required|string|max:255',
            'sumber'           => 'required|string|in:yayasan,eksternal',
            'nominal'          => 'nullable|numeric|min:0',
            'tanggal_diajukan' => 'required|date',
            'tanggal_diterima' => 'nullable|date',
        ]);

        $isYayasan = $data['sumber'] === 'yayasan';
        $mentee = User::findOrFail($data['user_id']);

        \App\Models\Beasiswa::create([
            'user_id'          => $data['user_id'],
            'mentor_id'        => $isYayasan ? $mentee->mentor_id : null,
            'created_by'       => $request->user()->id,
            'nama_beasiswa'    => $data['nama_beasiswa'],
            'sumber'           => $data['sumber'],
            'nominal'          => $data['nominal'] ?? null,
            'tanggal_diajukan' => $data['tanggal_diajukan'],
            'tanggal_diterima' => $isYayasan ? null : ($data['tanggal_diterima'] ?? $data['tanggal_diajukan']),
            'status'           => $isYayasan ? 'pending' : 'approved',
        ]);

        return back()->with('success', $isYayasan
            ? 'Beasiswa Yayasan diajukan, menunggu persetujuan mentor.'
            : 'Data beasiswa berhasil disimpan.');
    }

    public function destroyBeasiswa(\App\Models\Beasiswa $beasiswa)
    {
        $beasiswa->delete();

        return back()->with('success', 'Data beasiswa berhasil dihapus.');
    }

    // ── Data Master ───────────────────────────────────────────
    public function dataMaster(Request $request)
    {
        $query = User::whereIn('role', ['mahasiswa', 'alumni']);

        if ($request->role) {
            $query->where('role', $request->role);
        }

        if ($request->search) {
            $s = $request->search;
            $query->where(fn ($q) => $q->where('name', 'like', "%{$s}%")->orWhere('no_induk', 'like', "%{$s}%"));
        }

        if ($request->provinsi) {
            $query->where('provinsi', $request->provinsi);
        }

        if ($request->kota) {
            $query->where('kota', $request->kota);
        }

        if ($request->universitas) {
            $query->where('universitas', $request->universitas);
        }

        if ($request->prodi) {
            $query->where('prodi', $request->prodi);
        }

        $perPage = min((int) ($request->per_page ?? 15), 100);
        $data = $query->select([
                'id', 'name', 'role', 'no_induk', 'asrama', 'angkatan',
                'provinsi', 'kota', 'universitas', 'fakultas', 'prodi', 'avatar',
            ])
            ->orderBy('name')
            ->paginate($perPage)
            ->withQueryString();

        $base = User::whereIn('role', ['mahasiswa', 'alumni']);

        return Inertia::render('Super/DataMaster', [
            'data'    => $data,
            'filters' => $request->only(['role', 'search', 'provinsi', 'kota', 'universitas', 'prodi', 'per_page']),
            'options' => [
                'provinsi'    => (clone $base)->whereNotNull('provinsi')->distinct()->orderBy('provinsi')->pluck('provinsi'),
                'kota'        => (clone $base)->whereNotNull('kota')->distinct()->orderBy('kota')->pluck('kota'),
                'universitas' => (clone $base)->whereNotNull('universitas')->distinct()->orderBy('universitas')->pluck('universitas'),
                'prodi'       => (clone $base)->whereNotNull('prodi')->distinct()->orderBy('prodi')->pluck('prodi'),
            ],
            'stats' => [
                'total'     => (clone $base)->count(),
                'mahasiswa' => (clone $base)->where('role', 'mahasiswa')->count(),
                'alumni'    => (clone $base)->where('role', 'alumni')->count(),
            ],
        ]);
    }

    // ── Pengaturan ────────────────────────────────────────────
    public function pengaturan()
    {
        $s = \App\Models\AppSetting::allValues();

        return Inertia::render('Super/Pengaturan', [
            'asramas'           => \App\Models\Asrama::with('jabatans')->orderBy('nama_asrama')->get(),
            'pointRules'        => \App\Models\PointRule::orderBy('id')->get(),
            'dailyTargets'      => \App\Models\DailyTarget::orderBy('id')->get(),
            'activityTypes'     => \App\Models\PointRule::TYPES,
            'komponens'         => \App\Models\Komponen::orderBy('aspek')->orderBy('kode')->get(),
            'aspekList'         => \App\Models\Komponen::ASPEK,
            // Komponen Penilaian baru (3-level)
            'komponenPenilaian' => \App\Models\KomponenPenilaianAspek::with(['subAspeks.jenisKegiatans'])
                                        ->orderBy('urutan')->get(),
            'settings'          => [
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

    // ── Komponen CRUD ─────────────────────────────────────────
    public function storeKomponen(Request $request)
    {
        $data = $request->validate([
            'kode'          => 'required|string|max:20',
            'nama_komponen' => 'required|string|max:255',
            'aspek'         => 'required|string|in:' . implode(',', \App\Models\Komponen::ASPEK),
            'bobot'         => 'required|integer|min:0',
        ]);
        \App\Models\Komponen::create($data);
        return back()->with('success', 'Komponen berhasil ditambahkan.');
    }

    public function updateKomponen(Request $request, \App\Models\Komponen $komponen)
    {
        $data = $request->validate([
            'kode'          => 'required|string|max:20',
            'nama_komponen' => 'required|string|max:255',
            'aspek'         => 'required|string|in:' . implode(',', \App\Models\Komponen::ASPEK),
            'bobot'         => 'required|integer|min:0',
        ]);
        $komponen->update($data);
        return back()->with('success', 'Komponen berhasil diperbarui.');
    }

    public function destroyKomponen(\App\Models\Komponen $komponen)
    {
        try {
            $komponen->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            return back()->with('error', 'Komponen tidak bisa dihapus karena masih dipakai di data penilaian.');
        }
        return back()->with('success', 'Komponen dihapus.');
    }

    // ── Komponen Penilaian — Sub-Aspek CRUD ──────────────────
    public function storeSubAspek(Request $request)
    {
        $data = $request->validate([
            'aspek_id'       => 'required|exists:komponen_penilaian_aspek,id',
            'nama_sub_aspek' => 'required|string|max:150',
            'urutan'         => 'nullable|integer|min:0',
        ]);
        $data['urutan'] = $data['urutan'] ?? 0;
        \App\Models\KomponenPenilaianSubAspek::create($data);
        return back()->with('success', 'Sub-Aspek berhasil ditambahkan.');
    }

    public function updateSubAspek(Request $request, \App\Models\KomponenPenilaianSubAspek $subAspek)
    {
        $data = $request->validate([
            'nama_sub_aspek' => 'required|string|max:150',
            'urutan'         => 'nullable|integer|min:0',
        ]);
        $subAspek->update($data);
        return back()->with('success', 'Sub-Aspek berhasil diperbarui.');
    }

    public function destroySubAspek(\App\Models\KomponenPenilaianSubAspek $subAspek)
    {
        $subAspek->delete(); // jenis ikut terhapus (cascadeOnDelete)
        return back()->with('success', 'Sub-Aspek dihapus.');
    }

    // ── Komponen Penilaian — Jenis Kegiatan CRUD ─────────────
    private function jenisRules(): array
    {
        return [
            'nama_kegiatan'   => 'required|string|max:200',
            'urutan'          => 'nullable|integer|min:0',
            'poin_a'          => 'nullable|integer|min:0|max:20',
            'poin_p'          => 'nullable|integer|min:0|max:20',
            'poin_f'          => 'nullable|integer|min:0|max:20',
            'poin_u'          => 'nullable|integer|min:0|max:20',
            'poin_w'          => 'nullable|integer|min:0|max:20',
            'poin_n'          => 'nullable|integer|min:0|max:20',
            'poin_i'          => 'nullable|integer|min:0|max:20',
            'keterangan_bukti'=> 'nullable|string|max:500',
        ];
    }

    public function storeJenis(Request $request, \App\Models\KomponenPenilaianSubAspek $subAspek)
    {
        $data = $request->validate($this->jenisRules());
        $data['sub_aspek_id'] = $subAspek->id;
        $data['urutan'] = $data['urutan'] ?? 0;
        \App\Models\KomponenPenilaianJenis::create($data);
        return back()->with('success', 'Jenis kegiatan berhasil ditambahkan.');
    }

    public function updateJenis(Request $request, \App\Models\KomponenPenilaianJenis $jenis)
    {
        $data = $request->validate($this->jenisRules());
        $jenis->update($data);
        return back()->with('success', 'Jenis kegiatan berhasil diperbarui.');
    }

    public function destroyJenis(\App\Models\KomponenPenilaianJenis $jenis)
    {
        $jenis->delete();
        return back()->with('success', 'Jenis kegiatan dihapus.');
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
