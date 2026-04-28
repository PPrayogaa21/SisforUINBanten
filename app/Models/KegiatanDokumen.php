<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KegiatanDokumen extends Model
{
    protected $table = 'kegiatan_dokumen';

    protected $fillable = [
        'kegiatan_id',
        'jenis',
        'judul',
        'file_path',
    ];

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class);
    }
}
