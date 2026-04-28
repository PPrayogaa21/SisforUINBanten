<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kegiatan;
use App\Models\User;
use App\Models\Materi;
use App\Models\Dokumentasi;
use App\Models\Dokumen;

class KegiatanController extends Controller
{
    /* =======================
        CRUD KEGIATAN
    ======================= */

    public function index()
    {
        $kegiatan = Kegiatan::latest()->get();
        return view('admin.kegiatan.index', compact('kegiatan'));
    }

    public function create()
    {
        return view('admin.kegiatan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required',
            'deskripsi' => 'required',
            'tanggal' => 'required|date',
            'lokasi' => 'required'
        ]);

        Kegiatan::create($request->all());

        return redirect()->route('admin.kegiatan.index')
            ->with('success','Kegiatan berhasil dibuat');
    }

    public function show(Kegiatan $kegiatan)
    {
        return view('admin.kegiatan.show', compact('kegiatan'));
    }

    public function edit(Kegiatan $kegiatan)
    {
        return view('admin.kegiatan.edit', compact('kegiatan'));
    }

    public function update(Request $request, Kegiatan $kegiatan)
    {
        $kegiatan->update($request->all());

        return redirect()->route('admin.kegiatan.index')
            ->with('success','Kegiatan berhasil diupdate');
    }

    public function destroy(Kegiatan $kegiatan)
    {
        $kegiatan->delete();

        return back()->with('success','Kegiatan dihapus');
    }

    /* =======================
        PESERTA
    ======================= */

    public function peserta(Kegiatan $kegiatan)
    {
        $users = User::where('role','peserta')->get();

        return view('admin.kegiatan.peserta', compact('kegiatan','users'));
    }

    public function addPeserta(Request $request, Kegiatan $kegiatan)
    {
        $kegiatan->peserta()->attach($request->user_id);

        return back()->with('success','Peserta ditambahkan');
    }

    public function removePeserta(Kegiatan $kegiatan, User $user)
    {
        $kegiatan->peserta()->detach($user->id);

        return back()->with('success','Peserta dihapus');
    }

    public function updateKehadiran(Request $request, Kegiatan $kegiatan, User $user)
    {
        $kegiatan->peserta()->updateExistingPivot($user->id, [
            'kehadiran' => $request->kehadiran
        ]);

        return back()->with('success','Kehadiran diperbarui');
    }

    /* =======================
        NARASUMBER
    ======================= */

    public function narasumberList(Kegiatan $kegiatan)
    {
        $users = User::where('role','narasumber')->get();

        return view('admin.kegiatan.narasumber', compact('kegiatan','users'));
    }

    public function addNarasumber(Request $request, Kegiatan $kegiatan)
    {
        $kegiatan->narasumber()->attach($request->user_id);

        return back()->with('success','Narasumber ditambahkan');
    }

    public function removeNarasumber(Kegiatan $kegiatan, User $user)
    {
        $kegiatan->narasumber()->detach($user->id);

        return back()->with('success','Narasumber dihapus');
    }

    /* =======================
        MATERI
    ======================= */

    public function materiIndex(Kegiatan $kegiatan)
    {
        return view('admin.kegiatan.materi', compact('kegiatan'));
    }

    public function uploadMateri(Request $request, Kegiatan $kegiatan)
    {
        $file = $request->file('file');

        $path = $file->store('materi','public');

        Materi::create([
            'kegiatan_id' => $kegiatan->id,
            'nama' => $file->getClientOriginalName(),
            'file' => $path
        ]);

        return back()->with('success','Materi berhasil upload');
    }

    public function deleteMateri(Kegiatan $kegiatan, Materi $materi)
    {
        $materi->delete();

        return back()->with('success','Materi dihapus');
    }

    /* =======================
        DOKUMENTASI
    ======================= */

    public function dokumentasiIndex(Kegiatan $kegiatan)
    {
        return view('admin.kegiatan.dokumentasi', compact('kegiatan'));
    }

    public function uploadDokumentasi(Request $request, Kegiatan $kegiatan)
    {
        $path = $request->file('file')->store('dokumentasi','public');

        Dokumentasi::create([
            'kegiatan_id' => $kegiatan->id,
            'file' => $path
        ]);

        return back()->with('success','Dokumentasi ditambahkan');
    }

    public function deleteDokumentasi(Kegiatan $kegiatan, Dokumentasi $dokumentasi)
    {
        $dokumentasi->delete();

        return back()->with('success','Dokumentasi dihapus');
    }

    /* =======================
        DOKUMEN
    ======================= */

    public function dokumenIndex(Kegiatan $kegiatan)
    {
        return view('admin.kegiatan.dokumen', compact('kegiatan'));
    }

    public function uploadDokumen(Request $request, Kegiatan $kegiatan)
    {
        $path = $request->file('file')->store('dokumen','public');

        Dokumen::create([
            'kegiatan_id' => $kegiatan->id,
            'file' => $path
        ]);

        return back()->with('success','Dokumen diupload');
    }

    public function deleteDokumen(Kegiatan $kegiatan, Dokumen $dokumen)
    {
        $dokumen->delete();

        return back()->with('success','Dokumen dihapus');
    }
}