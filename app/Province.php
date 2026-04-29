<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $fillable = [
        'id', 'name'
    ];

    public function alumni()
    {
        return $this->hasMany(Alumni::class, 'id_province', 'id');
    }
}
