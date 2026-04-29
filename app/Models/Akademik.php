<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Akademik extends BaseAktivitas
{
    protected $table = 'akademiks';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $incrementing = true;
}
