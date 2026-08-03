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
            'jenis_kegiatan'=> 'required|string|in:akademik,hafalan,kegiatan,olahraga,sosial,lainnya',
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
            'jenis_kegiatan'=> 'required|string|in:akademik,hafalan,kegiatan,olahraga,sosial,lainnya',
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

        $wargaAsrama = \App\Models\User::where('role', 'mahasiswa')->where('asrama', $this->asrama())
            ->orderBy('name')->get(['id', 'name']);

        return Inertia::render('PengurusAsrama/KegiatanAttendance', [
            'kegiatan' => $kegiatan,
            'items'    => $items,
            'warga'    => $wargaAsrama,
        ]);
    }

    public function storeKegiatanAttendance(Request $request, Kegiatan $kegiatan)
    {
        abort_if($kegiatan->asrama !== $this->asrama(), 403);

        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $mahasiswa = \App\Models\User::findOrFail($data['user_id']);

        \App\Models\KegiatanAttendance::firstOrCreate(
            ['kegiatan_id' => $kegiatan->id, 'user_id' => $mahasiswa->id],
            ['asrama' => $mahasiswa->asrama, 'waktu_absen' => now(), 'dicatat_oleh' => $request->user()->id]
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
