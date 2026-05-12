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
            ->whereIn('status', ['published', 'ongoing', 'completed'])
            ->with('materi', 'narasumber')
            ->orderBy('waktu_mulai', 'desc')
            ->paginate(10);

        return view('peserta.kegiatan.index', compact('kegiatan'));
    }

    public function show(Kegiatan $kegiatan)
    {
        // Only allow access to published, ongoing, or completed activities
        if (in_array($kegiatan->status, ['draft', 'cancelled'])) {
            abort(403, 'Kegiatan ini tidak tersedia.');
        }

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
    
    public function viewDokumen(Kegiatan $kegiatan, \App\Models\KegiatanDokumen $dokumen)
    {
        $user = auth()->user();
        
        if ($dokumen->kegiatan_id != $kegiatan->id) {
            abort(404, 'Dokumen tidak ditemukan pada kegiatan ini.');
        }

        if ($dokumen->target_user_id != $user->id) {
            abort(403, 'Anda tidak memiliki akses ke dokumen ini.');
        }

        // Cek kehadiran peserta
        $peserta = $kegiatan->peserta()->where('user_id', $user->id)->first();
        if (!$peserta || $peserta->pivot->status_kehadiran !== 'hadir') {
            abort(403, 'Anda harus mengonfirmasi kehadiran terlebih dahulu untuk dapat melihat dokumen ini.');
        }

        $path = Storage::disk('public')->path($dokumen->file_path);
        
        if (!file_exists($path)) {
            abort(404, 'File dokumen tidak ditemukan di server.');
        }

        return response()->file($path);
    }
    
    public function downloadDokumen(Kegiatan $kegiatan, \App\Models\KegiatanDokumen $dokumen)
    {
        $user = auth()->user();
        
        if ($dokumen->kegiatan_id != $kegiatan->id) {
            abort(404, 'Dokumen tidak ditemukan pada kegiatan ini.');
        }

        if ($dokumen->target_user_id != $user->id) {
            abort(403, 'Anda tidak memiliki akses ke dokumen ini.');
        }

        // Cek kehadiran peserta
        $peserta = $kegiatan->peserta()->where('user_id', $user->id)->first();
        if (!$peserta || $peserta->pivot->status_kehadiran !== 'hadir') {
            abort(403, 'Anda harus mengonfirmasi kehadiran terlebih dahulu untuk dapat mengunduh dokumen ini.');
        }

        $path = Storage::disk('public')->path($dokumen->file_path);
        
        if (!file_exists($path)) {
            abort(404, 'File dokumen tidak ditemukan di server.');
        }

        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $filename = str_replace(['/', '\\', ':', '*', '?', '"', '<', '>', '|'], '_', $dokumen->judul) . '.' . $ext;

        return response()->download($path, $filename);
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

    public function join(Kegiatan $kegiatan)
    {
        $user = auth()->user();

        // Check if the user is a peserta
        if ($user->role !== 'peserta') {
            abort(403, 'Hanya peserta yang dapat bergabung.');
        }

        // Check if biodata is verified
        if (!$user->biodata_verified) {
            return redirect()->route('biodata.create')
                ->with('warning', 'Silakan lengkapi biodata Anda terlebih dahulu sebelum bergabung.');
        }

        // Check if already joined
        if ($kegiatan->peserta()->where('user_id', $user->id)->exists()) {
            return redirect()->route('peserta.kegiatan.show', $kegiatan->id)
                ->with('info', 'Anda sudah bergabung dengan kegiatan ini.');
        }

        // Join
        $kegiatan->peserta()->attach($user->id, ['status_kehadiran' => 'belum']);

        return redirect()->route('peserta.kegiatan.show', $kegiatan->id)
            ->with('success', 'Berhasil bergabung dengan kegiatan.');
    }
}
