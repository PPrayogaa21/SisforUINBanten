<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Kegiatan;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user && $user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        // Dashboard shows published, ongoing, and completed activities
        $kegiatan = Kegiatan::whereIn('status', ['published', 'ongoing', 'completed'])
            ->latest()
            ->get();

        // Stats and Priority should include completed activities for history, but exclude draft/cancelled
        $allUserKegiatan = Kegiatan::whereIn('status', ['published', 'ongoing', 'completed'])
            ->where(function($query) use ($user) {
                $query->whereHas('peserta', fn($q) => $q->where('user_id', $user->id))
                      ->orWhereHas('narasumber', fn($q) => $q->where('user_id', $user->id));
            })
            ->latest()
            ->get();

        $kegiatanDiikuti = $allUserKegiatan;
        $totalKegiatanDiikuti = $kegiatanDiikuti->count();
        $kegiatanAktif = $kegiatanDiikuti->where('status', 'ongoing')->count();
        $kegiatanSelesai = $kegiatanDiikuti->where('status', 'completed')->count();

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