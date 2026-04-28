<?php

namespace App\Http\Controllers\Narasumber;

use App\Http\Controllers\Controller;

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

        return view('narasumber.dashboard', compact(
            'kegiatanDiisi', 'totalKegiatan', 'kegiatanAktif', 'totalMateri'
        ));
    }
}
