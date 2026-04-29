<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormAkses extends Model
{
    protected $fillable = [
        'name', 
        'email', 
        'no_wa', 
        'asrama', 
        'status',
    ];
}
