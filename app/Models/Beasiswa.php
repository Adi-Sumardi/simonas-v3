<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Beasiswa extends Model
{
    protected $fillable = [
        'user_id',
        'mentor_id',
        'created_by',
        'nama_beasiswa',
        'sumber',
        'nominal',
        'tanggal_diajukan',
        'tanggal_diterima',
        'status',
        'catatan',
    ];

    protected $casts = [
        'nominal' => 'decimal:2',
        'tanggal_diajukan' => 'date',
        'tanggal_diterima' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function mentor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mentor_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
