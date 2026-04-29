<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Akademik;
use App\Models\Leadership;
use App\Models\Karakter;
use App\Models\Kreatif;
use Illuminate\Database\QueryException;

class ReportController2 extends Controller
{
    public function getSummary(Request $request)
    {
        try {
            $userId = Auth::id();
            
            // Set default date range to current month
            $startDate = Carbon::now()->startOfMonth();
            $endDate = Carbon::now()->endOfMonth();

            // Override with request dates if valid
            if ($request->filled('start_date') && $request->filled('end_date')) {
                try {
                    $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
                    $endDate = Carbon::parse($request->input('end_date'))->endOfDay();
                } catch (\Exception $e) {
                    Log::warning('Invalid date format:', [
                        'start_date' => $request->input('start_date'),
                        'end_date' => $request->input('end_date')
                    ]);
                    // Fallback to current month if date parsing fails
                    $startDate = Carbon::now()->startOfMonth();
                    $endDate = Carbon::now()->endOfMonth();
                }
            }

            // Get counts for each category
            $counts = [
                'akademik' => (int) $this->getActivityCount(Akademik::class, $userId, $startDate, $endDate),
                'leadership' => (int) $this->getActivityCount(Leadership::class, $userId, $startDate, $endDate),
                'karakter' => (int) $this->getActivityCount(Karakter::class, $userId, $startDate, $endDate),
                'kreatif' => (int) $this->getActivityCount(Kreatif::class, $userId, $startDate, $endDate),
            ];
            
            $counts['total'] = (int) array_sum($counts);

            // Get latest activities
            $latestActivities = $this->getLatestActivities($userId, $startDate, $endDate);

            // Calculate progress
            $targetActivities = 32;
            $progressPercentage = (float) min(($counts['total'] / $targetActivities) * 100, 100);
            $remainingActivities = (int) max($targetActivities - $counts['total'], 0);

            return response()->json([
                'success' => true,
                'data' => [
                    'summary' => $counts,
                    'latest_activities' => $latestActivities,
                    'period' => [
                        'start_date' => $startDate->format('Y-m-d'),
                        'end_date' => $endDate->format('Y-m-d'),
                    ],
                    'target_progress' => [
                        'current' => (int) $counts['total'],
                        'target' => (int) $targetActivities,
                        'remaining' => (int) $remainingActivities,
                        'percentage' => (float) round($progressPercentage, 1)
                    ]
                ]
            ]);

        } catch (QueryException $e) {
            Log::error('Database Error:', [
                'message' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada database'
            ], 500);
            
        } catch (\Exception $e) {
            Log::error('Report Error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server'
            ], 500);
        }
    }

    private function getActivityCount($model, $userId, $startDate, $endDate): int
    {
        return (int) $model::where('user_id', $userId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
    }

    private function getLatestActivities($userId, $startDate, $endDate): array
    {
        $tables = [
            ['model' => Akademik::class, 'type' => 'akademik'],
            ['model' => Leadership::class, 'type' => 'leadership'],
            ['model' => Karakter::class, 'type' => 'karakter'],
            ['model' => Kreatif::class, 'type' => 'kreatif']
        ];

        $activities = collect();

        foreach ($tables as $table) {
            $query = $table['model']::where('user_id', $userId)
                ->select(
                    'id',
                    'kegiatan',
                    'created_at',
                    'komponen_id'
                )
                ->selectRaw("? as aspek", [$table['type']])
                ->with('komponen:id,nama_komponen,kode')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->latest()
                ->limit(10);

            $activities = $activities->concat($query->get());
        }

        return $activities
            ->sortByDesc('created_at')
            ->take(10)
            ->map(function ($item) {
                return [
                    'id' => (int) $item->id,
                    'kegiatan' => (string) $item->kegiatan,
                    'aspek' => (string) $item->aspek,
                    'created_at' => $item->created_at->format('Y-m-d H:i:s'),
                    'komponen' => $item->komponen ? [
                        'id' => (int) $item->komponen->id,
                        'nama' => (string) $item->komponen->nama_komponen,
                        'kode' => (string) $item->komponen->kode
                    ] : null
                ];
            })
            ->values()
            ->all();
    }
}