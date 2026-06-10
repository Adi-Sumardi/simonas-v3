<?php

namespace App\Services;

use Firebase\JWT\JWT;

class LiveKitTokenService
{
    /**
     * Generate an access token for a LiveKit room.
     *
     * @param string $roomName Name of the room to join
     * @param string $identity Unique identity of the user
     * @param string $name Display name of the user
     * @param bool $isAdmin Whether the user is the room creator/admin
     * @return string Signed JWT token
     */
    public function generateToken(string $roomName, string $identity, string $name, bool $isAdmin = false): string
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
                'roomCreate' => $isAdmin,
                'roomJoin'   => true,
                'room'       => $roomName,
                'publisher'  => true,
                'subscriber' => true,
                'canPublish' => true,
                'canSubscribe' => true,
                'roomAdmin'  => $isAdmin,
            ],
        ];

        return JWT::encode($payload, $apiSecret, 'HS256');
    }
}
