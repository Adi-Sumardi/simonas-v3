<?php

namespace App\Services;

use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Server-to-server client for LiveKit's RoomService & Egress Twirp APIs.
 *
 * Admin tokens minted here are never sent to the browser — every host/co-host
 * action (mute-all, kick, recording) is authorized by checking the caller's
 * role in `live_meeting_participants` first, then this service performs the
 * privileged LiveKit call using the server's own credentials.
 */
class LiveKitAdminClient
{
    private function baseUrl(): string
    {
        $host = config('livekit.host');
        // Twirp REST calls go over http(s), while the client SDK connects over ws(s).
        return str_replace(['wss://', 'ws://'], ['https://', 'http://'], rtrim($host, '/'));
    }

    private function adminToken(array $videoGrants = []): string
    {
        $apiKey = config('livekit.api_key');
        $apiSecret = config('livekit.api_secret');

        if (empty($apiKey) || empty($apiSecret)) {
            throw new \RuntimeException('Kredensial LiveKit (API Key/Secret) belum diatur di file .env');
        }

        $issuedAt = time();

        $payload = [
            'iss' => $apiKey,
            'sub' => 'live-meet-admin',
            'nbf' => $issuedAt,
            'exp' => $issuedAt + 60,
            'video' => array_merge([
                'roomList'   => true,
                'roomCreate' => true,
                'roomAdmin'  => true,
                'roomRecord' => true,
            ], $videoGrants),
        ];

        return JWT::encode($payload, $apiSecret, 'HS256');
    }

    private function call(string $service, string $method, array $body, array $videoGrants = []): array
    {
        $url = "{$this->baseUrl()}/twirp/livekit.{$service}/{$method}";

        $response = Http::withToken($this->adminToken($videoGrants))
            ->acceptJson()
            ->post($url, $body);

        if ($response->failed()) {
            Log::warning("LiveKit {$service}.{$method} failed", [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);
            throw new \RuntimeException("LiveKit {$method} gagal: " . $response->body());
        }

        return $response->json() ?? [];
    }

    public function listParticipants(string $room): array
    {
        return $this->call('RoomService', 'ListParticipants', ['room' => $room])['participants'] ?? [];
    }

    /**
     * Mute every published audio track in the room, optionally skipping some identities
     * (e.g. the host/co-host triggering the action).
     */
    public function muteAllTracks(string $room, array $exceptIdentities = []): void
    {
        foreach ($this->listParticipants($room) as $participant) {
            if (in_array($participant['identity'] ?? null, $exceptIdentities, true)) {
                continue;
            }
            foreach ($participant['tracks'] ?? [] as $track) {
                $this->call('RoomService', 'MutePublishedTrack', [
                    'room'      => $room,
                    'identity'  => $participant['identity'],
                    'track_sid' => $track['sid'],
                    'muted'     => true,
                ]);
            }
        }
    }

    public function unmuteAllTracks(string $room, array $exceptIdentities = []): void
    {
        foreach ($this->listParticipants($room) as $participant) {
            if (in_array($participant['identity'] ?? null, $exceptIdentities, true)) {
                continue;
            }
            foreach ($participant['tracks'] ?? [] as $track) {
                $this->call('RoomService', 'MutePublishedTrack', [
                    'room'      => $room,
                    'identity'  => $participant['identity'],
                    'track_sid' => $track['sid'],
                    'muted'     => false,
                ]);
            }
        }
    }

    public function removeParticipant(string $room, string $identity): void
    {
        $this->call('RoomService', 'RemoveParticipant', ['room' => $room, 'identity' => $identity]);
    }

    public function endRoom(string $room): void
    {
        $this->call('RoomService', 'DeleteRoom', ['room' => $room]);
    }

    /**
     * Start a room-composite recording. Returns the LiveKit egress_id.
     */
    public function startRoomCompositeEgress(string $room, string $filepath): string
    {
        $result = $this->call('Egress', 'StartRoomCompositeEgress', [
            'room_name' => $room,
            'layout'    => 'grid',
            'file_outputs' => [
                ['filepath' => $filepath],
            ],
        ], ['roomRecord' => true]);

        if (empty($result['egress_id'])) {
            throw new \RuntimeException('LiveKit tidak mengembalikan egress_id: ' . json_encode($result));
        }

        return $result['egress_id'];
    }

    public function stopEgress(string $egressId): void
    {
        $this->call('Egress', 'StopEgress', ['egress_id' => $egressId], ['roomRecord' => true]);
    }
}
