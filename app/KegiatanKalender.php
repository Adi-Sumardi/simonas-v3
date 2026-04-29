<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KegiatanKalender extends Model
{
    protected $fillable = ['title', 'start_date', 'end_date'];
}
