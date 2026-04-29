<?php

namespace App\Http\Controllers\Narasumber;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use App\Models\KegiatanMateri;
use Illuminate\Http\Request;

class KegiatanController extends Controller
{
    public function index()
    {
        $kegiatan = auth()->user()->kegiatanSebagaiNarasumber()
            ->with('peserta', 'materi')
            ->orderBy('waktu_mulai', 'desc')
            ->paginate(10);

        return view('narasumber.kegiatan.index', compact('kegiatan'));
    }

    public function show(Kegiatan $kegiatan)
    {
        $user = auth()->user();
        if (!$kegiatan->narasumber()->where('user_id', $user->id)->exists()) {
            abort(403);
        }

        $kegiatan->load('peserta.biodata', 'materi.uploader', 'dokumentasi');

        return view('narasumber.kegiatan.show', compact('kegiatan'));
    }

    public function uploadMateri(Request $request, Kegiatan $kegiatan)
    {
        $user = auth()->user();
    
        // 🔒 WAJIB: cek akses
        if (!$kegiatan->narasumber()->where('user_id', $user->id)->exists()) {
            abort(403);
        }
    
        $request->validate([
            'judul' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip|max:20480',
        ]);
    
        $file = $request->file('file');
        $path = $file->store('kegiatan/materi', 'public');
    
        KegiatanMateri::create([
            'kegiatan_id' => $kegiatan->id,
            'uploaded_by' => auth()->id(),
            'judul' => $request->judul,
            'file_path' => $path,
            'file_type' => $file->getClientOriginalExtension(),
            'file_size' => $file->getSize(),
        ]);
    
        return back()->with('success', 'Materi berhasil diupload.');
    }
}
