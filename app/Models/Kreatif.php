<?php

namespace App\Models;

class Kreatif extends BaseAktivitas
{
    protected $table = 'kreatifs';
    protected $primaryKey = 'id';
    protected $keyType = 'bigint';
    public $incrementing = true;
}
