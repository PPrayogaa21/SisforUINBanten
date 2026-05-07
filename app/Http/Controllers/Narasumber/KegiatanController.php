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

        $kegiatan->load([
            'peserta.biodata', 
            'materi.uploader', 
            'dokumentasi',
            'dokumen' => function($query) use ($user) {
                $query->where('target_user_id', $user->id);
            }
        ]);

        return view('narasumber.kegiatan.show', compact('kegiatan'));
    }

    public function uploadMateri(Request $request, Kegiatan $kegiatan)
    {
        $user = auth()->user();
    
        // cek akses
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

        if (!\Illuminate\Support\Facades\Storage::disk('public')->exists($dokumen->file_path)) {
            abort(404, 'File dokumen tidak ditemukan.');
        }

        return \Illuminate\Support\Facades\Storage::disk('public')->download($dokumen->file_path, $dokumen->judul . '.' . pathinfo($dokumen->file_path, PATHINFO_EXTENSION));
    }
}
