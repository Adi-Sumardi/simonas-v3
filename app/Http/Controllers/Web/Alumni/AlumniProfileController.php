<?php

namespace App\Http\Controllers\Web\Alumni;

use App\Http\Controllers\Controller;
use App\Models\Alumni;
use App\Models\AlumniPost;
use App\Models\AlumniBusiness;
use App\Models\ProfilRiwayat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AlumniProfileController extends Controller
{
    /**
     * Pola unified dengan Mahasiswa profile:
     * - users table: data pribadi (name, avatar, bio, no_hp, asrama, angkatan)
     * - profil_riwayats table: pendidikan/organisasi/pekerjaan/penghargaan/sertifikasi
     * - alumnis table: data alumni-specific (alamat_domisili, pekerjaan_sekarang summary,
     *   bidang_keahlian, tahun_masuk/keluar_asrama)
     *
     * Saat status user berubah mahasiswa -> alumni:
     *   tidak perlu migrasi data riwayat. Tinggal ubah role + tambah row Alumni.
     */
    public function index(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        $alumni = Alumni::firstOrCreate(
            ['email' => $user->email],
            ['nama' => $user->name],
        );

        $riwayats = ProfilRiwayat::where('user_id', $user->id)
            ->orderBy('mulai', 'desc')
            ->get()
            ->groupBy('tipe')
            ->map(fn ($items) => $items->map(fn ($r) => [
                'id'                => $r->id,
                'tipe'              => $r->tipe,
                'judul'             => $r->judul,
                'posisi'            => $r->posisi,
                'mulai'             => $r->mulai?->format('Y-m'),
                'selesai'           => $r->selesai?->format('Y-m'),
                'masih_berlangsung' => $r->masih_berlangsung,
                'deskripsi'         => $r->deskripsi,
                'lokasi'            => $r->lokasi,
            ])->values());

        $posts = AlumniPost::where('user_id', $user->id)
            ->latest()->limit(5)->get()
            ->map(fn ($p) => [
                'id'             => $p->id,
                'type'           => $p->type,
                'title'          => $p->title,
                'content'        => mb_substr($p->content, 0, 120).(mb_strlen($p->content) > 120 ? '...' : ''),
                'likes_count'    => $p->likes_count,
                'comments_count' => $p->comments_count,
                'created_at'     => $p->created_at->diffForHumans(),
            ]);

        $business = AlumniBusiness::where('alumni_id', $user->id)->first();

        // Pekerjaan sekarang dihitung dari Riwayat (single source of truth)
        $pekerjaanAktif = ProfilRiwayat::where('user_id', $user->id)
            ->where('tipe', 'pekerjaan')
            ->where('masih_berlangsung', true)
            ->orderByDesc('mulai')
            ->first();

        return Inertia::render('Alumni/Profil', [
            'user' => [
                'id'       => $user->id,
                'name'     => $user->name,
                'email'    => $user->email,
                'avatar'   => $user->avatar,
                'asrama'   => $user->asrama,
                'angkatan' => $user->angkatan,
                'bio'      => $user->bio,
                'no_hp'    => $user->no_hp,
            ],
            'alumni' => [
                'alamat_domisili'     => $alumni->alamat_domisili,
                'asal_asrama'         => $alumni->asal_asrama ?: $user->asrama,
                'tahun_masuk_asrama'  => $alumni->tahun_masuk_asrama,
                'tahun_keluar_asrama' => $alumni->tahun_keluar_asrama,
                'bidang_keahlian'     => $alumni->bidang_keahlian,
                'pekerjaan_sekarang'  => $pekerjaanAktif?->posisi ?? $alumni->pekerjaan_sekarang,
                'bidang_pekerjaan'    => $pekerjaanAktif?->judul ?? $alumni->bidang_pekerjaan,
            ],
            'riwayats' => $riwayats,
            'posts'    => $posts,
            'business' => $business,
        ]);
    }

    /**
     * Update basic info: gabung antara users (name/bio/no_hp/asrama/angkatan)
     * dan alumnis (data alumni-specific).
     */
    public function update(Request $request)
    {
        $data = $request->validate([
            // user fields
            'name'     => 'required|string|max:255',
            'bio'      => 'nullable|string|max:500',
            'no_hp'    => 'nullable|string|max:20',
            'asrama'   => 'nullable|string|max:100',
            'angkatan' => 'nullable|string|max:10',

            // alumni-specific
            'alamat_domisili'     => 'nullable|string|max:255',
            'tahun_masuk_asrama'  => 'nullable|string|max:10',
            'tahun_keluar_asrama' => 'nullable|string|max:10',
            'bidang_keahlian'     => 'nullable|string|max:500',
        ]);

        /** @var User $user */
        $user = $request->user();
        $user->update([
            'name'     => $data['name'],
            'bio'      => $data['bio']      ?? $user->bio,
            'no_hp'    => $data['no_hp']    ?? $user->no_hp,
            'asrama'   => $data['asrama']   ?? $user->asrama,
            'angkatan' => $data['angkatan'] ?? $user->angkatan,
        ]);

        Alumni::updateOrCreate(
            ['email' => $user->email],
            [
                'nama'                => $data['name'],
                'no_whatsapp'         => $data['no_hp'] ?? null,
                'alamat_domisili'     => $data['alamat_domisili']     ?? null,
                'asal_asrama'         => $data['asrama']              ?? null,
                'tahun_masuk_asrama'  => $data['tahun_masuk_asrama']  ?? null,
                'tahun_keluar_asrama' => $data['tahun_keluar_asrama'] ?? null,
                'bidang_keahlian'     => $data['bidang_keahlian']     ?? null,
            ]
        );

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|max:2048',
        ]);

        /** @var User $user */
        $user = Auth::user();

        // Delete old avatar if exists
        if ($user->avatar && str_contains($user->avatar, 'storage/')) {
            $oldPath = str_replace(asset('storage/'), '', $user->avatar);
            \Illuminate\Support\Facades\Storage::disk('public')->delete($oldPath);
        }

        $path = $request->file('avatar')->store('avatars', 'public');
        $user->update(['avatar' => asset('storage/'.$path)]);

        return back()->with('success', 'Foto profil diperbarui.');
    }

    // ── Riwayat CRUD (unified) ───────────────────────────────────

    public function storeRiwayat(Request $request)
    {
        $entries = $request->input('entries');

        if ($entries && is_array($entries)) {
            $validated = $request->validate([
                'entries'                     => 'required|array|min:1',
                'entries.*.tipe'              => 'required|in:pendidikan,organisasi,pekerjaan,penghargaan,sertifikasi',
                'entries.*.judul'             => 'required|string|max:255',
                'entries.*.posisi'            => 'nullable|string|max:255',
                'entries.*.mulai'             => 'nullable|date',
                'entries.*.selesai'           => 'nullable|date',
                'entries.*.masih_berlangsung' => 'boolean',
                'entries.*.deskripsi'         => 'nullable|string|max:1000',
                'entries.*.lokasi'            => 'nullable|string|max:255',
            ]);

            foreach ($validated['entries'] as $entry) {
                ProfilRiwayat::create(array_merge($entry, ['user_id' => Auth::id()]));
            }

            return back()->with('success', count($validated['entries']).' riwayat ditambahkan.');
        }

        $data = $request->validate([
            'tipe'              => 'required|in:pendidikan,organisasi,pekerjaan,penghargaan,sertifikasi',
            'judul'             => 'required|string|max:255',
            'posisi'            => 'nullable|string|max:255',
            'mulai'             => 'nullable|date',
            'selesai'           => 'nullable|date|after_or_equal:mulai',
            'masih_berlangsung' => 'boolean',
            'deskripsi'         => 'nullable|string|max:1000',
            'lokasi'            => 'nullable|string|max:255',
        ]);

        ProfilRiwayat::create(array_merge($data, ['user_id' => Auth::id()]));

        return back()->with('success', 'Riwayat ditambahkan.');
    }

    public function updateRiwayat(Request $request, ProfilRiwayat $riwayat)
    {
        abort_if($riwayat->user_id !== Auth::id(), 403);

        $data = $request->validate([
            'tipe'              => 'required|in:pendidikan,organisasi,pekerjaan,penghargaan,sertifikasi',
            'judul'             => 'required|string|max:255',
            'posisi'            => 'nullable|string|max:255',
            'mulai'             => 'nullable|date',
            'selesai'           => 'nullable|date',
            'masih_berlangsung' => 'boolean',
            'deskripsi'         => 'nullable|string|max:1000',
            'lokasi'            => 'nullable|string|max:255',
        ]);

        $riwayat->update($data);

        return back()->with('success', 'Riwayat diperbarui.');
    }

    public function destroyRiwayat(ProfilRiwayat $riwayat)
    {
        abort_if($riwayat->user_id !== Auth::id(), 403);
        $riwayat->delete();

        return back()->with('success', 'Riwayat dihapus.');
    }
}
