<?php

namespace App\Http\Controllers\Web\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Hafalan;
use App\Models\HafalanLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class HafalanController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $hafalan = Hafalan::firstOrCreate(
            ['user_id' => $user->id],
            ['target_juz' => 30, 'current_juz' => 0, 'current_ayah' => 0]
        );

        $logs = HafalanLog::where('user_id', $user->id)
            ->with('mentor:id,name,avatar')
            ->latest('tested_at')
            ->take(30)
            ->get()
            ->map(fn($l) => [
                'id'           => $l->id,
                'surah'        => $l->surah,
                'ayat_start'   => $l->ayat_start,
                'ayat_end'     => $l->ayat_end,
                'score'        => $l->score,
                'notes'        => $l->notes,
                'mentor_notes' => $l->mentor_notes,
                'tested_at'    => $l->tested_at?->format('d M Y'),
                'reviewed_at'  => $l->reviewed_at?->format('d M Y'),
                'mentor'       => $l->mentor ? ['name' => $l->mentor->name, 'avatar' => $l->mentor->avatar] : null,
            ]);

        $weekly = $this->weeklyStats($user->id);
        $quality = $this->qualityStats($user->id);

        return Inertia::render('Mahasiswa/Hafalan', [
            'hafalan'       => [
                'current_juz'         => $hafalan->current_juz,
                'current_ayah'        => $hafalan->current_ayah,
                'target_juz'          => $hafalan->target_juz,
                'streak_days'         => $hafalan->streak_days,
                'total_ayah'          => $hafalan->total_ayah_completed,
                // Bookmark fields
                'current_surah_nomor' => $hafalan->current_surah_nomor ?? 1,
                'current_surah_nama'  => $hafalan->current_surah_nama  ?? 'Al-Fatihah',
                'current_ayat'        => $hafalan->current_ayat        ?? 1,
            ],
            'logs'    => $logs,
            'weekly'  => $weekly,
            'quality' => $quality,
        ]);
    }

    public function bookmark(Request $request)
    {
        $data = $request->validate([
            'surah_nomor' => 'required|integer|min:1|max:114',
            'surah_nama'  => 'required|string|max:100',
            'ayat'        => 'required|integer|min:1',
            'juz'         => 'required|integer|min:1|max:30',
        ]);

        $user    = Auth::user();
        $hafalan = Hafalan::firstOrCreate(
            ['user_id' => $user->id],
            ['target_juz' => 30, 'current_juz' => 0, 'current_ayah' => 0]
        );

        $hafalan->update([
            'current_surah_nomor' => $data['surah_nomor'],
            'current_surah_nama'  => $data['surah_nama'],
            'current_ayat'        => $data['ayat'],
            'current_juz'         => $data['juz'],
        ]);

        return back()->with('success', 'Posisi hafalan berhasil disimpan.');
    }

    public function storeLog(Request $request)
    {
        $data = $request->validate([
            'surah'      => 'required|string|max:100',
            'ayat_start' => 'required|integer|min:1',
            'ayat_end'   => 'required|integer|gte:ayat_start',
            'notes'      => 'nullable|string|max:500',
            'tested_at'  => 'nullable|date',
        ]);

        $user = Auth::user();

        HafalanLog::create([
            'user_id'   => $user->id,
            'mentor_id' => $user->mentor_id,
            'surah'     => $data['surah'],
            'ayat_start'=> $data['ayat_start'],
            'ayat_end'  => $data['ayat_end'],
            'score'     => 'pending',
            'notes'     => $data['notes'],
            'tested_at' => $data['tested_at'] ?? now(),
        ]);

        return back()->with('success', 'Setoran hafalan berhasil dikirim ke mentor.');
    }

    public function updateLog(Request $request, HafalanLog $log)
    {
        abort_if($log->user_id !== Auth::id(), 403);
        abort_if($log->score !== 'pending', 422, 'Setoran yang sudah dinilai tidak bisa diubah.');

        $data = $request->validate([
            'surah'      => 'required|string|max:100',
            'ayat_start' => 'required|integer|min:1',
            'ayat_end'   => 'required|integer|gte:ayat_start',
            'notes'      => 'nullable|string|max:500',
            'tested_at'  => 'nullable|date',
        ]);

        $log->update($data);

        return back()->with('success', 'Setoran berhasil diperbarui.');
    }

    public function destroyLog(HafalanLog $log)
    {
        abort_if($log->user_id !== Auth::id(), 403);
        abort_if($log->score !== 'pending', 422, 'Setoran yang sudah dinilai tidak bisa dihapus.');
        $log->delete();

        return back()->with('success', 'Setoran dihapus.');
    }

    // ─── Private helpers ───────────────────────────────────────────

    private function weeklyStats(int $userId): array
    {
        $start = now()->startOfWeek();
        $logs  = HafalanLog::where('user_id', $userId)
            ->where('tested_at', '>=', $start)
            ->whereIn('score', ['memtas', 'layak_ulang'])
            ->get();

        $completed = $logs->sum(fn($l) => $l->ayat_end - $l->ayat_start + 1);
        $target    = 100; // pages per week

        return [
            'completed_pages' => $completed,
            'target_pages'    => $target,
            'percent'         => min(100, round($completed / $target * 100)),
        ];
    }

    private function qualityStats(int $userId): array
    {
        $total  = HafalanLog::where('user_id', $userId)->whereNotIn('score', ['pending'])->count();
        $mutqin = HafalanLog::where('user_id', $userId)->where('score', 'memtas')->count();
        $murajaah = HafalanLog::where('user_id', $userId)->where('score', 'layak_ulang')->count();

        return [
            'mutqin_percent'   => $total > 0 ? round($mutqin / $total * 100) : 0,
            'murajaah_percent' => $total > 0 ? round($murajaah / $total * 100) : 0,
        ];
    }
}
