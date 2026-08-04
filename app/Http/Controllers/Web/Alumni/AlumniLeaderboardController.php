<?php

namespace App\Http\Controllers\Web\Alumni;

use App\Http\Controllers\Controller;
use App\Models\Akademik;
use App\Models\Karakter;
use App\Models\Kreatif;
use App\Models\Leadership;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AlumniLeaderboardController extends Controller
{
    // Sama dengan Mahasiswa\LeaderboardController — jaga konsisten.
    private const MONTHLY_ACTIVITY_TARGET = 120;

    public function index(Request $request)
    {
        $asrama = $request->get('asrama', 'Semua');
        $from = $request->query('from') ?: now()->startOfMonth()->toDateString();
        $to   = $request->query('to')   ?: now()->endOfMonth()->toDateString();

        $query = User::where('role', 'mahasiswa');
        if ($asrama !== 'Semua') {
            $query->where('asrama', $asrama);
        }
        $students = $query->get();
        $studentIds = $students->pluck('id');

        $countByUser = function (string $model) use ($studentIds, $from, $to) {
            return $model::whereIn('user_id', $studentIds)
                ->whereBetween('waktu', [$from, $to])
                ->selectRaw('user_id, SUM(poin) as cnt')
                ->groupBy('user_id')
                ->pluck('cnt', 'user_id');
        };

        $akademikCounts   = $countByUser(Akademik::class);
        $leadershipCounts = $countByUser(Leadership::class);
        $karakterCounts   = $countByUser(Karakter::class);
        $kreatifCounts    = $countByUser(Kreatif::class);

        $entries = $students->map(function ($u) use ($akademikCounts, $leadershipCounts, $karakterCounts, $kreatifCounts) {
            $total = (int) ($akademikCounts[$u->id] ?? 0)
                + (int) ($leadershipCounts[$u->id] ?? 0)
                + (int) ($karakterCounts[$u->id] ?? 0)
                + (int) ($kreatifCounts[$u->id] ?? 0);

            return [
                'user_id'   => $u->id,
                'name'      => $u->name,
                'asrama'    => $u->asrama ?? '-',
                'avatar'    => $u->avatar,
                'points'    => $total,
                'target'    => self::MONTHLY_ACTIVITY_TARGET,
                'terpenuhi' => $total >= self::MONTHLY_ACTIVITY_TARGET,
            ];
        })
            ->sortByDesc('points')
            ->values()
            ->map(function ($entry, $index) {
                $entry['rank'] = $index + 1;
                return $entry;
            });

        $top3 = $entries->take(3)->values()->toArray();
        $rest = $entries->slice(3)->values()->toArray();

        $available_asrama = User::where('role', 'mahasiswa')
            ->whereNotNull('asrama')
            ->distinct()
            ->pluck('asrama')
            ->sort()
            ->values()
            ->toArray();

        return Inertia::render('Alumni/Leaderboard', compact(
            'top3', 'available_asrama'
        ) + [
            'entries'       => $rest,
            'asrama_filter' => $asrama,
            'filters'       => ['from' => $from, 'to' => $to],
            'monthlyTarget' => self::MONTHLY_ACTIVITY_TARGET,
        ]);
    }
}
