<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kegiatan;
use App\Models\User;
use App\Models\KegiatanMateri;
use App\Models\KegiatanDokumentasi;
use App\Models\KegiatanDokumen;
use Illuminate\Support\Facades\Storage;

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
            'nama_kegiatan' => 'required|string|max:255',
            'jenis' => 'required|in:rapat,seminar,pelatihan,workshop,lainnya',
            'waktu_mulai' => 'required|date',
            'waktu_selesai' => 'required|date|after_or_equal:waktu_mulai',
            'tempat' => 'required|string|max:255',
            'status' => 'required|in:draft,published',
        ]);

        Kegiatan::create(array_merge($request->all(), [
            'created_by' => auth()->id(),
        ]));

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

    public function peserta(Request $request, Kegiatan $kegiatan)
    {
        $query = User::with('biodata')->where('role', 'user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }

        $users = $query->get();

        return view('admin.kegiatan.peserta', compact('kegiatan','users'));
    }

    public function addPeserta(Request $request, Kegiatan $kegiatan)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        if ($kegiatan->peserta()->where('user_id', $request->user_id)->exists()) {
            return back()->with('warning', 'Peserta sudah terdaftar dalam kegiatan ini!');
        }

        $kegiatan->peserta()->syncWithoutDetaching([
            $request->user_id => [
                'status_kehadiran' => 'tidak_hadir'
            ]
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
            'status_kehadiran' => $request->status_kehadiran
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

    public function narasumberList(Request $request, Kegiatan $kegiatan)
    {
        $query = User::with('biodata')->where('role', 'user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }

        $users = $query->get();
        $assigned = $kegiatan->narasumber;

        return view('admin.kegiatan.narasumber', compact('kegiatan','users','assigned'));
    }

    public function addNarasumber(Request $request, Kegiatan $kegiatan)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        if ($kegiatan->narasumber()->where('user_id', $request->user_id)->exists()) {
            return back()->with('warning', 'Narasumber sudah terdaftar dalam kegiatan ini!');
        }

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
        $request->validate([
            'judul' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip|max:20480'
        ]);

        $file = $request->file('file');
        $path = $file->store('materi','public');

        KegiatanMateri::create([
            'kegiatan_id' => $kegiatan->id,
            'uploaded_by' => auth()->id(),
            'judul' => $request->judul,
            'file_path' => $path,
            'file_type' => $file->getClientOriginalExtension(),
            'file_size' => $file->getSize(),  
        ]);

        return back()->with('success','Materi berhasil upload');
    }

    public function deleteMateri(Kegiatan $kegiatan, KegiatanMateri $materi)
    {
        if (Storage::disk('public')->exists($materi->file_path)) {
            Storage::disk('public')->delete($materi->file_path);
        }
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
        $request->validate([
            'files.*' => 'required|image|mimes:jpg,jpeg,png,webp,gif|max:40960'
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

    public function deleteDokumentasi(Kegiatan $kegiatan, KegiatanDokumentasi $dokumentasi)
    {
        if (Storage::disk('public')->exists($dokumentasi->file_path)) {
            Storage::disk('public')->delete($dokumentasi->file_path);
        }
        $dokumentasi->delete();

        return back()->with('success','Dokumentasi dihapus');
    }

    /* =======================
        DOKUMEN
    ======================= */

    public function dokumenIndex(Kegiatan $kegiatan)
    {
        $kegiatan->load('dokumen.targetUser');
        $narasumber = $kegiatan->narasumber;
        $peserta = $kegiatan->peserta;
        return view('admin.kegiatan.dokumen', compact('kegiatan', 'narasumber', 'peserta'));
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
            'user_id' => auth()->id(),
            'target_user_id' => $request->target_user_id, 
        ]);
    
        return back()->with('success', 'Surat tugas terkirim');
    }

    public function deleteDokumen(Kegiatan $kegiatan, KegiatanDokumen $dokumen)
    {
        if (Storage::disk('public')->exists($dokumen->file_path)) {
            Storage::disk('public')->delete($dokumen->file_path);
        }
        
        $dokumen->delete();

        return back()->with('success', 'Dokumen berhasil dihapus');
    }

    public function downloadDokumen(Kegiatan $kegiatan, KegiatanDokumen $dokumen)
    {
        if (!Storage::disk('public')->exists($dokumen->file_path)) {
            abort(404, 'File tidak ditemukan.');
        }

        return Storage::disk('public')->download($dokumen->file_path, $dokumen->judul . '.' . pathinfo($dokumen->file_path, PATHINFO_EXTENSION));
    }
}