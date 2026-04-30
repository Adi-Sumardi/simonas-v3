<?php

namespace App\Http\Controllers\Web\Mentor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HafalanController extends Controller
{
    public function pending(Request $request)
    {
        // TODO: load from HafalanLog where score IS NULL and mentor_id = auth user
        $pending_logs = [];

        return Inertia::render('Mentor/HafalanPending', compact('pending_logs'));
    }

    public function score(Request $request, $id)
    {
        $request->validate([
            'score' => 'required|in:memtas,layak_ulang,perlu_perbaikan',
            'notes' => 'nullable|string|max:500',
        ]);

        // TODO: update HafalanLog record

        return redirect()->route('mentor.hafalan.pending')
            ->with('success', 'Penilaian hafalan berhasil disimpan.');
    }
}
