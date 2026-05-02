<?php

namespace App\Http\Controllers\Web\Mentor;

use App\Http\Controllers\Controller;
use App\Models\HafalanLog;
use App\Models\Hafalan;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MenteesController extends Controller
{
    public function index(Request $request)
    {
        $mentor    = $request->user();
        $search    = $request->get('search');

        $mentees = User::query()
            ->where('mentor_id', $mentor->id)
            ->where('role', 'mahasiswa')
            ->when($search, fn($q) => $q->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%")
                  ->orWhere('no_induk', 'like', "%{$search}%");
            }))
            ->select(['id','name','nim','no_induk','avatar','role','asrama','prodi','angkatan'])
            ->paginate(15)
            ->withQueryString()
            ->through(function ($m) {
                $graded = HafalanLog::where('user_id', $m->id)
                    ->whereIn('score', ['memtas', 'layak_ulang', 'perlu_perbaikan'])
                    ->latest()->limit(10)->get();

                $scoreMap = ['memtas' => 100, 'layak_ulang' => 65, 'perlu_perbaikan' => 35];
                $score = $graded->count() > 0
                    ? (int) round($graded->avg(fn($l) => $scoreMap[$l->score] ?? 50))
                    : 0;

                $pendingCount = HafalanLog::where('user_id', $m->id)
                    ->where('score', HafalanLog::SCORE_PENDING)->count();

                return [
                    'id'     => $m->id,
                    'name'   => $m->name,
                    'nim'    => $m->nim ?? $m->no_induk ?? '-',
                    'avatar' => $m->avatar,
                    'kelas'  => $m->prodi ?? '-',
                    'asrama' => $m->asrama ?? '-',
                    'score'  => $score,
                    'status' => $pendingCount > 0 ? 'review_needed' : 'ok',
                ];
            });

        $availableStudents = User::where('role', 'mahasiswa')
            ->whereNull('mentor_id')
            ->select(['id', 'name', 'nim', 'asrama', 'prodi'])
            ->get();

        return Inertia::render('Mentor/Mentees/Index', [
            'mentees' => $mentees,
            'available_students' => $availableStudents,
        ]);
    }

    public function addMentee(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:users,id',
        ]);

        $mentor = $request->user();
        $student = User::where('id', $request->student_id)
            ->where('role', 'mahasiswa')
            ->firstOrFail();

        $student->update(['mentor_id' => $mentor->id]);

        return back()->with('success', "{$student->name} berhasil ditambahkan ke bimbingan Anda.");
    }

    public function removeMentee(Request $request, $id)
    {
        $mentor = $request->user();
        $student = User::where('id', $id)
            ->where('mentor_id', $mentor->id)
            ->firstOrFail();

        $student->update(['mentor_id' => null]);

        return back()->with('success', "{$student->name} berhasil dihapus dari bimbingan Anda.");
    }

    public function show(Request $request, $id)
    {
        $mentor = $request->user();

        // Ensure this mentee belongs to the current mentor
        $mentee = User::where('id', $id)
            ->where('mentor_id', $mentor->id)
            ->where('role', 'mahasiswa')
            ->firstOrFail();

        // Hafalan progress record
        $hafalan = Hafalan::where('user_id', $mentee->id)->first();

        // Hafalan logs
        $hafalanLogs = HafalanLog::where('user_id', $mentee->id)
            ->orderByDesc('created_at')
            ->limit(30)
            ->get()
            ->map(fn($log) => [
                'id'            => $log->id,
                'surah'         => $log->surah,
                'ayat_start'    => $log->ayat_start,
                'ayat_end'      => $log->ayat_end,
                'score'         => $log->score,
                'notes'         => $log->notes,
                'mentor_notes'  => $log->mentor_notes,
                'tested_at'     => $log->tested_at?->format('Y-m-d'),
                'reviewed_at'   => $log->reviewed_at?->diffForHumans(),
                'created_at'    => $log->created_at->format('Y-m-d H:i'),
            ]);

        // Score calculation for overview
        $scoreMap    = ['memtas' => 100, 'layak_ulang' => 65, 'perlu_perbaikan' => 35];
        $gradedLogs  = $hafalanLogs->whereIn('score', array_keys($scoreMap));
        $overallScore = $gradedLogs->count() > 0
            ? (int) round($gradedLogs->avg(fn($l) => $scoreMap[$l['score']] ?? 50))
            : 0;

        // Weekly hafalan trend (last 6 weeks)
        $trend = collect(range(5, 0))->map(function ($weeksAgo) use ($mentee) {
            $start = now()->subWeeks($weeksAgo)->startOfWeek();
            $end   = now()->subWeeks($weeksAgo)->endOfWeek();
            $count = HafalanLog::where('user_id', $mentee->id)
                ->whereBetween('created_at', [$start, $end])->count();
            return [
                'label' => $weeksAgo === 0 ? 'Ini' : "W-{$weeksAgo}",
                'count' => $count,
            ];
        })->values()->toArray();

        $menteeSummary = [
            'id'            => $mentee->id,
            'name'          => $mentee->name,
            'nim'           => $mentee->nim ?? $mentee->no_induk ?? '-',
            'avatar'        => $mentee->avatar,
            'asrama'        => $mentee->asrama ?? '-',
            'kelas'         => $mentee->prodi ?? '-',
            'angkatan'      => $mentee->angkatan ?? '-',
            'no_telp'       => $mentee->no_telp ?? '-',
            'asal_sekolah'  => $mentee->asal_sekolah ?? '-',
            'score'         => $overallScore,
            'hafalan_progress' => $hafalan ? [
                'target_juz'           => $hafalan->target_juz,
                'current_juz'          => $hafalan->current_juz,
                'total_ayah_completed' => $hafalan->total_ayah_completed,
                'streak_days'          => $hafalan->streak_days,
                'progress_percent'     => $hafalan->progress_percent,
                'last_tasmi_at'        => $hafalan->last_tasmi_at?->diffForHumans(),
            ] : null,
            'pending_count' => $hafalanLogs->where('score', 'pending')->count(),
            'total_logs'    => $hafalanLogs->count(),
        ];

        return Inertia::render('Mentor/Mentees/Show', [
            'mentee'       => $menteeSummary,
            'hafalan_logs' => $hafalanLogs->values(),
            'trend'        => $trend,
        ]);
    }
}
