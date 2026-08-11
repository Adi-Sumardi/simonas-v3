<?php

namespace App\Http\Controllers\Web\PengurusAsrama;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use App\Models\ProgramKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PengurusAsramaController extends Controller
{
    private function asrama(): string
    {
        return Auth::user()->asrama ?? '';
    }

    // ── Kegiatan Asrama ────────────────────────────────────────

    public function kegiatanIndex(Request $request)
    {
        $asrama  = $this->asrama();
        $perPage = min((int)($request->per_page ?? 10), 100);
        $query   = Kegiatan::where('asrama', $asrama);

        if ($request->search) {
            $q = $request->search;
            $query->where(fn($sub) => $sub
                ->where('nama_kegiatan', 'like', "%{$q}%")
                ->orWhere('keterangan', 'like', "%{$q}%")
            );
        }

        if ($request->status === 'upcoming') {
            $query->where('waktu', '>=', now());
        } elseif ($request->status === 'selesai') {
            $query->where('waktu', '<', now());
        }

        $kegiatan = $query->orderBy('waktu', 'desc')->paginate($perPage)->withQueryString();

        return Inertia::render('PengurusAsrama/KegiatanAsrama', [
            'kegiatan' => $kegiatan,
            'asrama'   => $asrama,
            'stats'    => [
                'total'    => Kegiatan::where('asrama', $asrama)->count(),
                'upcoming' => Kegiatan::where('asrama', $asrama)->where('waktu', '>=', now())->count(),
                'selesai'  => Kegiatan::where('asrama', $asrama)->where('waktu', '<', now())->count(),
            ],
            'filters'  => $request->only(['search', 'status', 'per_page']),
        ]);
    }

    public function kegiatanStore(Request $request)
    {
        $asrama = $this->asrama();
        abort_if(!$asrama, 403, 'Anda belum terdaftar di asrama manapun.');

        $data = $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'tujuan'        => 'required|string|max:500',
            'jenis_kegiatan'=> 'required|string|in:akademik,hafalan,ibadah,kegiatan,olahraga,sosial,lainnya',
            'wajib_absen'   => 'nullable|boolean',
            'waktu'         => 'required|date',
            'tempat'        => 'required|string|max:255',
            'keterangan'    => 'nullable|string|max:1000',
        ]);

        $data['penyelenggara'] = "Pengurus {$asrama}";
        $data['asrama']        = $asrama;
        $data['wajib_absen']   = $request->boolean('wajib_absen');

        Kegiatan::create($data);

        return back()->with('success', 'Kegiatan berhasil ditambahkan.');
    }

    public function kegiatanUpdate(Request $request, Kegiatan $kegiatan)
    {
        abort_if($kegiatan->asrama !== $this->asrama(), 403);

        $data = $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'tujuan'        => 'required|string|max:500',
            'jenis_kegiatan'=> 'required|string|in:akademik,hafalan,ibadah,kegiatan,olahraga,sosial,lainnya',
            'wajib_absen'   => 'nullable|boolean',
            'waktu'         => 'required|date',
            'tempat'        => 'required|string|max:255',
            'keterangan'    => 'nullable|string|max:1000',
        ]);
        $data['wajib_absen'] = $request->boolean('wajib_absen');

        $kegiatan->update($data);

        return back()->with('success', 'Kegiatan berhasil diperbarui.');
    }

    public function kegiatanDestroy(Kegiatan $kegiatan)
    {
        abort_if($kegiatan->asrama !== $this->asrama(), 403);

        $kegiatan->delete();

        return back()->with('success', 'Kegiatan berhasil dihapus.');
    }

    public function kegiatanAttendance(Kegiatan $kegiatan)
    {
        abort_if($kegiatan->asrama !== $this->asrama(), 403);

        $isPutri = $this->asrama() === 'Asrama Putri';

        $items = $kegiatan->attendances()
            ->with('user:id,name,avatar,asrama')
            ->orderByDesc('waktu_absen')
            ->get()
            ->map(fn (\App\Models\KegiatanAttendance $a) => [
                'id'          => $a->id,
                'user'        => $a->user->only(['id', 'name', 'avatar', 'asrama']),
                'asrama'      => $a->asrama,
                'status'      => $a->status,
                'keterangan'  => $a->keterangan,
                'waktu_absen' => $a->waktu_absen->format('Y-m-d H:i'),
                'latitude'    => $a->latitude,
                'longitude'   => $a->longitude,
                'alamat'      => $a->alamat,
                'selfie_url'  => $a->file_selfie_data ? route('files.show', ['table' => 'kegiatan_attendances', 'id' => $a->id, 'slot' => 'selfie']) : null,
                'lokasi_url'  => $a->file_lokasi_data ? route('files.show', ['table' => 'kegiatan_attendances', 'id' => $a->id, 'slot' => 'lokasi']) : null,
            ]);

        $wargaAsrama = \App\Models\User::where('role', 'mahasiswa')->where('asrama', $this->asrama())
            ->orderBy('name')->get(['id', 'name', 'avatar']);

        $attendanceByUser = $kegiatan->attendances()->get()->keyBy('user_id');

        // Rekap lengkap: seluruh warga asrama, termasuk yang belum dicatat sama sekali (status = null).
        $roster = $wargaAsrama->map(function ($w) use ($attendanceByUser) {
            $a = $attendanceByUser->get($w->id);
            return [
                'user_id'        => $w->id,
                'name'           => $w->name,
                'avatar'         => $w->avatar,
                'attendance_id'  => $a?->id,
                'status'         => $a?->status,
                'keterangan'     => $a?->keterangan,
                'poin_deduction' => $a?->poin_deduction ?? 0,
                'waktu_absen'    => $a?->waktu_absen?->format('Y-m-d H:i'),
            ];
        });

        return Inertia::render('PengurusAsrama/KegiatanAttendance', [
            'kegiatan' => $kegiatan,
            'items'    => $items,
            'roster'   => $roster,
            'warga'    => $wargaAsrama,
            'isPutri'  => $isPutri,
        ]);
    }

    public function storeKegiatanAttendance(Request $request, Kegiatan $kegiatan)
    {
        abort_if($kegiatan->asrama !== $this->asrama(), 403);

        $isPutri = $this->asrama() === 'Asrama Putri';
        $statusOptions = $isPutri
            ? ['hadir', 'izin', 'sakit', 'alpa', 'haid']
            : ['hadir', 'izin', 'sakit', 'alpa'];

        $data = $request->validate([
            'user_id'    => 'required|exists:users,id',
            'status'     => ['nullable', 'string', 'in:' . implode(',', $statusOptions)],
            'keterangan' => 'nullable|string|max:255',
        ]);

        $mahasiswa = \App\Models\User::findOrFail($data['user_id']);
        $status    = $data['status'] ?? 'hadir';

        // Potongan poin hanya berlaku untuk alpa pada kegiatan wajib_absen yang sudah selesai berlangsung.
        $poinDeduction = 0;
        if ($status === 'alpa' && $kegiatan->wajib_absen && $kegiatan->sudah_selesai) {
            $rule = \App\Models\PointRule::where('activity_type', 'kegiatan')->where('is_active', true)->first();
            $poinDeduction = $rule->poin ?? 20;
        }

        \App\Models\KegiatanAttendance::updateOrCreate(
            ['kegiatan_id' => $kegiatan->id, 'user_id' => $mahasiswa->id],
            [
                'asrama'         => $mahasiswa->asrama,
                'status'         => $status,
                'keterangan'     => $data['keterangan'] ?? null,
                'poin_deduction' => $poinDeduction,
                'waktu_absen'    => now(),
                'dicatat_oleh'   => $request->user()->id,
            ]
        );

        return back()->with('success', 'Kehadiran berhasil dicatat.');
    }

    public function destroyKegiatanAttendance(Kegiatan $kegiatan, \App\Models\KegiatanAttendance $attendance)
    {
        abort_if($kegiatan->asrama !== $this->asrama(), 403);
        abort_if($attendance->kegiatan_id !== $kegiatan->id, 404);

        $attendance->delete();

        return back()->with('success', 'Data kehadiran berhasil dihapus.');
    }

    // ── Warga Asrama ───────────────────────────────────────────

    public function wargaIndex(Request $request)
    {
        $asrama  = $this->asrama();
        $perPage = min((int)($request->per_page ?? 10), 100);

        $query = \App\Models\User::where('role', 'mahasiswa')->where('asrama', $asrama);

        if ($request->search) {
            $q = $request->search;
            $query->where(fn ($sub) => $sub
                ->where('name', 'like', "%{$q}%")
                ->orWhere('no_induk', 'like', "%{$q}%")
            );
        }

        if ($request->status) {
            $query->where('status_warga', $request->status);
        }

        $warga = $query->orderBy('name')
            ->select(['id', 'name', 'email', 'no_induk', 'asrama', 'status_warga', 'avatar', 'angkatan'])
            ->paginate($perPage)->withQueryString();

        return Inertia::render('PengurusAsrama/Warga', [
            'warga'   => $warga,
            'asrama'  => $asrama,
            'stats'   => [
                'total'    => \App\Models\User::where('role', 'mahasiswa')->where('asrama', $asrama)->count(),
                'aktif'    => \App\Models\User::where('role', 'mahasiswa')->where('asrama', $asrama)->where('status_warga', 'aktif')->count(),
                'nonaktif' => \App\Models\User::where('role', 'mahasiswa')->where('asrama', $asrama)->where('status_warga', 'nonaktif')->count(),
            ],
            'filters' => $request->only(['search', 'status', 'per_page']),
        ]);
    }

    public function wargaUpdateStatus(Request $request, \App\Models\User $warga)
    {
        abort_if($warga->role !== 'mahasiswa' || $warga->asrama !== $this->asrama(), 403);

        $data = $request->validate([
            'status_warga' => 'required|string|in:aktif,nonaktif',
        ]);

        $warga->update($data);

        return back()->with('success', 'Status warga berhasil diperbarui.');
    }

    // ── Program Kerja ──────────────────────────────────────────

    public function programKerjaIndex(Request $request)
    {
        $asrama  = $this->asrama();
        $perPage = min((int)($request->per_page ?? 10), 100);

        $query = ProgramKerja::where('asrama', $asrama);

        if ($request->tahun) {
            $query->where('tahun', $request->tahun);
        }
        if ($request->semester) {
            $query->where('semester', $request->semester);
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->search) {
            $q = $request->search;
            $query->where(fn($sub) => $sub
                ->where('nama_program', 'like', "%{$q}%")
                ->orWhere('deskripsi', 'like', "%{$q}%")
                ->orWhere('penanggung_jawab', 'like', "%{$q}%")
            );
        }

        $programs = $query->orderBy('tahun', 'desc')
            ->orderBy('semester', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        $stats = [
            'total'     => ProgramKerja::where('asrama', $asrama)->count(),
            'rencana'   => ProgramKerja::where('asrama', $asrama)->where('status', 'rencana')->count(),
            'berjalan'  => ProgramKerja::where('asrama', $asrama)->where('status', 'berjalan')->count(),
            'selesai'   => ProgramKerja::where('asrama', $asrama)->where('status', 'selesai')->count(),
        ];

        $tahunList = ProgramKerja::where('asrama', $asrama)
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        return Inertia::render('PengurusAsrama/ProgramKerja', [
            'programs'  => $programs,
            'asrama'    => $asrama,
            'stats'     => $stats,
            'tahunList' => $tahunList,
            'filters'   => $request->only(['search', 'tahun', 'semester', 'status', 'per_page']),
        ]);
    }

    public function programKerjaStore(Request $request)
    {
        $asrama = $this->asrama();
        abort_if(!$asrama, 403, 'Anda belum terdaftar di asrama manapun.');

        $data = $request->validate([
            'nama_program'     => 'required|string|max:255',
            'deskripsi'        => 'nullable|string|max:2000',
            'tahun'            => 'required|integer|min:2000|max:2100',
            'semester'         => 'required|integer|in:1,2',
            'status'           => 'required|in:rencana,berjalan,selesai,dibatalkan',
            'tanggal_mulai'    => 'nullable|date',
            'tanggal_selesai'  => 'nullable|date|after_or_equal:tanggal_mulai',
            'penanggung_jawab' => 'nullable|string|max:255',
        ]);

        $data['asrama']     = $asrama;
        $data['created_by'] = Auth::id();

        ProgramKerja::create($data);

        return back()->with('success', 'Program kerja berhasil ditambahkan.');
    }

    public function programKerjaUpdate(Request $request, ProgramKerja $programKerja)
    {
        abort_if($programKerja->asrama !== $this->asrama(), 403);

        $data = $request->validate([
            'nama_program'     => 'required|string|max:255',
            'deskripsi'        => 'nullable|string|max:2000',
            'tahun'            => 'required|integer|min:2000|max:2100',
            'semester'         => 'required|integer|in:1,2',
            'status'           => 'required|in:rencana,berjalan,selesai,dibatalkan',
            'tanggal_mulai'    => 'nullable|date',
            'tanggal_selesai'  => 'nullable|date|after_or_equal:tanggal_mulai',
            'penanggung_jawab' => 'nullable|string|max:255',
        ]);

        $programKerja->update($data);

        return back()->with('success', 'Program kerja berhasil diperbarui.');
    }

    public function programKerjaDestroy(ProgramKerja $programKerja)
    {
        abort_if($programKerja->asrama !== $this->asrama(), 403);

        $programKerja->delete();

        return back()->with('success', 'Program kerja berhasil dihapus.');
    }
}
