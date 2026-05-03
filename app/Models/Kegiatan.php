<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    protected $table = 'kegiatan';

    protected $fillable = [
        'created_by',
        'nama_kegiatan',
        'jenis',
        'deskripsi',
        'waktu_mulai',
        'waktu_selesai',
        'tempat',
        'latitude',
        'longitude',
        'alamat_lengkap',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'waktu_mulai' => 'datetime',
            'waktu_selesai' => 'datetime',
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
        ];
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function peserta()
    {
        return $this->belongsToMany(User::class, 'kegiatan_peserta')
            ->withPivot('status_kehadiran')
            ->withTimestamps();
    }

    public function narasumber()
    {
        return $this->belongsToMany(User::class, 'kegiatan_narasumber')
            ->withPivot('topik_materi')
            ->withTimestamps();
    }

    public function materi()
    {
        return $this->hasMany(KegiatanMateri::class, 'kegiatan_id');
    }

    public function dokumentasi()
    {
        return $this->hasMany(\App\Models\KegiatanDokumentasi::class);
    }

    public function dokumen()
    {
        return $this->hasMany(KegiatanDokumen::class, 'kegiatan_id');
    }

    public function kuesioner()
    {
        return $this->hasMany(Kuesioner::class, 'kegiatan_id');
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'bg-gray-100 text-gray-800',
            'published' => 'bg-blue-100 text-blue-800',
            'ongoing' => 'bg-yellow-100 text-yellow-800',
            'completed' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getJenisBadgeAttribute(): string
    {
        return match ($this->jenis) {
            'rapat' => 'bg-purple-100 text-purple-800',
            'seminar' => 'bg-indigo-100 text-indigo-800',
            'pelatihan' => 'bg-cyan-100 text-cyan-800',
            'workshop' => 'bg-teal-100 text-teal-800',
            'lainnya' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
    public function isPeserta($userId)
    {
        return $this->peserta()->where('user_id', $userId)->exists();
    }

    public function isNarasumber($userId)
    {
        return $this->narasumber()->where('user_id', $userId)->exists();
    }
}
