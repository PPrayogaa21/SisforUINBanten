<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kegiatan;
use App\Models\User;
use App\Models\KegiatanMateri;
use App\Models\KegiatanDokumentasi;
use App\Models\KegiatanDokumen;

class KegiatanController extends Controller
{
    /* =======================
        CRUD KEGIATAN
    ======================= */

    public function index()
    {
        ## ini sebelum gue ubah $kegiatan = Kegiatan::latest()->get();
        $kegiatan = Kegiatan::latest()->paginate(10);;
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
        $users = User::where('role','user')->get();

        return view('admin.kegiatan.peserta', compact('kegiatan','users'));
    }

    public function addPeserta(Request $request, Kegiatan $kegiatan)
    {
        ## ini kode sebelum gue ubah $kegiatan->peserta()->attach($request->user_id);
        $kegiatan->peserta()->attach($request->user_id, [
            'status_kehadiran' => 'tidak_hadir'
        ]);
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
            # ini sebelum gue ubahh'kehadiran' => $request->kehadiran
            'status_kehadiran' => $request->kehadiran
        ]);

        return back()->with('success','Kehadiran diperbarui');
    }

    #fitur absen 
    public function absen(Kegiatan $kegiatan)
    {
        $user = auth()->user();
    
        $kegiatan->peserta()->updateExistingPivot($user->id, [
            'status_kehadiran' => 'hadir'
        ]);
    
        return back()->with('success', 'Berhasil absen');
    }
    /* =======================
        NARASUMBER
    ======================= */

    public function narasumberList(Kegiatan $kegiatan)
    {
        $users = User::where('role','user')->get(); // FIX
        $assigned = $kegiatan->narasumber;

        return view('admin.kegiatan.narasumber', compact('kegiatan','users','assigned'));
    }

    public function addNarasumber(Request $request, Kegiatan $kegiatan)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        // biar ga duplicate
        $kegiatan->narasumber()->syncWithoutDetaching([
            $request->user_id => [
                'topik_materi' => $request->topik_materi
            ]
        ]);

        return back()->with('success','Narasumber berhasil ditambahkan');
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

        KegiatanMateri::create([
            'kegiatan_id' => $kegiatan->id,
            'uploaded_by' => auth()->id(),
            'judul' => $file->getClientOriginalName(),
            'file_path' => $path,
            'file_type' => $file->getClientOriginalExtension(),
            'file_size' => $file->getSize(),  
        ]);
        $request->validate([
            'file' => 'required|file|max:20480'
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
        $users = User::where('role', 'user')->get();
        return view('admin.kegiatan.dokumen', compact('kegiatan', 'users'));
    }

    public function uploadDokumentasi(Request $request, Kegiatan $kegiatan)
    {
        $request->validate([
            'files.*' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);
    
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('dokumentasi', 'public');
    
                KegiatanDokumentasi::create([
                    'kegiatan_id' => $kegiatan->id,
                    'file_path' => $path,
                    'caption' => $request->caption
                ]);
            }
        }
    
        return back()->with('success','Dokumentasi berhasil ditambahkan');
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
        $users = User::all();
        return view('admin.kegiatan.dokumen', compact('kegiatan', 'users'));
    }

    public function uploadDokumen(Request $request, Kegiatan $kegiatan)
    {
        $request->validate([
            'file' => 'required|file|max:2048',
            'judul' => 'required',
            'target_user_id' => 'required'
        ]);
    
        $path = $request->file('file')->store('dokumen', 'public');
    
        KegiatanDokumen::create([
            'kegiatan_id' => $kegiatan->id,
            'judul' => $request->judul,
            'jenis' => $request->jenis,
            'file_path' => $path,
            'target_user_id' => $request->target_user_id, 
        ]);
    
        return back()->with('success', 'Surat tugas terkirim');
    }
}