<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KuesionerResponse extends Model
{
    protected $table = 'kuesioner_response';

    protected $fillable = [
        'kuesioner_id',
        'user_id',
    ];

    public function kuesioner()
    {
        return $this->belongsTo(Kuesioner::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jawaban()
    {
        return $this->hasMany(KuesionerJawaban::class, 'response_id');
    }
}
