<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\KegiatanDokumentasi;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        $kegiatanTerbaru = Kegiatan::with('dokumentasi', 'peserta', 'narasumber')
            ->where('status', '!=', 'draft')
            ->orderBy('waktu_mulai', 'desc')
            ->take(5)
            ->get();

        $kegiatanBerlangsung = Kegiatan::where('status', 'ongoing')
            ->orderBy('waktu_mulai', 'asc')
            ->take(3)
            ->get();


        if (auth()->check()) {
            $user = auth()->user();
            if ($user->isAdmin()) {
                $kegiatanDenganLokasi = Kegiatan::whereNotNull('latitude')
                    ->whereNotNull('longitude')
                    ->where('status', '!=', 'draft')
                    ->orderBy('waktu_mulai', 'desc')
                    ->take(20)
                    ->get();
            } else {
                $kegiatanDenganLokasi = Kegiatan::whereNotNull('latitude')
                    ->whereNotNull('longitude')
                    ->where('status', '!=', 'draft')
                    ->where(function ($query) use ($user) {
                        $query->whereHas('peserta', function ($q) use ($user) {
                            $q->where('users.id', $user->id);
                        })->orWhereHas('narasumber', function ($q) use ($user) {
                            $q->where('users.id', $user->id);
                        });
                    })
                    ->orderBy('waktu_mulai', 'desc')
                    ->take(20)
                    ->get();
            }
        } else {
            $kegiatanDenganLokasi = collect();
        }

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
            'kegiatanDenganLokasi',
            'totalKegiatan',
            'totalKegiatanSelesai',
            'locations' 
        ));
    }
    public function show(Kegiatan $kegiatan)
    {
        $kegiatan->load('dokumentasi');
    
        return view('show', compact('kegiatan'));
    }
}