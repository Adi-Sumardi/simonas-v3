<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramKerja extends Model
{
    protected $fillable = [
        'asrama',
        'nama_program',
        'deskripsi',
        'tahun',
        'semester',
        'status',
        'tanggal_mulai',
        'tanggal_selesai',
        'penanggung_jawab',
        'created_by',
    ];

    protected $casts = [
        'tanggal_mulai'   => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
