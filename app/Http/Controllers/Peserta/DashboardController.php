<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $kegiatanDiikuti = $user->kegiatanSebagaiPeserta()
            ->orderBy('waktu_mulai', 'desc')
            ->take(5)
            ->get();

        $totalKegiatanDiikuti = $user->kegiatanSebagaiPeserta()->count();
        $kegiatanAktif = $user->kegiatanSebagaiPeserta()
            ->whereIn('kegiatan.status', ['published', 'ongoing'])
            ->count();
        $kegiatanSelesai = $user->kegiatanSebagaiPeserta()
            ->where('kegiatan.status', 'completed')
            ->count();

        return view('peserta.dashboard', compact(
            'kegiatanDiikuti',
            'totalKegiatanDiikuti',
            'kegiatanAktif',
            'kegiatanSelesai'
        ));
    }
}
