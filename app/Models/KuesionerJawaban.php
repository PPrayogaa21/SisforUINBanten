<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KuesionerJawaban extends Model
{
    protected $table = 'kuesioner_jawaban';

    protected $fillable = [
        'response_id',
        'pertanyaan_id',
        'jawaban',
    ];

    public function response()
    {
        return $this->belongsTo(KuesionerResponse::class, 'response_id');
    }

    public function pertanyaan()
    {
        return $this->belongsTo(KuesionerPertanyaan::class, 'pertanyaan_id');
    }
}
