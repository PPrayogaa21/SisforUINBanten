<?php

namespace App\Http\Controllers\Narasumber;

use App\Http\Controllers\Controller;
use App\Models\KegiatanDokumen;
class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
    
        $kegiatanDiisi = $user->kegiatanSebagaiNarasumber()
            ->orderBy('waktu_mulai', 'desc')
            ->take(5)
            ->get();
    
        $totalKegiatan = $user->kegiatanSebagaiNarasumber()->count();
    
        $kegiatanAktif = $user->kegiatanSebagaiNarasumber()
            ->whereIn('kegiatan.status', ['published', 'ongoing'])
            ->count();
    
        $totalMateri = $user->materiUploaded()->count();
    
    
        $suratTugas = KegiatanDokumen::where('target_user_id', auth()->id())
        ->where('jenis', 'surat_tugas')
        ->with('kegiatan')
        ->latest()
        ->get();
    
        return view('narasumber.dashboard', compact(
            'kegiatanDiisi',
            'totalKegiatan',
            'kegiatanAktif',
            'totalMateri',
            'suratTugas'
        ));
    }
}
