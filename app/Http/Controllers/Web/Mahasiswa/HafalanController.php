<?php

namespace App\Http\Controllers\Web\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Hafalan;
use App\Models\HafalanLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class HafalanController extends Controller
{
    // Jumlah ayat resmi per surah — dipakai untuk memvalidasi setoran hafalan
    // supaya ayat_start/ayat_end tidak melebihi panjang surah aslinya.
    private const SURAH_AYAT_COUNT = [
        'Al-Fatihah' => 7, 'Al-Baqarah' => 286, 'Ali Imran' => 200, 'An-Nisa' => 176,
        'Al-Maidah' => 120, 'Al-Anam' => 165, 'Al-Araf' => 206, 'Al-Anfal' => 75,
        'At-Taubah' => 129, 'Yunus' => 109, 'Hud' => 123, 'Yusuf' => 111,
        "Ar-Ra'd" => 43, 'Ibrahim' => 52, 'Al-Hijr' => 99, 'An-Nahl' => 128,
        'Al-Isra' => 111, 'Al-Kahf' => 110, 'Maryam' => 98, 'Ta Ha' => 135,
        'Al-Anbiya' => 112, 'Al-Hajj' => 78, "Al-Mu'minun" => 118, 'An-Nur' => 64,
        'Al-Furqan' => 77, "Ash-Shu'ara" => 227, 'An-Naml' => 93, 'Al-Qasas' => 88,
        'Al-Ankabut' => 69, 'Ar-Rum' => 60, 'Luqman' => 34, 'As-Sajdah' => 30,
        'Al-Ahzab' => 73, 'Saba' => 54, 'Fatir' => 45, 'Ya-Sin' => 83,
        'As-Saffat' => 182, 'Sad' => 88, 'Az-Zumar' => 75, 'Ghafir' => 85,
        'Fussilat' => 54, 'Ash-Shura' => 53, 'Az-Zukhruf' => 89, 'Ad-Dukhan' => 59,
        'Al-Jathiyah' => 37, 'Al-Ahqaf' => 35, 'Muhammad' => 38, 'Al-Fath' => 29,
        'Al-Hujurat' => 18, 'Qaf' => 45, 'Adh-Dhariyat' => 60, 'At-Tur' => 49,
        'An-Najm' => 62, 'Al-Qamar' => 55, 'Ar-Rahman' => 78, 'Al-Waqia' => 96,
        'Al-Hadid' => 29, 'Al-Mujadila' => 22, 'Al-Hashr' => 24, 'Al-Mumtahanah' => 13,
        'As-Saf' => 14, 'Al-Jumuah' => 11, 'Al-Munafiqun' => 11, 'At-Taghabun' => 18,
        'At-Talaq' => 12, 'At-Tahrim' => 12, 'Al-Mulk' => 30, 'Al-Qalam' => 52,
        'Al-Haqqah' => 52, 'Al-Maarij' => 44, 'Nuh' => 28, 'Al-Jinn' => 28,
        'Al-Muzzammil' => 20, 'Al-Muddaththir' => 56, 'Al-Qiyamah' => 40, 'Al-Insan' => 31,
        'Al-Mursalat' => 50, 'An-Naba' => 40, 'An-Naziat' => 46, 'Abasa' => 42,
        'At-Takwir' => 29, 'Al-Infitar' => 19, 'Al-Mutaffifin' => 36, 'Al-Inshiqaq' => 25,
        'Al-Buruj' => 22, 'At-Tariq' => 17, 'Al-Ala' => 19, 'Al-Ghashiyah' => 26,
        'Al-Fajr' => 30, 'Al-Balad' => 20, 'Ash-Shams' => 15, 'Al-Layl' => 21,
        'Ad-Duhaa' => 11, 'Ash-Sharh' => 8, 'At-Tin' => 8, 'Al-Alaq' => 19,
        'Al-Qadr' => 5, 'Al-Bayyinah' => 8, 'Az-Zalzalah' => 8, 'Al-Adiyat' => 11,
        'Al-Qariah' => 11, 'At-Takathur' => 8, 'Al-Asr' => 3, 'Al-Humazah' => 9,
        'Al-Fil' => 5, 'Quraish' => 4, 'Al-Maun' => 7, 'Al-Kawthar' => 3,
        'Al-Kafirun' => 6, 'An-Nasr' => 3, 'Al-Masad' => 5, 'Al-Ikhlas' => 4,
        'Al-Falaq' => 5, 'An-Nas' => 6,
    ];

    private function validateHafalanLog(Request $request): array
    {
        return $request->validate([
            'surah'         => ['required', 'string', Rule::in(array_keys(self::SURAH_AYAT_COUNT))],
            'ayat_start'    => 'required|integer|min:1',
            'ayat_end'      => [
                'required', 'integer', 'gte:ayat_start',
                function ($attribute, $value, $fail) use ($request) {
                    $max = self::SURAH_AYAT_COUNT[$request->input('surah')] ?? null;
                    if ($max !== null && $value > $max) {
                        $fail("Surah {$request->input('surah')} hanya memiliki {$max} ayat.");
                    }
                },
            ],
            'halaman_start' => 'nullable|integer|min:1|max:604',
            'halaman_end'   => 'nullable|integer|gte:halaman_start|max:604',
            'notes'         => 'nullable|string|max:500',
            'tested_at'     => 'nullable|date',
        ]);
    }

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
                'id'            => $l->id,
                'surah'         => $l->surah,
                'ayat_start'    => $l->ayat_start,
                'ayat_end'      => $l->ayat_end,
                'halaman_start' => $l->halaman_start,
                'halaman_end'   => $l->halaman_end,
                'score'         => $l->score,
                'notes'         => $l->notes,
                'mentor_notes'  => $l->mentor_notes,
                'tested_at'     => $l->tested_at?->format('d M Y'),
                'reviewed_at'   => $l->reviewed_at?->format('d M Y'),
                'mentor'        => $l->mentor ? ['name' => $l->mentor->name, 'avatar' => $l->mentor->avatar] : null,
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
                'current_page'        => $hafalan->current_page        ?? 1,
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
            'page'        => 'nullable|integer|min:1|max:604',
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
            'current_page'        => $data['page'] ?? null,
        ]);

        return back()->with('success', 'Posisi hafalan berhasil disimpan.');
    }

    public function storeLog(Request $request)
    {
        $data = $this->validateHafalanLog($request);

        $user = Auth::user();

        HafalanLog::create([
            'user_id'       => $user->id,
            'mentor_id'     => $user->mentor_id,
            'surah'         => $data['surah'],
            'ayat_start'    => $data['ayat_start'],
            'ayat_end'      => $data['ayat_end'],
            'halaman_start' => $data['halaman_start'],
            'halaman_end'   => $data['halaman_end'],
            'score'         => 'pending',
            'notes'         => $data['notes'],
            'tested_at'     => $data['tested_at'] ?? now(),
        ]);

        return back()->with('success', 'Setoran hafalan berhasil dikirim ke mentor.');
    }

    public function updateLog(Request $request, HafalanLog $log)
    {
        abort_if($log->user_id !== Auth::id(), 403);
        abort_if($log->score !== 'pending', 422, 'Setoran yang sudah dinilai tidak bisa diubah.');

        $data = $this->validateHafalanLog($request);

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
