<?php

namespace App\Http\Controllers\Web\Mentor;

use App\Http\Controllers\Controller;
use App\Models\Beasiswa;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BeasiswaController extends Controller
{
    public function pending(Request $request)
    {
        $mentor = $request->user();

        $items = Beasiswa::where('mentor_id', $mentor->id)
            ->where('status', 'pending')
            ->with('user:id,name,avatar,asrama')
            ->latest('tanggal_diajukan')
            ->get()
            ->map(fn (Beasiswa $b) => [
                'id'               => $b->id,
                'warga'            => $b->user->only(['id', 'name', 'avatar', 'asrama']),
                'nama_beasiswa'    => $b->nama_beasiswa,
                'nominal'          => (float) $b->nominal,
                'tanggal_diajukan' => $b->tanggal_diajukan->format('Y-m-d'),
            ]);

        return Inertia::render('Mentor/BeasiswaPending', [
            'items' => $items,
        ]);
    }

    public function approve(Request $request, Beasiswa $beasiswa)
    {
        abort_unless($beasiswa->mentor_id === $request->user()->id, 403);
        abort_unless($beasiswa->status === 'pending', 422, 'Pengajuan ini sudah diproses.');

        $data = $request->validate([
            'tanggal_diterima' => 'nullable|date',
            'catatan'          => 'nullable|string|max:1000',
        ]);

        $beasiswa->update([
            'status'           => 'approved',
            'tanggal_diterima' => $data['tanggal_diterima'] ?? now()->toDateString(),
            'catatan'          => $data['catatan'] ?? null,
        ]);

        return back()->with('success', "Beasiswa untuk {$beasiswa->user->name} disetujui.");
    }

    public function reject(Request $request, Beasiswa $beasiswa)
    {
        abort_unless($beasiswa->mentor_id === $request->user()->id, 403);
        abort_unless($beasiswa->status === 'pending', 422, 'Pengajuan ini sudah diproses.');

        $data = $request->validate([
            'catatan' => 'nullable|string|max:1000',
        ]);

        $beasiswa->update([
            'status'  => 'rejected',
            'catatan' => $data['catatan'] ?? null,
        ]);

        return back()->with('success', "Pengajuan beasiswa untuk {$beasiswa->user->name} ditolak.");
    }
}
