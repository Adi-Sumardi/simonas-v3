<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;

class AlumniStory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'image_path',
        'caption',
        'background_color',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    /** Aktif (belum expired). */
    public function scopeActive($query)
    {
        return $query->where('expires_at', '>', now());
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** User yang sudah lihat story ini. */
    public function viewers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'alumni_story_views', 'story_id', 'user_id')
            ->withPivot('viewed_at');
    }

    public function getImageUrlAttribute(): string
    {
        return Storage::url($this->image_path);
    }
}
