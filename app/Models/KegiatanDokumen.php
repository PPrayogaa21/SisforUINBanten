<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KegiatanDokumen extends Model
{
    protected $table = 'kegiatan_dokumen';


    protected $fillable = [
        'kegiatan_id',
        'judul',
        'jenis',
        'file_path',
        'user_id',
        'target_user_id'
    ];
    
    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class);
    }
}
