<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KuesionerPertanyaan extends Model
{
    protected $table = 'kuesioner_pertanyaan';

    protected $fillable = [
        'kuesioner_id',
        'pertanyaan',
        'tipe',
        'opsi',
        'urutan',
    ];

    protected function casts(): array
    {
        return [
            'opsi' => 'array',
        ];
    }

    public function kuesioner()
    {
        return $this->belongsTo(Kuesioner::class);
    }

    public function jawaban()
    {
        return $this->hasMany(KuesionerJawaban::class, 'pertanyaan_id');
    }
}
