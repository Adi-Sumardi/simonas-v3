<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LiveMeetingParticipant extends Model
{
    protected $fillable = [
        'room_id',
        'user_id',
        'role',
        'status',
        'invited_by',
        'joined_at',
        'left_at',
    ];

    protected $casts = [
        'joined_at' => 'datetime',
        'left_at'   => 'datetime',
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(LiveMeetingRoom::class, 'room_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function inviter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'invited_by');
    }

    public function isHostOrCoHost(): bool
    {
        return in_array($this->role, ['host', 'co-host'], true);
    }
}
