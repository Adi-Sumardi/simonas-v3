<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlumniPost extends Model
{
    protected $table = 'alumni_posts';
    protected $primaryKey = 'alumni_post_id';

    protected $fillable = [
        'alumni_user_id',
        'alumni_post_title',
        'alumni_post_slug',
        'alumni_post_content',
        'alumni_post_type',
        'alumni_post_status',
        'alumni_post_thumbnail',
        'alumni_post_views',
        'alumni_post_comments_count'
    ];

    protected $casts = [
        'alumni_post_views' => 'integer',
        'alumni_post_comments_count' => 'integer'
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'alumni_user_id');
    }

    public function categories()
    {
        return $this->belongsToMany(
            AlumniCategory::class,
            'alumni_post_categories',
            'alumni_post_id',
            'alumni_category_id'
        );
    }

    public function comments()
    {
        return $this->hasMany(AlumniComment::class, 'alumni_post_id', 'alumni_post_id');
    }

    public function jobDetail()
    {
        return $this->hasOne(AlumniJobDetail::class, 'alumni_post_id', 'alumni_post_id');
    }

    public function attachments()
    {
        return $this->hasMany(AlumniAttachment::class, 'alumni_post_id');
    }
} 