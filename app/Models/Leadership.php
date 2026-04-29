<?php

namespace App\Models;

class Leadership extends BaseAktivitas
{
    protected $table = 'leaderships';
    protected $primaryKey = 'id';
    protected $keyType = 'bigint';
    public $incrementing = true;
}
