<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LiveMeetingRecording extends Model
{
    protected $fillable = [
        'room_id',
        'egress_id',
        'file_path',
        'status',
        'duration_seconds',
        'started_at',
        'ended_at',
        'expires_at',
        'reminder_sent_at',
        'downloaded_at',
        'downloaded_by',
    ];

    protected $casts = [
        'started_at'       => 'datetime',
        'ended_at'         => 'datetime',
        'expires_at'       => 'datetime',
        'reminder_sent_at' => 'datetime',
        'downloaded_at'    => 'datetime',
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(LiveMeetingRoom::class, 'room_id');
    }

    public function downloader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'downloaded_by');
    }
}
