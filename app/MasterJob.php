<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MasterJob extends Model
{
    protected $table = 'nama_tabel_master_job';

    // Kolom-kolom yang dapat diisi secara massal
    protected $fillable = [
        'nama_kolom1',
        'nama_kolom2',
        // ...
    ];
}
