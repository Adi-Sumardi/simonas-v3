<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AlumniPendidikanBaru extends Model
{
    protected $table    = 'alumni_pendidikans';
    protected $fillable = ['user_id', 'jenjang', 'institusi', 'jurusan', 'tahun_masuk', 'tahun_lulus', 'is_current'];
    protected $casts    = ['is_current' => 'boolean'];
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}
