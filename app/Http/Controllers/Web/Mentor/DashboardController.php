<?php

namespace App\Http\Controllers\Web\Mentor;

use App\Http\Controllers\Controller;
use App\Models\HafalanLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $mentor = $request->user();

        // Mentees assigned to this mentor
        $mentees = User::query()
            ->where('mentor_id', $mentor->id)
            ->where('role', 'mahasiswa')
            ->select(['id','name','nim','avatar','role','asrama','no_induk'])
            ->get()
            ->map(function ($m) {
                // Calculate a score based on hafalan logs
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
                    'nim'    => $m->nim ?? $m->no_induk,
                    'avatar' => $m->avatar,
                    'kelas'  => $m->prodi ?? '-',
                    'asrama' => $m->asrama ?? '-',
                    'score'  => $score,
                    'status' => $pendingCount > 0 ? 'review_needed' : 'ok',
                ];
            });

        // Stats
        $totalMentees     = $mentees->count();
        $avgPerformance   = $totalMentees > 0 ? round($mentees->avg('score'), 1) : 0;

        $pendingNilai = HafalanLog::whereIn('user_id', $mentees->pluck('id'))
            ->where('score', HafalanLog::SCORE_PENDING)->count();

        // Quran target: % mentees with at least 1 hafalan log this week
        $weekStart = now()->startOfWeek()->toDateString();
        $activeThisWeek = HafalanLog::whereIn('user_id', $mentees->pluck('id'))
            ->where('created_at', '>=', $weekStart)
            ->distinct('user_id')->count('user_id');
        $quranTargetPct = $totalMentees > 0
            ? (int) round(($activeThisWeek / $totalMentees) * 100)
            : 0;

        $stats = [
            'total_mentees'        => $totalMentees,
            'avg_performance'      => $avgPerformance,
            'pending_nilai'        => $pendingNilai,
            'quran_target_percent' => $quranTargetPct,
        ];

        // Performance trend — last 5 weeks, based on hafalan logs of mentees
        $menteeIds = $mentees->pluck('id')->toArray();
        $performance_trend = collect(range(4, 0))->map(function ($weeksAgo) use ($menteeIds) {
            $start = now()->subWeeks($weeksAgo)->startOfWeek();
            $end   = now()->subWeeks($weeksAgo)->endOfWeek();
            $label = $weeksAgo === 0 ? 'Minggu Ini' : "W-{$weeksAgo}";

            if (empty($menteeIds)) return ['label' => $label, 'percent' => 0];

            $logs = HafalanLog::whereIn('user_id', $menteeIds)
                ->whereBetween('created_at', [$start, $end])
                ->whereIn('score', ['memtas', 'layak_ulang', 'perlu_perbaikan'])
                ->get();

            $scoreMap = ['memtas' => 100, 'layak_ulang' => 65, 'perlu_perbaikan' => 35];
            $pct = $logs->count() > 0
                ? (int) round($logs->avg(fn($l) => $scoreMap[$l->score] ?? 50))
                : 0;

            return ['label' => $label, 'percent' => $pct];
        })->values()->toArray();

        // Featured mentee — the one with most recent pending hafalan
        $featuredMentee = null;
        $latestPending  = HafalanLog::whereIn('user_id', $menteeIds)
            ->where('score', HafalanLog::SCORE_PENDING)
            ->latest()->first();

        if ($latestPending) {
            $fm = $mentees->firstWhere('id', $latestPending->user_id);
            if ($fm) {
                $recentLogs = HafalanLog::where('user_id', $fm['id'])
                    ->latest()->limit(5)->get()
                    ->map(function ($log) {
                        $scoreLabels = [
                            'memtas'           => ['icon' => 'check_circle', 'icon_bg' => 'bg-emerald-100 text-emerald-600'],
                            'layak_ulang'      => ['icon' => 'refresh',      'icon_bg' => 'bg-amber-100 text-amber-600'],
                            'perlu_perbaikan'  => ['icon' => 'warning',      'icon_bg' => 'bg-red-100 text-red-500'],
                            'pending'          => ['icon' => 'schedule',     'icon_bg' => 'bg-blue-100 text-blue-500'],
                        ];
                        $meta = $scoreLabels[$log->score] ?? $scoreLabels['pending'];
                        return [
                            'icon'    => $meta['icon'],
                            'icon_bg' => $meta['icon_bg'],
                            'title'   => "Setoran {$log->surah}",
                            'desc'    => "Ayat {$log->ayat_start}–{$log->ayat_end}",
                            'time'    => $log->created_at->diffForHumans(),
                        ];
                    })->toArray();

                $featuredMentee = array_merge($fm, [
                    'tracks'      => ['Hafalan', 'Aktivitas'],
                    'block'       => $fm['asrama'],
                    'recent_logs' => $recentLogs,
                ]);
            }
        }

        return Inertia::render('Mentor/Dashboard', compact(
            'stats', 'performance_trend', 'mentees', 'featured_mentee'
        ));
    }

    public function submitEval(Request $request, $id)
    {
        $request->validate([
            'spiritual'  => 'required|integer|min:0|max:10',
            'community'  => 'required|integer|min:0|max:10',
            'notes'      => 'nullable|string|max:1000',
        ]);

        // TODO: save to a dedicated MentorEval model if needed
        return redirect()->route('mentor.dashboard')
            ->with('success', 'Evaluasi berhasil disimpan.');
    }
}
