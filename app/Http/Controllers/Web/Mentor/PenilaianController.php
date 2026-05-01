<?php

namespace App\Http\Controllers\Web\Mentor;

use App\Http\Controllers\Controller;
use App\Models\HafalanLog;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PenilaianController extends Controller
{
    public function index(Request $request)
    {
        $mentor = $request->user();

        $menteeIds = User::where('mentor_id', $mentor->id)
            ->where('role', 'mahasiswa')
            ->pluck('id');

        $submissions = HafalanLog::whereIn('user_id', $menteeIds)
            ->with('user')
            ->orderByRaw("FIELD(score, 'pending') DESC")   // pending first
            ->orderByDesc('created_at')
            ->get()
            ->map(fn($log) => [
                'id'          => $log->id,
                'santri_id'   => $log->user_id,
                'santri_name' => $log->user->name,
                'asrama'      => $log->user->asrama ?? '-',
                'surah'       => $log->surah,
                'ayat_dari'   => $log->ayat_start,
                'ayat_sampai' => $log->ayat_end,
                'juz'         => null,   // not stored in DB, could be computed
                'tanggal'     => $log->created_at->format('Y-m-d'),
                'status'      => $log->score === HafalanLog::SCORE_PENDING ? 'pending' : 'graded',
                'score_raw'   => $log->score,
                'catatan'     => $log->mentor_notes,
            ]);

        $graded  = $submissions->where('status', 'graded');
        $scoreMap = ['memtas' => 100, 'layak_ulang' => 65, 'perlu_perbaikan' => 35];
        $avgNilai = $graded->count() > 0
            ? (int) round($graded->avg(fn($s) => $scoreMap[$s['score_raw']] ?? 0))
            : 0;

        $stats = [
            'pending'   => $submissions->where('status', 'pending')->count(),
            'graded'    => $graded->count(),
            'total'     => $submissions->count(),
            'avg_nilai' => $avgNilai,
        ];

        return Inertia::render('Mentor/Penilaian', [
            'submissions' => $submissions->values(),
            'stats'       => $stats,
        ]);
    }

    public function store(Request $request, int $id)
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

        return back()->with('success', 'Penilaian berhasil disimpan.');
    }
}
