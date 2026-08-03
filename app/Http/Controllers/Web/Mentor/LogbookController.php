<?php

namespace App\Http\Controllers\Web\Mentor;

use App\Http\Controllers\Controller;
use App\Models\MentoringLog;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LogbookController extends Controller
{
    public function index(Request $request)
    {
        $mentor = $request->user();

        $query = MentoringLog::where('mentor_id', $mentor->id)->with('mentee:id,name,avatar');

        if ($request->mentee_id) {
            $query->where('mentee_id', $request->mentee_id);
        }

        $perPage = min((int) $request->get('per_page', 10), 100);
        $logs = $query->latest('tanggal')->paginate($perPage)->withQueryString();

        return Inertia::render('Mentor/Logbook', [
            'logs'    => $logs,
            'mentees' => User::where('mentor_id', $mentor->id)->where('role', 'mahasiswa')
                ->select(['id', 'name'])->orderBy('name')->get(),
            'filters' => $request->only(['mentee_id']),
        ]);
    }

    public function store(Request $request)
    {
        $mentor = $request->user();

        $data = $request->validate([
            'mentee_id'     => 'required|exists:users,id',
            'tanggal'       => 'required|date',
            'topik'         => 'required|string|max:255',
            'tujuan'        => 'nullable|string|max:2000',
            'hasil_diskusi' => 'nullable|string|max:2000',
            'kendala'       => 'nullable|string|max:2000',
            'solusi'        => 'nullable|string|max:2000',
            'tindak_lanjut' => 'nullable|string|max:2000',
        ]);

        abort_unless(
            User::where('id', $data['mentee_id'])->where('mentor_id', $mentor->id)->exists(),
            403,
            'Warga ini bukan mentee kamu.'
        );

        MentoringLog::create(array_merge($data, ['mentor_id' => $mentor->id]));

        return back()->with('success', 'Log mentoring berhasil disimpan.');
    }

    public function update(Request $request, MentoringLog $log)
    {
        abort_unless($log->mentor_id === $request->user()->id, 403);

        $data = $request->validate([
            'tanggal'       => 'required|date',
            'topik'         => 'required|string|max:255',
            'tujuan'        => 'nullable|string|max:2000',
            'hasil_diskusi' => 'nullable|string|max:2000',
            'kendala'       => 'nullable|string|max:2000',
            'solusi'        => 'nullable|string|max:2000',
            'tindak_lanjut' => 'nullable|string|max:2000',
        ]);

        $log->update($data);

        return back()->with('success', 'Log mentoring berhasil diperbarui.');
    }

    public function destroy(Request $request, MentoringLog $log)
    {
        abort_unless($log->mentor_id === $request->user()->id, 403);

        $log->delete();

        return back()->with('success', 'Log mentoring berhasil dihapus.');
    }
}
