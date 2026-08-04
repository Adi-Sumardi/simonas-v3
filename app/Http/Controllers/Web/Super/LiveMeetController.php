<?php

namespace App\Http\Controllers\Web\Super;

use App\Http\Controllers\Controller;
use App\Models\LiveMeetingParticipant;
use App\Models\LiveMeetingRecording;
use App\Models\LiveMeetingRoom;
use App\Models\Notification;
use App\Services\LiveKitAdminClient;
use App\Services\LiveKitTokenService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class LiveMeetController extends Controller
{
    // ── Dashboard ──────────────────────────────────────────────

    public function index()
    {
        $active = LiveMeetingRoom::where('status', '!=', 'ended')
            ->with('host:id,name,avatar')
            ->latest()
            ->get()
            ->map(fn (LiveMeetingRoom $r) => $this->roomSummary($r));

        $riwayat = LiveMeetingRoom::where('status', 'ended')
            ->where('created_at', '>=', now()->subDays(7))
            ->with(['host:id,name,avatar', 'recordings'])
            ->latest('ended_at')
            ->get()
            ->map(fn (LiveMeetingRoom $r) => $this->roomSummary($r));

        return Inertia::render('Super/LiveMeet/Index', [
            'active'  => $active,
            'riwayat' => $riwayat,
        ]);
    }

    private function roomSummary(LiveMeetingRoom $r): array
    {
        return [
            'id'                    => $r->id,
            'title'                 => $r->title,
            'room_name'             => $r->room_name,
            'status'                => $r->status,
            'host'                  => $r->host?->only(['id', 'name', 'avatar']),
            'started_at'            => $r->started_at?->format('Y-m-d H:i'),
            'ended_at'              => $r->ended_at?->format('Y-m-d H:i'),
            'waiting_room_enabled'  => $r->waiting_room_enabled,
            'participants_count'    => $r->participants()->where('status', 'admitted')->count(),
            'recordings' => $r->recordings->map(fn (LiveMeetingRecording $rec) => [
                'id'         => $rec->id,
                'status'     => $rec->status,
                'expires_at' => $rec->expires_at?->format('Y-m-d H:i'),
                'days_left'  => $rec->expires_at ? max(0, now()->diffInDays($rec->expires_at, false)) : null,
                'downloaded_at' => $rec->downloaded_at?->format('Y-m-d H:i'),
            ]),
        ];
    }

    // ── Create / manage room ──────────────────────────────────

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'                 => 'required|string|max:255',
            'waiting_room_enabled'  => 'nullable|boolean',
        ]);

        $host = $request->user();
        $room = LiveMeetingRoom::create([
            'host_id'               => $host->id,
            'room_name'             => 'live_meet_' . Str::random(12),
            'title'                 => $data['title'],
            'passcode'              => (string) random_int(100000, 999999),
            'status'                => 'live',
            'waiting_room_enabled'  => $request->boolean('waiting_room_enabled'),
            'started_at'            => now(),
        ]);

        LiveMeetingParticipant::create([
            'room_id' => $room->id,
            'user_id' => $host->id,
            'role'    => 'host',
            'status'  => 'admitted',
            'joined_at' => now(),
        ]);

        return redirect()->route('super.live-meet.show', $room->id);
    }

    public function show(Request $request, LiveMeetingRoom $room, LiveKitTokenService $tokenService)
    {
        $user = $request->user();
        $participant = $room->participants()->where('user_id', $user->id)->first();

        // Host viewing their own room dashboard even before others join.
        if (!$participant) {
            abort_if($room->host_id !== $user->id, 403, 'Anda bukan peserta meeting ini.');
            $participant = LiveMeetingParticipant::create([
                'room_id' => $room->id,
                'user_id' => $user->id,
                'role'    => 'host',
                'status'  => 'admitted',
                'joined_at' => now(),
            ]);
        }

        abort_if($participant->status !== 'admitted', 403, 'Anda belum diizinkan masuk ke meeting ini.');

        $token = $tokenService->generateToken(
            $room->room_name,
            'user_' . $user->id,
            $user->name,
            false // no client ever gets roomAdmin — see LiveKitAdminClient
        );

        $isHostOrCoHost = $participant->isHostOrCoHost();

        return Inertia::render('Super/LiveMeet/Room', [
            'room' => [
                'id'    => $room->id,
                'title' => $room->title,
                'room_name' => $room->room_name,
                'passcode' => $isHostOrCoHost ? $room->passcode : null,
                'status' => $room->status,
                'waiting_room_enabled' => $room->waiting_room_enabled,
            ],
            'myRole'   => $participant->role,
            'token'    => $token,
            'wsUrl'    => config('livekit.host'),
            'hasActiveRecording' => $room->recordings()->where('status', 'recording')->exists(),
            'participants' => $isHostOrCoHost
                ? $room->participants()->where('status', 'admitted')->with('user:id,name,avatar')->get()->map(fn (LiveMeetingParticipant $p) => [
                    'id'      => $p->id,
                    'user_id' => $p->user_id,
                    'name'    => $p->user?->name,
                    'avatar'  => $p->user?->avatar,
                    'role'    => $p->role,
                ])
                : [],
            'waitingQueue' => $isHostOrCoHost && $room->waiting_room_enabled
                ? $room->participants()->where('status', 'pending')->with('user:id,name,avatar')->get()->map(fn (LiveMeetingParticipant $p) => [
                    'id'      => $p->id,
                    'user_id' => $p->user_id,
                    'name'    => $p->user?->name,
                    'avatar'  => $p->user?->avatar,
                ])
                : [],
        ]);
    }

    // ── Join via passcode + waiting room ──────────────────────

    public function join(Request $request, LiveMeetingRoom $room)
    {
        return Inertia::render('Super/LiveMeet/Join', [
            'room' => [
                'id' => $room->id,
                'title' => $room->title,
                'status' => $room->status,
            ],
        ]);
    }

    public function joinSubmit(Request $request, LiveMeetingRoom $room)
    {
        abort_if($room->status === 'ended', 410, 'Meeting ini sudah selesai.');

        $data = $request->validate(['passcode' => 'required|string']);
        abort_unless($data['passcode'] === $room->passcode, 422, 'Passcode salah.');

        $user = $request->user();

        $participant = LiveMeetingParticipant::firstOrCreate(
            ['room_id' => $room->id, 'user_id' => $user->id],
            [
                'role'   => 'participant',
                'status' => $room->waiting_room_enabled ? 'pending' : 'admitted',
                'joined_at' => now(),
            ]
        );

        if ($participant->status === 'admitted') {
            return redirect()->route('super.live-meet.show', $room->id);
        }

        return back()->with('info', 'Menunggu izin masuk dari host.');
    }

    public function waitingStatus(Request $request, LiveMeetingRoom $room)
    {
        $participant = $room->participants()->where('user_id', $request->user()->id)->first();

        return response()->json([
            'status' => $participant?->status ?? 'unknown',
        ]);
    }

    // ── Host controls: waiting room ───────────────────────────

    public function admit(Request $request, LiveMeetingRoom $room, LiveMeetingParticipant $participant)
    {
        $this->authorizeHostAction($room, $request->user());
        abort_if($participant->room_id !== $room->id, 404);

        $participant->update(['status' => 'admitted', 'joined_at' => now()]);

        return back()->with('success', 'Peserta diizinkan masuk.');
    }

    public function deny(Request $request, LiveMeetingRoom $room, LiveMeetingParticipant $participant)
    {
        $this->authorizeHostAction($room, $request->user());
        abort_if($participant->room_id !== $room->id, 404);

        $participant->update(['status' => 'denied']);

        return back()->with('success', 'Peserta ditolak.');
    }

    // ── Host controls: co-host, mute, kick ────────────────────

    public function promote(Request $request, LiveMeetingRoom $room, LiveMeetingParticipant $participant)
    {
        $this->authorizeHostAction($room, $request->user(), hostOnly: true);
        abort_if($participant->room_id !== $room->id, 404);

        $participant->update(['role' => 'co-host']);

        Notification::send($participant->user_id, 'Anda dijadikan Co-Host', "Anda dijadikan co-host di meeting \"{$room->title}\".", 'success', route('super.live-meet.show', $room->id));

        return back()->with('success', 'Peserta dijadikan co-host.');
    }

    public function demote(Request $request, LiveMeetingRoom $room, LiveMeetingParticipant $participant)
    {
        $this->authorizeHostAction($room, $request->user(), hostOnly: true);
        abort_if($participant->room_id !== $room->id, 404);

        $participant->update(['role' => 'participant']);

        return back()->with('success', 'Status co-host dicabut.');
    }

    public function kick(Request $request, LiveMeetingRoom $room, LiveMeetingParticipant $participant, LiveKitAdminClient $liveKit)
    {
        $this->authorizeHostAction($room, $request->user());
        abort_if($participant->room_id !== $room->id, 404);

        $liveKit->removeParticipant($room->room_name, 'user_' . $participant->user_id);
        $participant->update(['status' => 'left', 'left_at' => now()]);

        return back()->with('success', 'Peserta dikeluarkan dari meeting.');
    }

    public function muteAll(Request $request, LiveMeetingRoom $room, LiveKitAdminClient $liveKit)
    {
        $me = $this->authorizeHostAction($room, $request->user());

        $liveKit->muteAllTracks($room->room_name, ['user_' . $me->user_id]);

        return back()->with('success', 'Semua peserta dibisukan.');
    }

    public function unmuteAll(Request $request, LiveMeetingRoom $room, LiveKitAdminClient $liveKit)
    {
        $me = $this->authorizeHostAction($room, $request->user());

        $liveKit->unmuteAllTracks($room->room_name, ['user_' . $me->user_id]);

        return back()->with('success', 'Semua peserta diizinkan bicara kembali.');
    }

    // ── Recording ──────────────────────────────────────────────

    public function startRecording(Request $request, LiveMeetingRoom $room, LiveKitAdminClient $liveKit)
    {
        $this->authorizeHostAction($room, $request->user());
        abort_if($room->recordings()->where('status', 'recording')->exists(), 422, 'Rekaman sudah berjalan.');

        $filepath = '/out/' . $room->room_name . '_' . now()->format('Ymd_His');
        $egressId = $liveKit->startRoomCompositeEgress($room->room_name, $filepath);

        LiveMeetingRecording::create([
            'room_id'    => $room->id,
            'egress_id'  => $egressId,
            'status'     => 'recording',
            'started_at' => now(),
        ]);

        return back()->with('success', 'Rekaman dimulai.');
    }

    public function stopRecording(Request $request, LiveMeetingRoom $room, LiveKitAdminClient $liveKit)
    {
        $this->authorizeHostAction($room, $request->user());

        $recording = $room->recordings()->where('status', 'recording')->latest()->first();
        abort_unless($recording, 404, 'Tidak ada rekaman yang sedang berjalan.');

        $liveKit->stopEgress($recording->egress_id);
        $recording->update(['status' => 'processing']);

        return back()->with('success', 'Rekaman dihentikan, sedang diproses.');
    }

    // ── End meeting ────────────────────────────────────────────

    public function end(Request $request, LiveMeetingRoom $room, LiveKitAdminClient $liveKit)
    {
        $this->authorizeHostAction($room, $request->user(), hostOnly: true);

        $activeRecording = $room->recordings()->where('status', 'recording')->latest()->first();
        if ($activeRecording) {
            $liveKit->stopEgress($activeRecording->egress_id);
            $activeRecording->update(['status' => 'processing']);
        }

        $liveKit->endRoom($room->room_name);

        $room->update(['status' => 'ended', 'ended_at' => now()]);
        $room->participants()->where('status', 'admitted')->update(['status' => 'left', 'left_at' => now()]);

        return redirect()->route('super.live-meet.index')->with('success', 'Meeting diakhiri untuk semua peserta.');
    }

    // ── Recording download / delete ───────────────────────────

    public function downloadRecording(Request $request, LiveMeetingRecording $recording)
    {
        $user = $request->user();
        $isParticipantHost = $recording->room
            ?->participants()
            ->where('user_id', $user->id)
            ->whereIn('role', ['host', 'co-host'])
            ->exists();
        abort_unless($user->can('access-live-meet') || $isParticipantHost, 403, 'Hanya host/co-host meeting ini yang bisa mengunduh rekaman.');

        abort_unless($recording->status === 'ready' && $recording->file_path, 404, 'Rekaman belum siap.');
        abort_unless(Storage::disk('live_meet_recordings')->exists(basename($recording->file_path)), 404, 'File rekaman tidak ditemukan.');

        $recording->update(['downloaded_at' => now(), 'downloaded_by' => $request->user()->id]);

        return Storage::disk('live_meet_recordings')->download(basename($recording->file_path));
    }

    public function destroyRecording(LiveMeetingRecording $recording)
    {
        if ($recording->file_path && Storage::disk('live_meet_recordings')->exists(basename($recording->file_path))) {
            Storage::disk('live_meet_recordings')->delete(basename($recording->file_path));
        }
        $recording->delete();

        return back()->with('success', 'Rekaman dihapus.');
    }

    // ── Helpers ────────────────────────────────────────────────

    private function authorizeHostAction(LiveMeetingRoom $room, $user, bool $hostOnly = false): LiveMeetingParticipant
    {
        $participant = $room->participants()->where('user_id', $user->id)->where('status', 'admitted')->first();

        abort_if(!$participant, 403, 'Anda bukan peserta aktif meeting ini.');

        if ($hostOnly) {
            abort_if($participant->role !== 'host', 403, 'Hanya host yang bisa melakukan aksi ini.');
        } else {
            abort_if(!$participant->isHostOrCoHost(), 403, 'Hanya host/co-host yang bisa melakukan aksi ini.');
        }

        return $participant;
    }
}
