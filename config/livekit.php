<?php

return [
    'host' => env('LIVEKIT_HOST', 'https://your-project.livekit.cloud'),
    'api_key' => env('LIVEKIT_API_KEY'),
    'api_secret' => env('LIVEKIT_API_SECRET'),

    // Folder di disk lokal server tempat file rekaman Live Meet superadmin disimpan
    // sebelum otomatis dihapus setelah 7 hari (lihat LiveKitAdminClient & CleanupExpiredLiveMeetRecordings).
    'recordings_path' => env('LIVE_MEET_RECORDINGS_PATH', storage_path('app/live-meet-recordings')),
];
