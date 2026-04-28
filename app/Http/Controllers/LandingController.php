<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\KegiatanDokumentasi;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        $kegiatanTerbaru = Kegiatan::where('status', '!=', 'draft')
            ->orderBy('waktu_mulai', 'desc')
            ->take(6)
            ->get();

        $kegiatanBerlangsung = Kegiatan::where('status', 'ongoing')
            ->orderBy('waktu_mulai', 'asc')
            ->take(3)
            ->get();

        $dokumentasi = KegiatanDokumentasi::with('kegiatan')
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        $kegiatanDenganLokasi = Kegiatan::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->where('status', '!=', 'draft')
            ->orderBy('waktu_mulai', 'desc')
            ->take(20)
            ->get();

        $totalKegiatan = Kegiatan::where('status', '!=', 'draft')->count();
        $totalKegiatanSelesai = Kegiatan::where('status', 'completed')->count();

    
        $locations = $kegiatanDenganLokasi->map(function ($k) {
            return [
                'lat' => (float) $k->latitude,
                'lng' => (float) $k->longitude,
                'nama' => $k->nama_kegiatan,
                'tempat' => $k->tempat,
                'waktu' => $k->waktu_mulai
                    ? \Carbon\Carbon::parse($k->waktu_mulai)->format('d/m/Y')
                    : '-',
            ];
        });

        return view('landing', compact(
            'kegiatanTerbaru',
            'kegiatanBerlangsung',
            'dokumentasi',
            'kegiatanDenganLokasi',
            'totalKegiatan',
            'totalKegiatanSelesai',
            'locations' 
        ));
    }
}