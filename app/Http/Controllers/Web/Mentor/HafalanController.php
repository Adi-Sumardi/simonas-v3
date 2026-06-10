<?php

namespace App\Http\Controllers\Web\Mentor;

use App\Http\Controllers\Controller;
use App\Models\HafalanLog;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HafalanController extends Controller
{
    /**
     * Show all pending hafalan logs for mentees under this mentor.
     */
    public function pending(Request $request)
    {
        $mentor = $request->user();

        // Get mentees of this mentor
        $menteeIds = User::where('mentor_id', $mentor->id)
            ->where('role', 'mahasiswa')
            ->pluck('id');

        $pending_logs = HafalanLog::whereIn('user_id', $menteeIds)
            ->where('score', HafalanLog::SCORE_PENDING)
            ->with('user')
            ->latest()
            ->get()
            ->map(fn($log) => [
                'id'              => $log->id,
                'surah'           => $log->surah,
                'ayat_start'      => $log->ayat_start,
                'ayat_end'        => $log->ayat_end,
                'halaman_start'   => $log->halaman_start,
                'halaman_end'     => $log->halaman_end,
                'score'           => $log->score,
                'notes'           => $log->notes,
                'submitted_at'    => $log->created_at->diffForHumans(),
                'mahasiswa_name'  => $log->user->name,
                'mahasiswa_nim'   => $log->user->nim ?? $log->user->no_induk ?? '-',
                'mahasiswa_avatar'=> $log->user->avatar,
            ]);

        return Inertia::render('Mentor/HafalanPending', compact('pending_logs'));
    }

    /**
     * Score / review a hafalan log.
     */
    public function score(Request $request, $id)
    {
        $mentor = $request->user();

        $request->validate([
            'score'        => 'required|in:memtas,layak_ulang,perlu_perbaikan',
            'mentor_notes' => 'nullable|string|max:500',
        ]);

        $menteeIds = User::where('mentor_id', $mentor->id)
            ->where('role', 'mahasiswa')
            ->pluck('id');

        $log = HafalanLog::whereIn('user_id', $menteeIds)
            ->where('id', $id)
            ->firstOrFail();

        $log->update([
            'score'        => $request->score,
            'mentor_notes' => $request->mentor_notes,
            'mentor_id'    => $mentor->id,
            'reviewed_at'  => now(),
        ]);

        return redirect()->back()
            ->with('success', 'Penilaian hafalan berhasil disimpan.');
    }
}
