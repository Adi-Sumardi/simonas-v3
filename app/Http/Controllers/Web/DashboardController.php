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
        return [
            'stats' => [
                'shalat'       => ['completed' => 4, 'total' => 5, 'next' => 'Isha 19:15'],
                'study_hours'  => ['today' => 3.5, 'target' => 5],
                'hafalan'      => ['progress_percent' => 75, 'current_surah' => 'Al-Kahf', 'juz' => 15],
                'points'       => ['total' => 1240, 'rank' => 4, 'to_next' => 160],
            ],
            'recent_activities' => [],
        ];
    }

    private function mentorPayload($user): array
    {
        return [
            'stats' => [
                'total_mentees'        => 12,
                'avg_performance'      => 88.4,
                'pending_nilai'        => 4,
                'quran_target_percent' => 85,
            ],
            'performance_trend' => [
                ['label' => 'Week 1', 'percent' => 60],
                ['label' => 'Week 2', 'percent' => 45],
                ['label' => 'Week 3', 'percent' => 85],
                ['label' => 'Week 4', 'percent' => 70],
                ['label' => 'Now',    'percent' => 95],
            ],
            'mentees'        => [],
            'featured_mentee'=> null,
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

        return [
            'stats' => [
                'total_warga'   => 120,
                'total_mentor'  => 8,
                'total_alumni'  => 340,
                'avg_score'     => round($students->avg('total')),
            ],
            'students' => $students->values(),
            'asramas'  => $asramas,
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
                'total_alumni' => 340,
                'network_size' => 120,
            ],
        ];
    }
}
