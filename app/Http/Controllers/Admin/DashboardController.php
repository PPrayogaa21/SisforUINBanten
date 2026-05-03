<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use App\Models\User;
use App\Models\KuesionerResponse;

class DashboardController extends Controller
{
    public function index()
    {
        $totalKegiatan = Kegiatan::count();
        $kegiatanAktif = Kegiatan::whereIn('status', ['published', 'ongoing'])->count();
        $kegiatanSelesai = Kegiatan::where('status', 'completed')->count();
        $totalPengguna = User::where('role', 'user')->count();
        $totalResponKuesioner = KuesionerResponse::count();

        $kegiatanTerbaru = Kegiatan::with(['creator', 'peserta', 'narasumber'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        $kegiatanBulanIni = Kegiatan::whereMonth('waktu_mulai', now()->month)
            ->whereYear('waktu_mulai', now()->year)
            ->count();

        // Chart data - kegiatan per bulan (6 bulan terakhir)
        $chartData = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $count = Kegiatan::whereMonth('waktu_mulai', $date->month)
                ->whereYear('waktu_mulai', $date->year)
                ->count();
            $chartData[] = [
                'bulan' => $date->translatedFormat('M Y'),
                'jumlah' => $count,
            ];
        }

        return view('admin.dashboard', compact(
            'totalKegiatan',
            'kegiatanAktif',
            'kegiatanSelesai',
            'totalPengguna',
            'totalResponKuesioner',
            'kegiatanTerbaru',
            'kegiatanBulanIni',
            'chartData'
        ));
    }
}
