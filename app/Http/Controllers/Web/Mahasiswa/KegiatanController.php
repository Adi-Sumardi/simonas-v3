<?php

namespace App\Http\Controllers\Web\Mahasiswa;

use App\Http\Controllers\Concerns\HandlesBlobUpload;
use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use App\Models\KegiatanAttendance;
use Illuminate\Http\Request;
use Inertia\Inertia;

class KegiatanController extends Controller
{
    use HandlesBlobUpload;

    public function index(Request $request)
    {
        $user = $request->user();

        $kegiatan = Kegiatan::query()
            ->where(function ($q) use ($user) {
                $q->whereNull('asrama')->orWhere('asrama', $user->asrama);
            })
            ->orderByDesc('waktu')
            ->paginate(10)
            ->withQueryString();

        $myAttendance = KegiatanAttendance::where('user_id', $user->id)
            ->whereIn('kegiatan_id', collect($kegiatan->items())->pluck('id'))
            ->pluck('waktu_absen', 'kegiatan_id');

        $kegiatan->getCollection()->transform(function (Kegiatan $k) use ($myAttendance) {
            $arr = $k->toArray();
            $arr['sudah_absen'] = $myAttendance->has($k->id);
            $arr['waktu_absen'] = $myAttendance->get($k->id)?->format('Y-m-d H:i');
            return $arr;
        });

        return Inertia::render('Mahasiswa/Kegiatan', [
            'kegiatan' => $kegiatan,
        ]);
    }

    public function checkin(Request $request, Kegiatan $kegiatan)
    {
        abort_unless($kegiatan->wajib_absen, 422, 'Kegiatan ini tidak memerlukan absensi.');

        $request->validate([
            'file_selfie' => 'required|' . self::ALLOWED_UPLOAD_RULE,
            'file_lokasi' => 'required|' . self::ALLOWED_UPLOAD_RULE,
            'latitude'    => 'required|numeric|between:-90,90',
            'longitude'   => 'required|numeric|between:-180,180',
            'alamat'      => 'nullable|string|max:255',
        ]);

        $existing = KegiatanAttendance::where('kegiatan_id', $kegiatan->id)
            ->where('user_id', $request->user()->id)
            ->first();

        if ($existing) {
            return back()->with('error', 'Kamu sudah absen di kegiatan ini.');
        }

        $selfie = $this->processUpload($request->file('file_selfie'));
        $lokasi = $this->processUpload($request->file('file_lokasi'));

        KegiatanAttendance::create([
            'kegiatan_id'  => $kegiatan->id,
            'user_id'      => $request->user()->id,
            'asrama'       => $request->user()->asrama,
            'waktu_absen'  => now(),
            'latitude'     => $request->float('latitude'),
            'longitude'    => $request->float('longitude'),
            'alamat'       => $request->input('alamat'),
            'file_selfie'      => $selfie['name'],
            'file_selfie_data' => $this->binaryExpr($selfie['data']),
            'file_selfie_mime' => $selfie['mime'],
            'file_selfie_size' => $selfie['size'],
            'file_lokasi'      => $lokasi['name'],
            'file_lokasi_data' => $this->binaryExpr($lokasi['data']),
            'file_lokasi_mime' => $lokasi['mime'],
            'file_lokasi_size' => $lokasi['size'],
        ]);

        return back()->with('success', 'Absensi berhasil dicatat.');
    }
}
