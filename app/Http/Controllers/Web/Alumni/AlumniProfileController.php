<?php

namespace App\Http\Controllers\Web\Alumni;

use App\Http\Controllers\Controller;
use App\Models\Alumni;
use App\Models\AlumniPost;
use App\Models\AlumniBusiness;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AlumniProfileController extends Controller
{
    public function index(Request $request)
    {
        $user    = $request->user();
        $profile = Alumni::where('email', $user->email)->first();
        $posts   = AlumniPost::where('user_id', $user->id)
            ->latest()->limit(5)
            ->get()->map(fn($p) => [
                'id'       => $p->id,
                'type'     => $p->type,
                'title'    => $p->title,
                'content'  => substr($p->content, 0, 120) . (strlen($p->content) > 120 ? '...' : ''),
                'likes_count'    => $p->likes_count,
                'comments_count' => $p->comments_count,
                'created_at'     => $p->created_at->diffForHumans(),
            ]);

        $business = AlumniBusiness::where('alumni_id', $user->id)->first();

        return Inertia::render('Alumni/Profil', [
            'profile'  => $profile,
            'posts'    => $posts,
            'business' => $business,
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();
        $validated = $request->validate([
            'nama'                   => 'nullable|string|max:255',
            'no_whatsapp'            => 'nullable|string|max:20',
            'alamat_domisili'        => 'nullable|string|max:255',
            'pendidikan_terakhir'    => 'nullable|string|max:100',
            'kampus_s1'              => 'nullable|string|max:200',
            'jurusan_s1'             => 'nullable|string|max:200',
            'pekerjaan_sekarang'     => 'nullable|string|max:200',
            'bidang_pekerjaan'       => 'nullable|string|max:200',
            'bidang_keahlian'        => 'nullable|string|max:500',
            'asal_asrama'            => 'nullable|string|max:100',
            'tahun_masuk_asrama'     => 'nullable|string|max:10',
            'tahun_keluar_asrama'    => 'nullable|string|max:10',
        ]);

        Alumni::updateOrCreate(
            ['email' => $user->email],
            array_merge($validated, ['nama' => $validated['nama'] ?? $user->name])
        );

        return back()->with('success', 'Profil berhasil diperbarui!');
    }
}
