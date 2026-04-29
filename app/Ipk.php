<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ipk extends Model
{
    protected $fillable = [
        'user_id', 
        'ip', 
        'tahun', 
        'semester', 
        'file',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
