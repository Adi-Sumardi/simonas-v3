<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AlumniSertifikat extends Model
{
    protected $table    = 'alumni_sertifikats';
    protected $fillable = ['user_id', 'tipe', 'nama', 'penerbit', 'tahun', 'nomor_id', 'url'];
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}
