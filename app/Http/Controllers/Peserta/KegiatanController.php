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

    public function downloadMateri(KegiatanMateri $materi)
    {
        return Storage::disk('public')->download($materi->file_path, $materi->judul . '.' . $materi->file_type);
    }
}
