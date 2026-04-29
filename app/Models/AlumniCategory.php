<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlumniCategory extends Model
{
    protected $table = 'alumni_categories';
    protected $primaryKey = 'alumni_category_id';

    protected $fillable = [
        'alumni_category_name',
        'alumni_category_slug'
    ];

    public function posts()
    {
        return $this->belongsToMany(
            AlumniPost::class,
            'alumni_post_categories',
            'alumni_category_id',
            'alumni_post_id'
        );
    }
} 