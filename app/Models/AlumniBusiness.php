<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlumniBusiness extends Model
{
    protected $fillable = [
        'alumni_id',
        'company_name', 
        'business_type',
        'description',
        'logo',
        'address',
        'latitude',
        'longitude',
        'website',
        'phone',
        'email',
        'social_media',
        'is_active'
    ];

    protected $casts = [
        'social_media' => 'json',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'is_active' => 'boolean'
    ];

    public function alumni()
    {
        return $this->belongsTo(User::class, 'alumni_id');
    }
}
