<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserEvent extends Model
{
    protected $fillable = [
        'user_id', 'title', 'date', 'time',
        'type', 'color', 'desc', 'recurring',
    ];

    protected $casts = [
        'date'      => 'date',
        'recurring' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
