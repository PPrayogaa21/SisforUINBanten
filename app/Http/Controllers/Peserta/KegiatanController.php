<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use App\Models\KegiatanMateri;
use Illuminate\Support\Facades\Storage;

class KegiatanController extends Controller
{
    public function index()
    {
        $kegiatan = auth()->user()->kegiatanSebagaiPeserta()
            ->with('materi', 'narasumber')
            ->orderBy('waktu_mulai', 'desc')
            ->paginate(10);

        return view('peserta.kegiatan.index', compact('kegiatan'));
    }

    public function show(Kegiatan $kegiatan)
    {
        $user = auth()->user();
        $isPeserta = $kegiatan->peserta()->where('user_id', $user->id)->exists();
        if (!$isPeserta) {
            abort(403, 'Anda bukan peserta kegiatan ini.');
        }

        $kegiatan->load([
            'materi.uploader', 
            'narasumber.biodata', 
            'dokumentasi', 
            'dokumen' => function($query) use ($user) {
                $query->where('target_user_id', $user->id);
            }, 
            'kuesioner'
        ]);

        return view('peserta.kegiatan.show', compact('kegiatan'));
    }

    public function downloadMateri($id)
    {
        $materi = \App\Models\KegiatanMateri::findOrFail($id);
    
        if (!Storage::disk('public')->exists($materi->file_path)) {
            abort(404, 'File materi tidak ditemukan.');
        }

        return Storage::disk('public')->download($materi->file_path, $materi->judul . '.' . $materi->file_type);
    }
    
    public function downloadDokumen(Kegiatan $kegiatan, \App\Models\KegiatanDokumen $dokumen)
    {
        $user = auth()->user();
        
        // Pastikan dokumen milik kegiatan ini
        if ($dokumen->kegiatan_id != $kegiatan->id) {
            abort(404, 'Dokumen tidak ditemukan pada kegiatan ini.');
        }

        // Cek akses: User harus target recipient dokumen ini
        if ($dokumen->target_user_id != $user->id) {
            abort(403, 'Anda tidak memiliki akses ke dokumen ini.');
        }

        if (!Storage::disk('public')->exists($dokumen->file_path)) {
            abort(404, 'File dokumen tidak ditemukan.');
        }

        return Storage::disk('public')->download($dokumen->file_path, $dokumen->judul . '.' . pathinfo($dokumen->file_path, PATHINFO_EXTENSION));
    }
    
    ##fungsi absennnn baruuu
    public function absen(Kegiatan $kegiatan)
    {
        $user = auth()->user();
    
        // Cek apakah user peserta kegiatan ini
        if (!$kegiatan->peserta()->where('user_id', $user->id)->exists()) {
            abort(403, 'Kamu bukan peserta kegiatan ini');
        }
    
        // Update status kehadiran
        $kegiatan->peserta()->updateExistingPivot($user->id, [
            'status_kehadiran' => 'hadir'
        ]);
    
        return back()->with('success', 'Berhasil absen');
    }

    public function downloadDokumentasi($id)
    {
        $dokumentasi = \App\Models\KegiatanDokumentasi::findOrFail($id);
    
        if (!Storage::disk('public')->exists($dokumentasi->file_path)) {
            abort(404, 'File foto tidak ditemukan.');
        }

        return Storage::disk('public')->download($dokumentasi->file_path);
    }
}
