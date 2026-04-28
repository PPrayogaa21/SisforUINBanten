<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KegiatanNarasumber extends Model
{
    protected $table = 'kegiatan_narasumber';

    protected $fillable = [
        'kegiatan_id',
        'user_id',
        'topik_materi',
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
