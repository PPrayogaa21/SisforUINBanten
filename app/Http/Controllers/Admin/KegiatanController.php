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

    public function index(Request $request)
    {
        $query = Kegiatan::latest();
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_kegiatan', 'ilike', "%{$search}%")
                  ->orWhere('tempat', 'ilike', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        $kegiatan = $query->paginate(10)->withQueryString();
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
            'link_maps' => 'nullable|url',
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
        $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'jenis' => 'required|in:rapat,seminar,pelatihan,workshop,lainnya',
            'waktu_mulai' => 'required|date',
            'waktu_selesai' => 'required|date|after_or_equal:waktu_mulai',
            'tempat' => 'required|string|max:255',
            'link_maps' => 'nullable|url',
            'status' => 'required|in:draft,published',
        ]);

        $kegiatan->update($request->all());

        return redirect()->route('admin.kegiatan.index')
            ->with('success','Kegiatan berhasil diupdate');
    }

    public function destroy(Kegiatan $kegiatan)
    {
        // Hapus file materi fisik
        foreach ($kegiatan->materi as $materi) {
            if (Storage::disk('public')->exists($materi->file_path)) {
                Storage::disk('public')->delete($materi->file_path);
            }
        }
        
        // Hapus file dokumentasi fisik
        foreach ($kegiatan->dokumentasi as $dokumentasi) {
            if (Storage::disk('public')->exists($dokumentasi->file_path)) {
                Storage::disk('public')->delete($dokumentasi->file_path);
            }
        }

        // Hapus file dokumen fisik
        foreach ($kegiatan->dokumen as $dokumen) {
            $otherDocsCount = KegiatanDokumen::where('file_path', $dokumen->file_path)
                ->where('id', '!=', $dokumen->id)
                ->count();
            if ($otherDocsCount === 0 && Storage::disk('public')->exists($dokumen->file_path)) {
                Storage::disk('public')->delete($dokumen->file_path);
            }
        }

        $kegiatan->delete();

        return back()->with('success','Kegiatan beserta seluruh file terkait berhasil dihapus');
    }

    /* =======================
        PESERTA
    ======================= */

    public function peserta(Request $request, Kegiatan $kegiatan)
    {
        $query = User::with('biodata')->where('role', 'user');
        
        $kegiatan->load('peserta.biodata');
        $pesertaList = $kegiatan->peserta;

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('biodata', function($bq) use ($search) {
                    $bq->where('nama_lengkap', 'ilike', "%{$search}%")
                       ->orWhere('nip', 'ilike', "%{$search}%");
                })->orWhere('username', 'ilike', "%{$search}%");
            });

            $searchLower = strtolower($search);
            $pesertaList = $pesertaList->filter(function($p) use ($searchLower) {
                $nama = strtolower($p->biodata->nama_lengkap ?? $p->username ?? '');
                $nip = strtolower($p->biodata->nip ?? '');
                return str_contains($nama, $searchLower) || str_contains($nip, $searchLower);
            });
        }

        $users = $query->get();

        return view('admin.kegiatan.peserta', compact('kegiatan','users', 'pesertaList'));
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
                'status_kehadiran' => 'registered'
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
    public function cetakPdfPeserta(Request $request, Kegiatan $kegiatan)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Akses ditolak. Hanya admin yang dapat mengakses fitur ini.');
        }

        if ($request->has('peserta_ids')) {
            $request->validate([
                'peserta_ids' => 'required|array',
                'peserta_ids.*' => 'exists:users,id'
            ]);
            $peserta = $kegiatan->peserta()->whereIn('users.id', $request->peserta_ids)->with('biodata')->wherePivot('status_kehadiran', 'hadir')->get();
        } else {
            $peserta = $kegiatan->peserta()->with('biodata')->wherePivot('status_kehadiran', 'hadir')->get();
        }

        if ($peserta->isEmpty()) {
            return back()->with('warning', 'Tidak ada peserta yang hadir untuk dicetak.');
        }

        $printDate = \Carbon\Carbon::now();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.kegiatan.pdf_biodata', compact('kegiatan', 'peserta', 'printDate'));
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf->stream('Biodata_Peserta_' . $kegiatan->id . '.pdf');
    }

    /* =======================
        NARASUMBER
    ======================= */

    public function narasumberList(Request $request, Kegiatan $kegiatan)
    {
        $query = User::with('biodata')->where('role', 'user');

        $kegiatan->load('narasumber.biodata');
        $narasumberList = $kegiatan->narasumber;

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('biodata', function($bq) use ($search) {
                    $bq->where('nama_lengkap', 'ilike', "%{$search}%")
                       ->orWhere('nip', 'ilike', "%{$search}%");
                })->orWhere('username', 'ilike', "%{$search}%");
            });

            $searchLower = strtolower($search);
            $narasumberList = $narasumberList->filter(function($n) use ($searchLower) {
                $nama = strtolower($n->biodata->nama_lengkap ?? $n->username ?? '');
                $nip = strtolower($n->biodata->nip ?? '');
                return str_contains($nama, $searchLower) || str_contains($nip, $searchLower);
            });
        }

        $users = $query->get();

        return view('admin.kegiatan.narasumber', compact('kegiatan','users','narasumberList'));
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

    public function cetakPdfNarasumber(Request $request, Kegiatan $kegiatan)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Akses ditolak. Hanya admin yang dapat mengakses fitur ini.');
        }

        if ($request->has('narasumber_ids')) {
            $request->validate([
                'narasumber_ids' => 'required|array',
                'narasumber_ids.*' => 'exists:users,id'
            ]);
            $narasumber = User::whereIn('id', $request->narasumber_ids)->with('biodata')->get();
        } else {
            $narasumber = $kegiatan->narasumber()->with('biodata')->get();
        }

        if ($narasumber->isEmpty()) {
            return back()->with('warning', 'Tidak ada narasumber untuk dicetak.');
        }

        $printDate = \Carbon\Carbon::now();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.kegiatan.pdf_biodata_narasumber', compact('kegiatan', 'narasumber', 'printDate'));
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf->stream('Biodata_Narasumber_' . $kegiatan->id . '.pdf');
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
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png|max:20480',
            'judul' => 'required',
            'target_user_id' => 'required|array',
            'target_user_id.*' => 'required|exists:users,id'
        ]);
    
        $path = $request->file('file')->store('dokumen', 'public');
    
        foreach ($request->target_user_id as $userId) {
            KegiatanDokumen::create([
                'kegiatan_id' => $kegiatan->id,
                'judul' => $request->judul,
                'jenis' => $request->jenis,
                'file_path' => $path,
                'user_id' => auth()->id(),
                'target_user_id' => $userId, 
            ]);
        }
    
        return back()->with('success', 'Dokumen terkirim ke penerima yang dipilih');
    }

    public function deleteDokumen(Kegiatan $kegiatan, KegiatanDokumen $dokumen)
    {
        // Cek apakah ada record lain yang menggunakan file yang sama
        $otherDocsCount = KegiatanDokumen::where('file_path', $dokumen->file_path)
            ->where('id', '!=', $dokumen->id)
            ->count();

        if ($otherDocsCount === 0 && Storage::disk('public')->exists($dokumen->file_path)) {
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

    public function extractMaps(Request $request)
    {
        $request->validate([
            'url' => ['required', 'url', function ($attribute, $value, $fail) {
                if (!preg_match('/^(https?:\/\/)?(www\.)?(google\.com\/maps|maps\.app\.goo\.gl|maps\.google\.com)/i', $value)) {
                    $fail('URL harus merupakan tautan Google Maps yang valid.');
                }
            }]
        ]);
        $url = $request->url;

        $lat = null; $lng = null; $alamat = null;

        // -- STAGE 1: GET REDIRECT URL FOR COORDINATES --
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_MAXREDIRS => 5,
            CURLOPT_NOBODY => true // fast HEAD request just to get final URL
        ]);
        curl_exec($ch);
        $finalUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
        curl_close($ch);

        // Extract Coords from path
        if (preg_match('/!3d(-?\d+\.\d+)!4d(-?\d+\.\d+)/', $finalUrl, $m)) {
            $lat = $m[1]; $lng = $m[2];
        } elseif (preg_match('/@(-?\d+\.\d+),(-?\d+\.\d+)/', $finalUrl, $m)) {
            $lat = $m[1]; $lng = $m[2];
        }

        // -- STAGE 2: GET METADATA FOR ADDRESS --
        // Using Twitterbot ensures Google serves a small static page with Place & Address combined in meta tags
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_USERAGENT => 'Twitterbot/1.0', 
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_TIMEOUT => 10,
        ]);
        $response = curl_exec($ch);
        curl_close($ch);

        // Scrape Address from og:title
        // We use a robust approach to find the content attribute within the correct meta tag
        preg_match_all('/<meta\s+([^>]+)>/i', $response, $metas);
        $rawTitle = null;
        
        foreach ($metas[1] as $attrs) {
            if (stripos($attrs, 'property="og:title"') !== false || stripos($attrs, 'itemprop="name"') !== false) {
                if (preg_match('/content="([^"]+)"/i', $attrs, $c)) {
                    $rawTitle = html_entity_decode($c[1]);
                    break;
                }
            }
        }

        if ($rawTitle && str_contains($rawTitle, ' · ')) {
            $parts = explode(' · ', $rawTitle);
            // Address is usually the part containing 'Jl.' or just the rest
            array_shift($parts); // drop the Place Name
            $alamat = implode(' · ', $parts);
            
            // Clean up Google Plus Codes (e.g., "R5C3+2QX, ") from start of address if present
            $alamat = preg_replace('/^[A-Z0-9]{4}\+[A-Z0-9]{2,3},\s*/i', '', $alamat);
        }

        // Fallback if address still empty
        if (!$alamat) {
             foreach ($metas[1] as $attrs) {
                 if (stripos($attrs, 'property="og:description"') !== false) {
                     if (preg_match('/content="([^"]+)"/i', $attrs, $c)) {
                         $rawDesc = html_entity_decode($c[1]);
                         if (!str_contains($rawDesc, '★')) {
                              $alamat = $rawDesc;
                         }
                         break;
                     }
                 }
             }
        }

        // Sanitizing
        if ($alamat && str_contains(strtolower($alamat), 'lihat peta')) $alamat = null;
        if ($alamat && str_contains(strtolower($alamat), 'mencari bisnis lokal')) $alamat = null;

        return response()->json([
            'lat' => $lat,
            'lng' => $lng,
            'alamat' => $alamat ? trim($alamat) : null
        ]);
    }
}