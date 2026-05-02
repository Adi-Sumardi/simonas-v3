<?php

namespace App\Http\Controllers\Web\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\ProfilRiwayat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ProfileController extends Controller
{
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();

        $mentor = $user->mentor_id
            ? User::select('id', 'name', 'email', 'avatar')->find($user->mentor_id)
            : null;
            
        if ($mentor && $mentor->avatar) {
            // No need to manually format, accessor handles it
        }

        $riwayats = ProfilRiwayat::where('user_id', $user->id)
            ->orderBy('mulai', 'desc')
            ->get()
            ->groupBy('tipe')
            ->map(fn($items) => $items->map(fn($r) => [
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

        return Inertia::render('Mahasiswa/Profile', [
            'user'     => [
                'id'       => $user->id,
                'name'     => $user->name,
                'email'    => $user->email,
                'avatar'   => $user->avatar,
                'nim'      => $user->nim,
                'asrama'   => $user->asrama,
                'angkatan' => $user->angkatan,
                'bio'      => $user->bio,
                'no_hp'    => $user->no_hp,
            ],
            'mentor'   => $mentor,
            'riwayats' => $riwayats,
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'bio'      => 'nullable|string|max:500',
            'no_hp'    => 'nullable|string|max:20',
            'angkatan' => 'nullable|string|max:10',
        ]);

        Auth::user()->update($data);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    // ── Riwayat CRUD ──────────────────────────────────────────────

    public function storeRiwayat(Request $request)
    {
        // Support both single entry and array batch
        $entries = $request->input('entries');

        if ($entries && is_array($entries)) {
            // Batch insert
            $rules = [
                'entries'                      => 'required|array|min:1',
                'entries.*.tipe'               => 'required|in:pendidikan,organisasi,pekerjaan,penghargaan,sertifikasi',
                'entries.*.judul'              => 'required|string|max:255',
                'entries.*.posisi'             => 'nullable|string|max:255',
                'entries.*.mulai'              => 'nullable|date',
                'entries.*.selesai'            => 'nullable|date',
                'entries.*.masih_berlangsung'  => 'boolean',
                'entries.*.deskripsi'          => 'nullable|string|max:1000',
                'entries.*.lokasi'             => 'nullable|string|max:255',
            ];
            $validated = $request->validate($rules);

            foreach ($validated['entries'] as $entry) {
                ProfilRiwayat::create(array_merge($entry, ['user_id' => Auth::id()]));
            }

            return back()->with('success', count($validated['entries']) . ' riwayat berhasil ditambahkan.');
        }

        // Single entry fallback
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

        return back()->with('success', 'Riwayat berhasil ditambahkan.');
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

        return back()->with('success', 'Riwayat berhasil diperbarui.');
    }

    public function destroyRiwayat(ProfilRiwayat $riwayat)
    {
        abort_if($riwayat->user_id !== Auth::id(), 403);
        $riwayat->delete();

        return back()->with('success', 'Riwayat dihapus.');
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|max:2048',
        ]);

        /** @var User $user */
        $user = Auth::user();

        // Delete old avatar if exists
        if ($user->avatar && \Storage::disk('public')->exists($user->avatar)) {
            \Storage::disk('public')->delete($user->avatar);
        }

        $path = $request->file('avatar')->store('avatars', 'public');
        $user->update(['avatar' => $path]);

        return back()->with('success', 'Foto profil berhasil diperbarui.');
    }
}
