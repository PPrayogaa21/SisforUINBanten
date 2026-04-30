<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'nama',
        'status',
        'ket',
        'tglreg',
        'hak_akses',
        'adalah',
        'email',
        'password',
        'role',
        'biodata_verified',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'biodata_verified' => 'boolean',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function biodata()
    {
        return $this->hasOne(Biodata::class);
    }

    public function kegiatanCreated()
    {
        return $this->hasMany(Kegiatan::class, 'created_by');
    }

    public function kegiatanSebagaiPeserta()
    {
        return $this->belongsToMany(Kegiatan::class, 'kegiatan_peserta')
            ->withPivot('status_kehadiran')
            ->withTimestamps();
    }

    public function kegiatanSebagaiNarasumber()
    {
        return $this->belongsToMany(Kegiatan::class, 'kegiatan_narasumber')
            ->withPivot('topik_materi')
            ->withTimestamps();
    }

    public function materiUploaded()
    {
        return $this->hasMany(KegiatanMateri::class, 'uploaded_by');
    }

    public function kuesionerResponses()
    {
        return $this->hasMany(KuesionerResponse::class);
    }
}
