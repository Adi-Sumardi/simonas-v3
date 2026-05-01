<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AlumniPost extends Model
{
    protected $fillable = ['user_id', 'type', 'title', 'content', 'image_url', 'likes_count', 'comments_count', 'is_pinned'];

    protected $casts = ['is_pinned' => 'boolean', 'likes_count' => 'integer', 'comments_count' => 'integer'];

    public function user(): BelongsTo      { return $this->belongsTo(User::class); }
    public function comments(): HasMany    { return $this->hasMany(AlumniComment::class); }
    public function likes(): HasMany       { return $this->hasMany(AlumniLike::class); }
}