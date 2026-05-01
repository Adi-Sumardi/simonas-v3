<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AlumniJob extends Model
{
    protected $fillable = ['user_id', 'type', 'title', 'company', 'location', 'work_type', 'description', 'requirements', 'salary_range', 'contact_info', 'deadline', 'is_active'];

    protected $casts = ['is_active' => 'boolean', 'deadline' => 'date'];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}
