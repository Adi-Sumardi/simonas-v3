<?php

namespace App\Services;

use Firebase\JWT\JWT;

class LiveKitTokenService
{
    /**
     * Generate a client-facing access token for a LiveKit room. This token goes
     * straight into the browser (LiveKit client SDK), so it never carries the
     * `roomAdmin` grant — privileged RoomService calls (mute/kick/end room,
     * recording) are always performed server-side via LiveKitAdminClient using
     * its own short-lived admin token instead. See LiveKitAdminClient's class doc.
     *
     * @param string $roomName Name of the room to join
     * @param string $identity Unique identity of the user
     * @param string $name Display name of the user
     * @param bool $canCreateRoom Whether this identity may create the room if it doesn't exist yet (the session host)
     * @return string Signed JWT token
     */
    public function generateToken(string $roomName, string $identity, string $name, bool $canCreateRoom = false): string
    {
        $apiKey = config('livekit.api_key');
        $apiSecret = config('livekit.api_secret');

        if (empty($apiKey) || empty($apiSecret)) {
            throw new \Exception('Kredensial LiveKit (API Key/Secret) belum diatur di file .env');
        }

        $issuedAt = time();
        $expireAt = $issuedAt + 7200; // valid selama 2 jam

        $payload = [
            'iss' => $apiKey,
            'sub' => $identity,
            'nbf' => $issuedAt,
            'exp' => $expireAt,
            'name' => $name,
            'video' => [
                'roomCreate' => $canCreateRoom,
                'roomJoin'   => true,
                'room'       => $roomName,
                'publisher'  => true,
                'subscriber' => true,
                'canPublish' => true,
                'canSubscribe' => true,
            ],
        ];

        return JWT::encode($payload, $apiSecret, 'HS256');
    }
}
