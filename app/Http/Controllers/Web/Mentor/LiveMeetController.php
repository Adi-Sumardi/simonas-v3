<?php

namespace App\Http\Controllers\Web\Mentor;

use App\Http\Controllers\Controller;
use App\Models\HafalanLog;
use App\Models\User;
use App\Models\LiveMeeting;
use App\Services\LiveKitAdminClient;
use App\Services\LiveKitTokenService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class LiveMeetController extends Controller
{
    /**
     * Start a new live meeting session for the mentor.
     */
    public function start(Request $request, LiveKitTokenService $tokenService)
    {
        $mentor = $request->user();
        $roomName = "meet_mentor_" . $mentor->id;

        // Close any old active meetings for this mentor
        LiveMeeting::where('mentor_id', $mentor->id)
            ->where('is_active', true)
            ->update([
                'is_active' => false,
                'ended_at' => now(),
            ]);

        // Create new active meeting
        LiveMeeting::create([
            'mentor_id'  => $mentor->id,
            'room_name'  => $roomName,
            'is_active'  => true,
            'started_at' => now(),
        ]);

        return redirect()->route('mentor.live-meet.view');
    }

    /**
     * View the live meeting room.
     */
    public function view(Request $request, LiveKitTokenService $tokenService)
    {
        $mentor = $request->user();
        
        $meeting = LiveMeeting::where('mentor_id', $mentor->id)
            ->where('is_active', true)
            ->first();

        // If no active meeting, auto-start a new session for the mentor
        if (!$meeting) {
            $roomName = "meet_mentor_" . $mentor->id;
            $meeting = LiveMeeting::create([
                'mentor_id'  => $mentor->id,
                'room_name'  => $roomName,
                'is_active'  => true,
                'started_at' => now(),
            ]);
        }

        // Generate token for the mentor (gracefully handle unconfigured credentials)
        $token = null;
        $wsUrl = config('livekit.host');
        $livekit_error = null;
        try {
            $token = $tokenService->generateToken(
                $meeting->room_name,
                "mentor_" . $mentor->id,
                $mentor->name,
                true
            );
        } catch (\Throwable $e) {
            $livekit_error = $e->getMessage();
            \Illuminate\Support\Facades\Log::warning('LiveKit token generation failed: ' . $e->getMessage());
        }

        // Fetch pending logs for the mentor's mentees
        $menteeIds = User::where('mentor_id', $mentor->id)
            ->where('role', 'mahasiswa')
            ->pluck('id');

        $pending_logs = HafalanLog::whereIn('user_id', $menteeIds)
            ->where('score', 'pending')
            ->with('user')
            ->latest()
            ->get()
            ->map(fn($log) => [
                'id'              => $log->id,
                'surah'           => $log->surah,
                'ayat_start'      => $log->ayat_start,
                'ayat_end'        => $log->ayat_end,
                'halaman_start'   => $log->halaman_start,
                'halaman_end'     => $log->halaman_end,
                'score'           => $log->score,
                'notes'           => $log->notes,
                'submitted_at'    => $log->created_at->diffForHumans(),
                'mahasiswa_name'  => $log->user?->name ?? 'Mahasiswa',
                'mahasiswa_nim'   => $log->user?->nim ?? $log->user?->no_induk ?? '-',
                'mahasiswa_avatar'=> $log->user?->avatar,
            ]);

        return Inertia::render('Mentor/HafalanLiveMeet', [
            'token'         => $token,
            'wsUrl'         => $wsUrl,
            'roomName'      => $meeting->room_name,
            'pending_logs'  => $pending_logs,
            'livekit_error' => $livekit_error,
        ]);
    }

    /**
     * Stop/end the current live meeting session.
     *
     * The UI's confirm dialog explicitly tells the mentor every connected student
     * will be disconnected, so we must actually close the LiveKit room here — just
     * flipping the DB flag (the old behavior) leaves the room open on the LiveKit
     * server, and a student already inside keeps their video call running
     * indefinitely since nothing ever fires their client's onDisconnected event.
     */
    public function stop(Request $request, LiveKitAdminClient $liveKit)
    {
        $mentor = $request->user();

        $meeting = LiveMeeting::where('mentor_id', $mentor->id)
            ->where('is_active', true)
            ->first();

        if ($meeting) {
            try {
                $liveKit->endRoom($meeting->room_name);
            } catch (\Throwable $e) {
                // Best-effort: the LiveKit-side room may already be gone (e.g. LiveKit's
                // own idle timeout already closed it) — our DB state below is the source
                // of truth for whether the meeting is "active" from the app's perspective.
                Log::warning('LiveKit endRoom skipped (best-effort): ' . $e->getMessage());
            }
        }

        LiveMeeting::where('mentor_id', $mentor->id)
            ->where('is_active', true)
            ->update([
                'is_active' => false,
                'ended_at'  => now(),
            ]);

        return redirect()->route('mentor.hafalan.pending')
            ->with('success', 'Pertemuan Live Meet telah diakhiri.');
    }
}
