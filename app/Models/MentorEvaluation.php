<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MentorEvaluation extends Model
{
    protected $fillable = ['mentor_id', 'mentee_id', 'spiritual', 'community', 'notes'];

    protected $casts = [
        'spiritual' => 'integer',
        'community' => 'integer',
    ];

    public function mentor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mentor_id');
    }

    public function mentee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mentee_id');
    }
}
