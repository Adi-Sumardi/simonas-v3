<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AlumniLike extends Model
{
    protected $fillable = ['alumni_post_id', 'user_id'];

    public function post(): BelongsTo { return $this->belongsTo(AlumniPost::class, 'alumni_post_id'); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}
