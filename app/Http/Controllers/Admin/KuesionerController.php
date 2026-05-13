<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use App\Models\Kuesioner;
use App\Models\KuesionerPertanyaan;
use App\Models\KuesionerResponse;
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
            'auto_generate' => 'nullable|boolean'
        ]);

        $kuesioner = Kuesioner::create([
            'kegiatan_id' => $kegiatan->id,
            'judul' => $request->judul,
            'is_active' => true,
        ]);

        if ($request->has('auto_generate') && $request->auto_generate) {
            $this->generateTemplateQuestions($kuesioner);
        }

        return back()->with('success', 'Kuesioner berhasil dibuat.');
    }

    private function generateTemplateQuestions(Kuesioner $kuesioner)
    {
        $template = [
            // 1. Narasumber
            ['q' => 'Kompetensi', 't' => 'rating', 'c' => 'Narasumber', 'o' => 1],
            ['q' => 'Penguasaan Materi', 't' => 'rating', 'c' => 'Narasumber', 'o' => 2],
            ['q' => 'Cara penyampaian', 't' => 'rating', 'c' => 'Narasumber', 'o' => 3],
            ['q' => 'Komunikasi', 't' => 'rating', 'c' => 'Narasumber', 'o' => 4],
            ['q' => 'Ketepatan Waktu', 't' => 'rating', 'c' => 'Narasumber', 'o' => 5],
            ['q' => 'Komentar terkait Narasumber', 't' => 'text', 'c' => 'Narasumber', 'o' => 6],

            // 2. Materi yang disampaikan
            ['q' => 'Kesesuaian Kegiatan', 't' => 'rating', 'c' => 'Materi yang disampaikan', 'o' => 7],
            ['q' => 'Kesesuaian Kebutuhan', 't' => 'rating', 'c' => 'Materi yang disampaikan', 'o' => 8],
            ['q' => 'Kesesuaian Peserta', 't' => 'rating', 'c' => 'Materi yang disampaikan', 'o' => 9],
            ['q' => 'Alokasi Waktu Materi', 't' => 'rating', 'c' => 'Materi yang disampaikan', 'o' => 10],
            ['q' => 'Ketersediaan Materi', 't' => 'rating', 'c' => 'Materi yang disampaikan', 'o' => 11],
            ['q' => 'Komentar terkait Materi yang disampaikan', 't' => 'text', 'c' => 'Materi yang disampaikan', 'o' => 12],

            // 3. Akomodasi / Tempat Kegiatan
            ['q' => 'Kelengkapan / Fasilitas', 't' => 'rating', 'c' => 'Akomodasi / Tempat Kegiatan', 'o' => 13],
            ['q' => 'Kenyamanan', 't' => 'rating', 'c' => 'Akomodasi / Tempat Kegiatan', 'o' => 14],
            ['q' => 'Kebersihan', 't' => 'rating', 'c' => 'Akomodasi / Tempat Kegiatan', 'o' => 15],
            ['q' => 'Keamanan', 't' => 'rating', 'c' => 'Akomodasi / Tempat Kegiatan', 'o' => 16],
            ['q' => 'Pelayanan', 't' => 'rating', 'c' => 'Akomodasi / Tempat Kegiatan', 'o' => 17],
            ['q' => 'Komentar terkait Akomodasi / Tempat Kegiatan', 't' => 'text', 'c' => 'Akomodasi / Tempat Kegiatan', 'o' => 18],

            // 4. Konsumsi
            ['q' => 'Kualitas', 't' => 'rating', 'c' => 'Konsumsi', 'o' => 19],
            ['q' => 'Kuantitas', 't' => 'rating', 'c' => 'Konsumsi', 'o' => 20],
            ['q' => 'Variasi', 't' => 'rating', 'c' => 'Konsumsi', 'o' => 21],
            ['q' => 'Kebersihan', 't' => 'rating', 'c' => 'Konsumsi', 'o' => 22],
            ['q' => 'Pelayanan', 't' => 'rating', 'c' => 'Konsumsi', 'o' => 23],
            ['q' => 'Komentar terkait Konsumsi', 't' => 'text', 'c' => 'Konsumsi', 'o' => 24],

            // 5. Pelayanan Panitia
            ['q' => 'Layanan Administrasi', 't' => 'rating', 'c' => 'Pelayanan Panitia', 'o' => 25],
            ['q' => 'Keramahan', 't' => 'rating', 'c' => 'Pelayanan Panitia', 'o' => 26],
            ['q' => 'Kesigapan', 't' => 'rating', 'c' => 'Pelayanan Panitia', 'o' => 27],
            ['q' => 'Penampilan', 't' => 'rating', 'c' => 'Pelayanan Panitia', 'o' => 28],
            ['q' => 'Kekompakkan', 't' => 'rating', 'c' => 'Pelayanan Panitia', 'o' => 29],
            ['q' => 'Komentar terkait Pelayanan Panitia', 't' => 'text', 'c' => 'Pelayanan Panitia', 'o' => 30],

            // 6. Penilaian Keseluruhan
            ['q' => 'Penilaian kegiatan', 't' => 'rating', 'c' => 'Penilaian Keseluruhan', 'o' => 31],
            ['q' => 'Kritik/ Saran perbaikan', 't' => 'text', 'c' => 'Penilaian Keseluruhan', 'o' => 32],
        ];

        foreach ($template as $item) {
            KuesionerPertanyaan::create([
                'kuesioner_id' => $kuesioner->id,
                'pertanyaan' => $item['q'],
                'tipe' => $item['t'],
                'opsi' => ['kategori' => $item['c']],
                'urutan' => $item['o'],
            ]);
        }
    }

    public function addPertanyaan(Request $request, Kuesioner $kuesioner)
    {
        $request->validate([
            'pertanyaan' => 'required|string',
            'tipe' => 'required|in:rating,text',
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

    public function loadTemplate(Kuesioner $kuesioner)
    {
        // Clear existing questions if any to avoid duplicates, or just append? 
        // Better to append but safety alert! Actually, let's just append or seed cleanly.
        $this->generateTemplateQuestions($kuesioner);
        return back()->with('success', 'Berhasil memasukkan 32 pertanyaan template resmi.');
    }

    public function cetakBlanko(Kuesioner $kuesioner)
    {
        $kuesioner->load('kegiatan', 'pertanyaan');
        $kegiatan = $kuesioner->kegiatan;
        
        // Pass a proxy user objects and empty collection of answers to safely bypass validation and render the sheet blank!
        $user = (object)['username' => '_____'];
        $answers = collect(); 

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.kegiatan.pdf_kuesioner', compact(
            'kuesioner', 'kegiatan', 'user', 'answers'
        ));

        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

        $filename = 'Blanko_Evaluasi_' . str_replace(' ', '_', $kegiatan->nama_kegiatan) . '.pdf';
        return $pdf->stream($filename);
    }

    public function cetakPdfResponse(KuesionerResponse $response)
    {
        $response->load(['user.biodata', 'kuesioner.kegiatan', 'jawaban.pertanyaan']);
        $kuesioner = $response->kuesioner;
        $kegiatan = $kuesioner->kegiatan;
        $user = $response->user;
        
        // Process answers mapped by pertanyaan_id
        $answers = $response->jawaban->keyBy('pertanyaan_id');

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.kegiatan.pdf_kuesioner', compact(
            'response', 'kuesioner', 'kegiatan', 'user', 'answers'
        ));

        // Setup formatting options to enable images & inline css properly for custom PDF layout
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

        $filename = 'Evaluasi_' . str_replace(' ', '_', $kegiatan->nama_kegiatan) . '_' . str_replace(' ', '_', $user->username) . '.pdf';
        return $pdf->stream($filename);
    }
}
