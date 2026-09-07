<?php

namespace App\Http\Controllers\Web\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\LiveMeeting;
use App\Models\Hafalan;
use App\Services\LiveKitTokenService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LiveMeetController extends Controller
{
    /**
     * Check if the student's assigned mentor is currently live.
     */
    public function checkStatus(Request $request)
    {
        $mahasiswa = $request->user();
        
        if (!$mahasiswa->mentor_id) {
            return response()->json([
                'active' => false,
                'message' => 'Anda belum memiliki mentor.'
            ]);
        }

        $activeMeeting = LiveMeeting::where('mentor_id', $mahasiswa->mentor_id)
            ->where('is_active', true)
            ->first();

        return response()->json([
            'active'      => $activeMeeting ? true : false,
            'mentor_name' => $activeMeeting ? $activeMeeting->mentor->name : null,
        ]);
    }

    /**
     * Join the mentor's active live meeting room.
     */
    public function join(Request $request, LiveKitTokenService $tokenService)
    {
        $mahasiswa = $request->user();
        
        if (!$mahasiswa->mentor_id) {
            return redirect()->back()->with('error', 'Anda belum memiliki mentor.');
        }

        $meeting = LiveMeeting::where('mentor_id', $mahasiswa->mentor_id)
            ->where('is_active', true)
            ->first();

        if (!$meeting) {
            return redirect()->back()->with('error', 'Mentor Anda tidak sedang mengadakan pertemuan Live Meet.');
        }

        // Generate token for the student (never gets roomCreate/roomAdmin)
        try {
            $token = $tokenService->generateToken(
                $meeting->room_name,
                "mahasiswa_" . $mahasiswa->id,
                $mahasiswa->name,
                false
            );
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('LiveKit token generation failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Live Meet sedang tidak dapat diakses. Silakan coba lagi beberapa saat lagi.');
        }

        $wsUrl = config('livekit.host');

        // Load bookmark hafalan
        $hafalan = Hafalan::firstOrCreate(
            ['user_id' => $mahasiswa->id],
            ['target_juz' => 30, 'current_juz' => 0, 'current_ayah' => 0]
        );

        return Inertia::render('Mahasiswa/HafalanLiveMeet', [
            'token'      => $token,
            'wsUrl'      => $wsUrl,
            'roomName'   => $meeting->room_name,
            'mentorName' => $meeting->mentor->name,
            'hafalan'    => [
                'current_surah_nomor' => $hafalan->current_surah_nomor ?? 1,
                'current_surah_nama'  => $hafalan->current_surah_nama  ?? 'Al-Fatihah',
                'current_ayat'        => $hafalan->current_ayat        ?? 1,
                'current_juz'         => $hafalan->current_juz         ?? 1,
            ]
        ]);
    }
}
