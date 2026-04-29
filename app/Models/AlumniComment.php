<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AlumniComment extends Model
{
    protected $table = 'alumni_comments';
    protected $primaryKey = 'alumni_comment_id';

    protected $fillable = [
        'alumni_post_id',
        'alumni_user_id',
        'alumni_comment_content',
        'alumni_parent_id'
    ];

    protected $with = ['author'];

    public function post(): BelongsTo
    {
        return $this->belongsTo(AlumniPost::class, 'alumni_post_id', 'alumni_post_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'alumni_user_id', 'id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(AlumniComment::class, 'alumni_parent_id', 'alumni_comment_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(AlumniComment::class, 'alumni_parent_id', 'alumni_comment_id');
    }
} 