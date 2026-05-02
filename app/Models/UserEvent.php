<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserEvent extends Model
{
    protected $fillable = [
        'user_id', 'title', 'date', 'time',
        'type', 'color', 'desc', 'recurring', 'is_mandatory',
        'excluded_dates', 'completed_at_dates', 'completed_at_details',
    ];

    protected $casts = [
        'date'                 => 'date',
        'recurring'            => 'boolean',
        'is_mandatory'         => 'boolean',
        'excluded_dates'       => 'array',
        'completed_at_dates'   => 'array',
        'completed_at_details' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
