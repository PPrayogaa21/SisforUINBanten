<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kuesioner extends Model
{
    protected $table = 'kuesioner';

    protected $fillable = [
        'kegiatan_id',
        'judul',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class);
    }

    public function pertanyaan()
    {
        return $this->hasMany(KuesionerPertanyaan::class)->orderBy('urutan');
    }

    public function responses()
    {
        return $this->hasMany(KuesionerResponse::class);
    }
}
