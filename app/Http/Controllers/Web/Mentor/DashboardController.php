<?php

namespace App\Http\Controllers\Web\Mentor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $stats = [
            'total_mentees'          => 12,
            'avg_performance'        => 88.4,
            'pending_nilai'          => 4,
            'quran_target_percent'   => 85,
        ];

        $performance_trend = [
            ['label' => 'Week 1', 'percent' => 60],
            ['label' => 'Week 2', 'percent' => 45],
            ['label' => 'Week 3', 'percent' => 85],
            ['label' => 'Week 4', 'percent' => 70],
            ['label' => 'Active',  'percent' => 95],
        ];

        // TODO: load from User/Mahasiswa model filtered by mentor_id
        $mentees = [];

        // Featured mentee (selected mentee for quick eval)
        $featured_mentee = null;

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

        // TODO: save to MentorNilai or equivalent model

        return redirect()->route('mentor.dashboard')
            ->with('success', 'Evaluasi berhasil disimpan.');
    }
}
