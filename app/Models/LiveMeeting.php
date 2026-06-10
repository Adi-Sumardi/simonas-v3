<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LiveMeeting extends Model
{
    protected $fillable = ['mentor_id', 'room_name', 'is_active', 'started_at', 'ended_at'];
    
    protected $casts = [
        'is_active' => 'boolean',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    /**
     * Get the mentor who host the live meeting.
     */
    public function mentor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mentor_id');
    }
}
