<?php

namespace App\Http\Controllers\Web\Alumni;

use App\Http\Controllers\Controller;
use App\Models\Alumni;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AlumniDatabaseController extends Controller
{
    public function index(Request $request)
    {
        $query = User::role('alumni')
            ->with(['alumni', 'profilRiwayats' => function($q) {
                $q->where('tipe', 'pekerjaan')->where('masih_berlangsung', true);
            }])
            ->when($request->search, function ($q, $search) {
                $q->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhereHas('profilRiwayats', function ($q) use ($search) {
                          $q->where('posisi', 'like', "%{$search}%")
                            ->orWhere('judul', 'like', "%{$search}%");
                      });
                });
            })
            ->when($request->asrama, function ($q, $asrama) {
                $q->where('asrama', $asrama);
            })
            ->orderBy('name');

        $alumni = $query->paginate(12)->withQueryString()
            ->through(fn ($u) => [
                'id'         => $u->id,
                'name'       => $u->name,
                'avatar'     => $u->avatar,
                'asrama'     => $u->asrama,
                'angkatan'   => $u->angkatan,
                'pekerjaan'  => $u->profilRiwayats->first()?->posisi,
                'perusahaan' => $u->profilRiwayats->first()?->judul,
                'lokasi'     => $u->alumni?->alamat_domisili ?? '-',
            ]);

        $asramas = User::role('alumni')->whereNotNull('asrama')->distinct()->pluck('asrama');

        return Inertia::render('Alumni/Database', [
            'alumni'  => $alumni,
            'asramas' => $asramas,
            'filters' => $request->only(['search', 'asrama']),
        ]);
    }

    public function details(User $user)
    {
        abort_if(!$user->hasRole('alumni'), 403);

        $user->load(['alumni', 'profilRiwayats' => fn($q) => $q->orderBy('mulai', 'desc')]);

        return response()->json([
            'id'       => $user->id,
            'name'     => $user->name,
            'avatar'   => $user->avatar,
            'bio'      => $user->bio,
            'no_hp'    => $user->no_hp,
            'asrama'   => $user->asrama,
            'angkatan' => $user->angkatan,
            'alumni'   => $user->alumni,
            'riwayats' => $user->profilRiwayats->groupBy('tipe'),
        ]);
    }
}
