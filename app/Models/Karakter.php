<?php

namespace App\Models;

class Karakter extends BaseAktivitas
{
    protected $table = 'karakters';
    protected $primaryKey = 'id';
    protected $keyType = 'bigint';
    public $incrementing = true;
}
