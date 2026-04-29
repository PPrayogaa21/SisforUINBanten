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

        $kegiatan->load('materi.uploader', 'narasumber.biodata', 'dokumentasi', 'dokumen', 'kuesioner');

        return view('peserta.kegiatan.show', compact('kegiatan'));
    }

    public function downloadMateri($id)
    {
        $materi = \App\Models\KegiatanMateri::findOrFail($id);
    
        return response()->download(
            storage_path('app/public/' . $materi->file_path)
        );
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


}
