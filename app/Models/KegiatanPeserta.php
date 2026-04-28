<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KegiatanPeserta extends Model
{
    protected $table = 'kegiatan_peserta';

    protected $fillable = [
        'kegiatan_id',
        'user_id',
        'status_kehadiran',
    ];

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
