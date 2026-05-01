<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AlumniComment extends Model
{
    // Use our new standard schema (alumni_comments table with id, alumni_post_id, user_id, content)
    protected $fillable = ['alumni_post_id', 'user_id', 'content'];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function post(): BelongsTo { return $this->belongsTo(AlumniPost::class, 'alumni_post_id'); }
}