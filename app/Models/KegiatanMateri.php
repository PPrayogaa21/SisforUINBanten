<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KegiatanMateri extends Model
{
    protected $table = 'kegiatan_materi';

    protected $fillable = [
        'kegiatan_id',
        'uploaded_by',
        'judul',
        'file_path',
        'file_type',
        'file_size',
    ];

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getFileSizeFormattedAttribute(): string
    {
        $bytes = $this->file_size;
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        }
        if ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        }
        return $bytes . ' B';
    }
}
