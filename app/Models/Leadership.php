<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Leadership extends Model
{
    protected $fillable = [
        'user_id', 
        'komponen_id', 
        'nama_warga',
        'komponen',
        'asrama',
        'kegiatan',
        'waktu',
        'tempat',
        'keterangan',
        'file',
        'file_data',
        'file_mime',
        'file_size',
        'nama_penilai',
        'nilai',
        'tipe_kegiatan'
    ];

    protected $hidden = ['file_data'];

    public function getFileDataAttribute($value)
    {
        if (! is_resource($value)) return $value;
        rewind($value);
        return stream_get_contents($value);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function komponen()
    {
        return $this->belongsTo(Komponen::class, 'komponen_id');
    }
}
