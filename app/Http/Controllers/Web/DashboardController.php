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
            'super'     => $this->superPayload($user),
            'admin'     => $this->adminPayload($user),
            'mentor'    => $this->mentorPayload($user),
            'alumni'    => $this->alumniPayload($user),
            default     => $this->mahasiswaPayload($user),
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
        $month = now()->month;
        $year = now()->year;
        $monthLogsCount = 0;

        $models = [
            \App\Models\Akademik::class,
            \App\Models\Leadership::class,
            \App\Models\Karakter::class,
            \App\Models\Kreatif::class,
        ];

        foreach ($models as $m) {
            $monthLogsCount += $m::where('user_id', $user->id)
                ->whereMonth('waktu', $month)
                ->whereYear('waktu', $year)
                ->count();
        }

        $logTarget = 23;

        // Points calculation based on on-time completion
        $basePoints = 1200;
        $shalatPoints = $completedOnTime * 10; 

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
                ],
                'points' => [
                    'total'   => $basePoints + $shalatPoints,
                    'rank'    => 4,
                    'to_next' => 160,
                ],
            ],
            'recent_activities' => $recentActivities,
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

    private function superPayload($user): array
    {
        // In production: replace with real DB queries
        $students = collect([
            ['id'=>1,  'name'=>'Ahmad Fauzi',       'asrama'=>'Al-Farabi',   'shalat'=>90,'akademik'=>85,'hafalan'=>75,'kepemimpinan'=>80,'karakter'=>88,'kreativitas'=>70],
            ['id'=>2,  'name'=>'Budi Santoso',       'asrama'=>'Al-Farabi',   'shalat'=>80,'akademik'=>78,'hafalan'=>82,'kepemimpinan'=>65,'karakter'=>75,'kreativitas'=>88],
            ['id'=>3,  'name'=>'Cahya Ramadhan',     'asrama'=>'Al-Ghazali', 'shalat'=>95,'akademik'=>90,'hafalan'=>88,'kepemimpinan'=>85,'karakter'=>92,'kreativitas'=>80],
            ['id'=>4,  'name'=>'Dani Pratama',       'asrama'=>'Al-Ghazali', 'shalat'=>72,'akademik'=>68,'hafalan'=>60,'kepemimpinan'=>70,'karakter'=>74,'kreativitas'=>65],
            ['id'=>5,  'name'=>'Eko Wahyudi',        'asrama'=>'Ibnu Sina',   'shalat'=>88,'akademik'=>92,'hafalan'=>70,'kepemimpinan'=>78,'karakter'=>85,'kreativitas'=>90],
            ['id'=>6,  'name'=>'Fahri Maulana',      'asrama'=>'Ibnu Sina',   'shalat'=>76,'akademik'=>80,'hafalan'=>85,'kepemimpinan'=>60,'karakter'=>78,'kreativitas'=>72],
            ['id'=>7,  'name'=>'Galih Setiawan',     'asrama'=>'Al-Kindi',    'shalat'=>85,'akademik'=>75,'hafalan'=>78,'kepemimpinan'=>88,'karakter'=>80,'kreativitas'=>76],
            ['id'=>8,  'name'=>'Hendra Gunawan',     'asrama'=>'Al-Kindi',    'shalat'=>92,'akademik'=>88,'hafalan'=>92,'kepemimpinan'=>75,'karakter'=>90,'kreativitas'=>68],
            ['id'=>9,  'name'=>'Irfan Hakim',        'asrama'=>'Al-Farabi',   'shalat'=>68,'akademik'=>72,'hafalan'=>65,'kepemimpinan'=>72,'karakter'=>70,'kreativitas'=>85],
            ['id'=>10, 'name'=>'Joko Widodo',        'asrama'=>'Al-Ghazali', 'shalat'=>82,'akademik'=>86,'hafalan'=>80,'kepemimpinan'=>82,'karakter'=>84,'kreativitas'=>78],
            ['id'=>11, 'name'=>'Kemal Aditya',       'asrama'=>'Ibnu Sina',   'shalat'=>78,'akademik'=>82,'hafalan'=>68,'kepemimpinan'=>90,'karakter'=>76,'kreativitas'=>92],
            ['id'=>12, 'name'=>'Lukman Hakim',       'asrama'=>'Al-Kindi',    'shalat'=>94,'akademik'=>89,'hafalan'=>95,'kepemimpinan'=>72,'karakter'=>93,'kreativitas'=>74],
        ])->map(fn($s) => [
            'id'     => $s['id'],
            'name'   => $s['name'],
            'asrama' => $s['asrama'],
            'total'  => round(($s['shalat']+$s['akademik']+$s['hafalan']+$s['kepemimpinan']+$s['karakter']+$s['kreativitas'])/6),
            'scores' => [
                ['subject'=>'Shalat',        'value'=>$s['shalat'],       'fullMark'=>100],
                ['subject'=>'Akademik',      'value'=>$s['akademik'],     'fullMark'=>100],
                ['subject'=>'Hafalan',       'value'=>$s['hafalan'],      'fullMark'=>100],
                ['subject'=>'Kepemimpinan',  'value'=>$s['kepemimpinan'], 'fullMark'=>100],
                ['subject'=>'Karakter',      'value'=>$s['karakter'],     'fullMark'=>100],
                ['subject'=>'Kreativitas',   'value'=>$s['kreativitas'],  'fullMark'=>100],
            ],
        ]);

        $asramas = $students->pluck('asrama')->unique()->sort()->values();

        $asramaStats = \App\Models\Asrama::all()->map(function($a) {
            return [
                'id'       => $a->id,
                'name'     => $a->nama_asrama,
                'capacity' => $a->kapasitas,
                'current'  => \App\Models\User::where('role', 'mahasiswa')->where('asrama', $a->nama_asrama)->count(),
            ];
        });

        return [
            'stats' => [
                'total_warga'   => 120,
                'total_mentor'  => 8,
                'total_alumni'  => 340,
                'avg_score'     => round($students->avg('total')),
            ],
            'students'      => $students->values(),
            'asramas'       => $asramas,
            'asrama_stats'  => $asramaStats,
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
