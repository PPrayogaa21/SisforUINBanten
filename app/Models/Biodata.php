<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Biodata extends Model
{
    protected $table = 'biodata';

    protected $fillable = [
        'user_id',
        'nip',
        'nama_lengkap',
        'jabatan',
        'unit_kerja',
        'pangkat_golongan',
        'no_hp',
        'alamat',
        'foto',
        'from_api',
    ];

    protected function casts(): array
    {
        return [
            'from_api' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
