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
        'email',
        'email_verified_at',
        'jabatan',
        'bagian',
        'pangkat_golongan',
        'no_hp',
        'alamat',
        'foto',
        'from_api',
        'adalah',
        'ket',
        'tgl_bergabung',
    ];

    protected function casts(): array
    {
        return [
            'from_api' => 'boolean',
            'email_verified_at' => 'datetime',
            'tgl_bergabung' => 'date',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
