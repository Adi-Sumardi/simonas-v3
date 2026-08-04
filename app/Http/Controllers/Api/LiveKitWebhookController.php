<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LiveMeetingRecording;
use App\Models\LiveMeetingRoom;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Receives LiveKit server webhook events (egress_ended, room_finished, ...).
 *
 * LiveKit signs the request with an `Authorization: Bearer <jwt>` header where
 * the JWT's `sha256` claim is the base64 hash of the raw request body — this
 * verifies both the sender and payload integrity without a shared secret header.
 */
class LiveKitWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $rawBody = $request->getContent();

        if (!$this->verifySignature($request, $rawBody)) {
            Log::warning('LiveKit webhook: invalid signature, ignoring.');
            return response()->json(['error' => 'invalid signature'], 401);
        }

        $payload = json_decode($rawBody, true) ?? [];
        $event = $payload['event'] ?? null;

        Log::info('LiveKit webhook received', ['event' => $event]);

        match ($event) {
            'egress_ended'  => $this->handleEgressEnded($payload),
            'room_finished' => $this->handleRoomFinished($payload),
            default         => null,
        };

        return response()->json(['ok' => true]);
    }

    private function verifySignature(Request $request, string $rawBody): bool
    {
        $authHeader = $request->header('Authorization', '');
        $token = str_starts_with($authHeader, 'Bearer ') ? substr($authHeader, 7) : $authHeader;

        if (empty($token)) {
            return false;
        }

        try {
            $secret = config('livekit.api_secret');
            $decoded = (array) JWT::decode($token, new Key($secret, 'HS256'));
        } catch (\Throwable $e) {
            Log::warning('LiveKit webhook JWT decode failed: ' . $e->getMessage());
            return false;
        }

        $expectedHash = base64_encode(hash('sha256', $rawBody, true));

        return isset($decoded['sha256']) && hash_equals($expectedHash, $decoded['sha256']);
    }

    private function handleEgressEnded(array $payload): void
    {
        $info = $payload['egressInfo'] ?? $payload['egress_info'] ?? [];
        $egressId = $info['egressId'] ?? $info['egress_id'] ?? null;

        if (!$egressId) {
            Log::warning('LiveKit webhook egress_ended: missing egressId', $payload);
            return;
        }

        $recording = LiveMeetingRecording::where('egress_id', $egressId)->first();
        if (!$recording) {
            Log::warning("LiveKit webhook egress_ended: no matching recording for egress {$egressId}");
            return;
        }

        $status = $info['status'] ?? null;
        $failed = is_string($status) && str_contains($status, 'FAILED');

        $fileResults = $info['fileResults'] ?? $info['file_results'] ?? [];
        $firstFile = $fileResults[0] ?? null;
        $filename = $firstFile['filename'] ?? null;
        $durationNs = $firstFile['duration'] ?? null;

        if ($failed || !$filename) {
            $recording->update(['status' => 'failed']);
            Log::warning("LiveKit egress {$egressId} failed or produced no file.", $info);
            return;
        }

        $recording->update([
            'status'           => 'ready',
            'file_path'        => $filename,
            'duration_seconds' => $durationNs ? (int) round($durationNs / 1_000_000_000) : null,
            'ended_at'         => now(),
            'expires_at'       => now()->addDays(7),
        ]);
    }

    private function handleRoomFinished(array $payload): void
    {
        $room = $payload['room'] ?? [];
        $roomName = $room['name'] ?? null;

        if (!$roomName) {
            return;
        }

        LiveMeetingRoom::where('room_name', $roomName)
            ->where('status', '!=', 'ended')
            ->update(['status' => 'ended', 'ended_at' => now()]);
    }
}
