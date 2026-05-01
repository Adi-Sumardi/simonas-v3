<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AlumniPekerjaanBaru extends Model
{
    protected $table    = 'alumni_pekerjaans';
    protected $fillable = ['user_id', 'jabatan', 'perusahaan', 'lokasi', 'tahun_mulai', 'tahun_selesai', 'is_current', 'deskripsi'];
    protected $casts    = ['is_current' => 'boolean'];
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}
