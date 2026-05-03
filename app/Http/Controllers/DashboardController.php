<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Kegiatan;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $kegiatan = Kegiatan::latest()->get();

        $kegiatanDiikuti = $kegiatan->filter(function ($item) use ($user) {
            return $item->isPeserta($user->id) || $item->isNarasumber($user->id);
        });

        $totalKegiatanDiikuti = $kegiatanDiikuti->count();
        $kegiatanAktif = $kegiatan->where('status', 'ongoing')->count();
        $kegiatanSelesai = $kegiatan->where('status', 'completed')->count();

        return view('dashboard', compact(
            'user',
            'kegiatan',
            'kegiatanDiikuti',
            'totalKegiatanDiikuti',
            'kegiatanAktif',
            'kegiatanSelesai'
        ));
    }
}