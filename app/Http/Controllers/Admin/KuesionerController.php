<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use App\Models\Kuesioner;
use App\Models\KuesionerPertanyaan;
use Illuminate\Http\Request;

class KuesionerController extends Controller
{
    public function index(Kegiatan $kegiatan)
    {
        $kegiatan->load('kuesioner.pertanyaan', 'kuesioner.responses');
        return view('admin.kegiatan.kuesioner', compact('kegiatan'));
    }

    public function store(Request $request, Kegiatan $kegiatan)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
        ]);

        $kuesioner = Kuesioner::create([
            'kegiatan_id' => $kegiatan->id,
            'judul' => $request->judul,
            'is_active' => true,
        ]);

        return back()->with('success', 'Kuesioner berhasil dibuat.');
    }

    public function addPertanyaan(Request $request, Kuesioner $kuesioner)
    {
        $request->validate([
            'pertanyaan' => 'required|string',
            'tipe' => 'required|in:rating,text,pilihan_ganda',
            'opsi' => 'nullable|array',
        ]);

        $maxUrutan = $kuesioner->pertanyaan()->max('urutan') ?? 0;

        KuesionerPertanyaan::create([
            'kuesioner_id' => $kuesioner->id,
            'pertanyaan' => $request->pertanyaan,
            'tipe' => $request->tipe,
            'opsi' => $request->opsi,
            'urutan' => $maxUrutan + 1,
        ]);

        return back()->with('success', 'Pertanyaan berhasil ditambahkan.');
    }

    public function deletePertanyaan(KuesionerPertanyaan $pertanyaan)
    {
        $pertanyaan->delete();
        return back()->with('success', 'Pertanyaan berhasil dihapus.');
    }

    public function destroy(Kuesioner $kuesioner)
    {
        $kuesioner->delete();
        return back()->with('success', 'Kuesioner berhasil dihapus.');
    }

    public function hasil(Kuesioner $kuesioner)
    {
        $kuesioner->load('pertanyaan.jawaban', 'responses.user', 'responses.jawaban');
        $kegiatan = $kuesioner->kegiatan;
        return view('admin.kegiatan.kuesioner-hasil', compact('kuesioner', 'kegiatan'));
    }
}
