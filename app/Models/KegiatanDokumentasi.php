<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KegiatanDokumentasi extends Model
{
    protected $table = 'kegiatan_dokumentasi';

    protected $fillable = [
        'kegiatan_id',
        'caption',
        'file_path',
    ];

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class);
    }
}
