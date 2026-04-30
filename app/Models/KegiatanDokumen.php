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

    public function targetUser()
    {
        return $this->belongsTo(User::class, 'target_user_id');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
