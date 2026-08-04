<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LiveMeetingRoom extends Model
{
    protected $fillable = [
        'host_id',
        'room_name',
        'title',
        'passcode',
        'status',
        'waiting_room_enabled',
        'started_at',
        'ended_at',
    ];

    protected $casts = [
        'waiting_room_enabled' => 'boolean',
        'started_at'           => 'datetime',
        'ended_at'             => 'datetime',
    ];

    public function host(): BelongsTo
    {
        return $this->belongsTo(User::class, 'host_id');
    }

    public function participants(): HasMany
    {
        return $this->hasMany(LiveMeetingParticipant::class, 'room_id');
    }

    public function recordings(): HasMany
    {
        return $this->hasMany(LiveMeetingRecording::class, 'room_id');
    }
}
