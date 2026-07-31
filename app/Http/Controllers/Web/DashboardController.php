<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Single unified dashboard endpoint.
     * The React page reads `auth.user.role` and renders the appropriate
     * dashboard component. Spatie permissions control what data is shared.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // Share role-based data based on user's role column
        // (Spatie roles are synced from $user->role on login via RoleSyncService)
        $role = $user->role ?? 'mahasiswa';

        $payload = match ($role) {
            'super'            => $this->superPayload($user, $request),
            'admin'            => $this->adminPayload($user),
            'mentor'           => $this->mentorPayload($user),
            'alumni'           => $this->alumniPayload($user),
            'pengurus_asrama'  => $this->mahasiswaPayload($user),
            default            => $this->mahasiswaPayload($user),
        };

        return Inertia::render('Dashboard', array_merge([
            'role'        => $role,
            'permissions' => $user->getAllPermissions()->pluck('name'),
        ], $payload));
    }

    // ─── Payload per role ─────────────────────────────────────────

    private function mahasiswaPayload($user): array
    {
        $today = now()->format('Y-m-d');
        $now = now();

        $events = \App\Models\UserEvent::where('user_id', $user->id)
            ->where('type', 'shalat')
            ->get();

        $allTodayPrayers = $events->map(function($e) use ($today, $now) {
            $isCompleted = in_array($today, $e->completed_at_dates ?? []);
            
            if ($e->recurring) {
                if ($today < $e->date->format('Y-m-d')) return null;
            } else if ($e->date->format('Y-m-d') !== $today) {
                return null;
            }

            $prayerTime = \Carbon\Carbon::parse($today . ' ' . ($e->time ?? '00:00'));
            
            $checkTime = ($isCompleted && isset($e->completed_at_details[$today])) 
                ? \Carbon\Carbon::parse($e->completed_at_details[$today]) 
                : $now;

            $isLate = $checkTime->greaterThan($prayerTime->copy()->addMinutes(90));
            $isTimeArrived = $now->greaterThanOrEqualTo($prayerTime);
            
            return [
                'id'              => $e->id,
                'title'           => $e->title,
                'time'            => $e->time,
                'completed'       => $isCompleted,
                'is_late'         => $isLate,
                'is_time_arrived' => $isTimeArrived,
                'status'          => $isCompleted 
                                    ? ($isLate ? 'Tidak Tepat Waktu' : 'Tepat Waktu') 
                                    : ($isLate ? 'Terlambat' : 'Menunggu'),
            ];
        })->filter()->sortBy('time')->values();

        // Filter list to only show: already completed OR time has arrived
        $todayPrayers = $allTodayPrayers->filter(function($p) {
            return $p['completed'] || $p['is_time_arrived'];
        })->values();

        $completedOnTime = $allTodayPrayers->where('completed', true)->where('is_late', false)->count();
        $visualCompleted = $allTodayPrayers->where('completed', true)->count();
        $totalCount = $allTodayPrayers->count();
        $next = $allTodayPrayers->where('completed', false)->where('is_time_arrived', false)->first();

        // 2. Real Activity Logs Calculation (Current Month)
        // Use whereBetween instead of whereMonth/whereYear — compatible with PostgreSQL
        $startOfMonth = now()->startOfMonth()->toDateTimeString();
        $endOfMonth   = now()->endOfMonth()->toDateTimeString();
        $monthLogsCount = 0;

        $models = [
            \App\Models\Akademik::class,
            \App\Models\Leadership::class,
            \App\Models\Karakter::class,
            \App\Models\Kreatif::class,
        ];

        foreach ($models as $m) {
            $monthLogsCount += $m::where('user_id', $user->id)
                ->whereBetween('waktu', [$startOfMonth, $endOfMonth])
                ->count();
        }

        $logTarget = (int) \App\Models\DailyTarget::val('study_hour_target', 23);
        $totalPoints = $user->calculatePoints();

        // 1. Real Hafalan Data
        $hafalanRecord = \App\Models\Hafalan::where('user_id', $user->id)->first();

        // 3. Recent Activities (Top 3)
        $recent = collect();
        foreach ($models as $m) {
            $items = $m::where('user_id', $user->id)
                ->latest('waktu')
                ->take(3)
                ->get()
                ->map(fn($r) => [
                    'id'         => $r->id,
                    'jenis'      => $r->kegiatan ?? $r->nama_kegiatan ?? 'Aktivitas',
                    'deskripsi'  => ($r->tempat ? $r->tempat . ' - ' : '') . ($r->keterangan ?? ''),
                    'created_at' => $r->waktu ? \Carbon\Carbon::parse($r->waktu)->format('d M Y') : $r->created_at->format('d M Y'),
                    'icon'       => 'event_note',
                    'image_url'  => $r->file ? asset('storage/' . $r->file) : null,
                ]);
            $recent = $recent->merge($items);
        }
        $recentActivities = $recent->sortByDesc(fn($r) => $r['created_at'])->take(3)->values();

        // Bulk-compute points for all mahasiswa: O(activity_types) queries, not O(N * activity_types)
        $studentIds = \App\Models\User::where('role', 'mahasiswa')->pluck('id');
        $allPoints  = array_fill_keys($studentIds->toArray(), 0);
        $rules      = \App\Models\PointRule::activeRules();

        if ($rules->isNotEmpty()) {
            $bulkCounts = [
                'akademik'   => \App\Models\Akademik::whereIn('user_id', $studentIds)->selectRaw('user_id, COUNT(*) as cnt')->groupBy('user_id')->pluck('cnt', 'user_id'),
                'leadership' => \App\Models\Leadership::whereIn('user_id', $studentIds)->selectRaw('user_id, COUNT(*) as cnt')->groupBy('user_id')->pluck('cnt', 'user_id'),
                'karakter'   => \App\Models\Karakter::whereIn('user_id', $studentIds)->selectRaw('user_id, COUNT(*) as cnt')->groupBy('user_id')->pluck('cnt', 'user_id'),
                'kreatif'    => \App\Models\Kreatif::whereIn('user_id', $studentIds)->selectRaw('user_id, COUNT(*) as cnt')->groupBy('user_id')->pluck('cnt', 'user_id'),
                'hafalan'    => \App\Models\HafalanLog::whereIn('user_id', $studentIds)->where('score', 'memtas')->selectRaw('user_id, COUNT(*) as cnt')->groupBy('user_id')->pluck('cnt', 'user_id'),
            ];
            foreach ($rules as $rule) {
                $counts = $bulkCounts[$rule->activity_type] ?? collect();
                foreach ($counts as $uid => $cnt) {
                    $allPoints[$uid] = ($allPoints[$uid] ?? 0) + (int)$cnt * $rule->poin;
                }
            }
        } else {
            $allPoints[$user->id] = $totalPoints;
        }

        arsort($allPoints);
        $sortedIds = array_keys($allPoints);
        $rankPos   = array_search($user->id, $sortedIds);
        $rank      = $rankPos !== false ? (int)$rankPos + 1 : count($allPoints);
        $toNext    = 0;
        if ($rank > 1) {
            $toNext = max(0, ($allPoints[$sortedIds[$rank - 2]] ?? 0) - $totalPoints + 1);
        }

        return [
            'stats' => [
                'shalat' => [
                    'completed'   => $completedOnTime,
                    'visual_done' => $visualCompleted,
                    'total'       => $totalCount ?: 5,
                    'next_prayer' => $next ? $next['title'] . ' ' . $next['time'] : 'Selesai',
                    'list'        => $todayPrayers,
                ],
                'activity_logs' => [
                    'count'  => $monthLogsCount,
                    'target' => $logTarget
                ],
                'hafalan' => [
                    'progress_percent' => $hafalanRecord ? $hafalanRecord->progress_percent : 0,
                    'current_surah'    => $hafalanRecord ? $hafalanRecord->current_surah_nama : 'Belum Mulai',
                    'juz'              => $hafalanRecord ? $hafalanRecord->current_juz : 0,
                    'current_page'     => $hafalanRecord ? $hafalanRecord->current_page : null,
                ],
                'points' => [
                    'total'   => $totalPoints,
                    'rank'    => $rank,
                    'to_next' => $toNext,
                ],
            ],
            'recent_activities' => $recentActivities,
            'latest_jobs' => \App\Models\AlumniJob::with('user:id,name,avatar')
                ->where('is_active', true)
                ->latest()
                ->take(3)
                ->get()
                ->map(fn($job) => [
                    'id'           => $job->id,
                    'title'        => $job->title,
                    'company'      => $job->company,
                    'location'     => $job->location ?? 'Remote',
                    'work_type'    => $job->work_type ?? 'onsite',
                    'salary_range' => $job->salary_range ?? 'Kompetitif',
                    'description'  => $job->description,
                    'requirements' => $job->requirements,
                    'contact_info' => $job->contact_info,
                    'posted_by'    => $job->user->name ?? 'Alumni',
                    'posted_at'    => $job->created_at->diffForHumans(),
                ]),
        ];
    }

    private function mentorPayload($user): array
    {
        $mentees = \App\Models\User::where('mentor_id', $user->id)->get();
        $menteeIds = $mentees->pluck('id');

        // Stats
        $totalMentees = $mentees->count();
        
        // Avg Performance (Scale 0-100)
        $avgAkademik = \App\Models\Akademik::whereIn('user_id', $menteeIds)->avg('nilai') ?? 0;
        $avgLeadership = \App\Models\Leadership::whereIn('user_id', $menteeIds)->avg('nilai') ?? 0;
        $avgKarakter = \App\Models\Karakter::whereIn('user_id', $menteeIds)->avg('nilai') ?? 0;
        $avgKreatif = \App\Models\Kreatif::whereIn('user_id', $menteeIds)->avg('nilai') ?? 0;
        
        $avgPerformance = ($avgAkademik + $avgLeadership + $avgKarakter + $avgKreatif) / 4;

        $pendingNilai = \App\Models\HafalanLog::where('mentor_id', $user->id)
            ->where('score', 'pending')
            ->count();

        // Quran Target: based on average progress in 'hafalans' table
        $hafalanRecords = \App\Models\Hafalan::whereIn('user_id', $menteeIds)->get();
        $avgQuran = $hafalanRecords->count() > 0 ? $hafalanRecords->avg('progress_percent') : 0;

        // Performance Trend (last 5 weeks)
        $trend = [];
        for ($i = 4; $i >= 0; $i--) {
            $start = now()->subWeeks($i)->startOfWeek();
            $end = now()->subWeeks($i)->endOfWeek();
            
            $weekAvg = \App\Models\Akademik::whereIn('user_id', $menteeIds)
                ->whereBetween('created_at', [$start, $end])
                ->avg('nilai') ?? 50; // default 50 if no data
            
            $trend[] = [
                'label'   => $i === 0 ? 'Now' : 'W' . (now()->subWeeks($i)->weekOfYear),
                'percent' => (int)$weekAvg
            ];
        }

        $menteesList = $mentees->map(function($m) {
            $hafalan = \App\Models\Hafalan::where('user_id', $m->id)->first();
            return [
                'id'       => $m->id,
                'name'     => $m->name,
                'avatar'   => $m->avatar,
                'asrama'   => $m->asrama,
                'progress' => $hafalan ? $hafalan->progress_percent : 0,
                'last_log' => $m->updated_at->diffForHumans(),
            ];
        });

        return [
            'stats' => [
                'total_mentees'        => $totalMentees,
                'avg_performance'      => round($avgPerformance, 1),
                'pending_nilai'        => $pendingNilai,
                'quran_target_percent' => round($avgQuran, 1),
                'performance_trend'    => $trend,
                'mentees'              => $menteesList,
                'featured_mentee'      => $menteesList->sortByDesc('progress')->first(),
            ],
        ];
    }

    private function superPayload($user, ?Request $request = null): array
    {
        $totalWarga  = \App\Models\User::where('role', 'mahasiswa')->count();
        $totalMentor = \App\Models\User::where('role', 'mentor')->count();
        $totalAlumni = \App\Models\User::where('role', 'alumni')->count();

        // Rentang tanggal untuk radar chart — default bulan berjalan.
        $radarFrom = $request?->query('radar_from') ?: now()->startOfMonth()->toDateString();
        $radarTo   = $request?->query('radar_to')   ?: now()->endOfMonth()->toDateString();

        // Build real student radar data from DB
        $mahasiswas = \App\Models\User::where('role', 'mahasiswa')->take(20)->get();

        $students = $mahasiswas->map(function ($m) use ($radarFrom, $radarTo) {
            $scores = $m->radarScores($radarFrom, $radarTo);

            return [
                'id'     => $m->id,
                'name'   => $m->name,
                'asrama' => $m->asrama ?? '-',
                'total'  => round(collect($scores)->avg('value')),
                'scores' => $scores,
            ];
        });

        $asramas = \App\Models\Asrama::orderBy('nama_asrama')->pluck('nama_asrama');

        $asramaStats = \App\Models\Asrama::all()->map(function($a) {
            return [
                'id'       => $a->id,
                'name'     => $a->nama_asrama,
                'capacity' => $a->kapasitas,
                'current'  => \App\Models\User::where('role', 'mahasiswa')->where('asrama', $a->nama_asrama)->count(),
            ];
        });

        $avgScore = $students->count() > 0 ? round($students->avg('total')) : 0;

        return [
            'stats' => [
                'total_warga'   => $totalWarga,
                'total_mentor'  => $totalMentor,
                'total_alumni'  => $totalAlumni,
                'avg_score'     => $avgScore,
            ],
            'students'      => $students->values(),
            'asramas'       => $asramas,
            'asrama_stats'  => $asramaStats,
            'radar_range'   => ['from' => $radarFrom, 'to' => $radarTo],
        ];
    }

    private function adminPayload($user): array
    {
        return [
            'stats' => [
                'total_warga'  => 30,
                'pending_input'=> 5,
            ],
        ];
    }

    private function alumniPayload($user): array
    {
        return [
            'stats' => [
                'total_alumni' => \App\Models\Alumni::count(),
                'network_size' => \App\Models\User::where('role', 'alumni')->count(),
                'total_posts'  => \App\Models\AlumniPost::count(),
                'total_jobs'   => \App\Models\AlumniJob::where('is_active', true)->count(),
            ],
        ];
    }
}
