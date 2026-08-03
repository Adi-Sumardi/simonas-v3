<?php

namespace App\Http\Controllers\Web\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\MentoringLog;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LogbookController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $perPage = min((int) $request->get('per_page', 10), 100);
        $logs = MentoringLog::where('mentee_id', $user->id)
            ->with('mentor:id,name,avatar')
            ->latest('tanggal')
            ->paginate($perPage)
            ->withQueryString();

        return Inertia::render('Mahasiswa/Logbook', [
            'logs' => $logs,
        ]);
    }
}
