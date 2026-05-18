<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use App\Models\Kuesioner;
use App\Models\KuesionerResponse;
use App\Models\KuesionerJawaban;
use Illuminate\Http\Request;

class KuesionerController extends Controller
{
    public function fill(Kegiatan $kegiatan, Kuesioner $kuesioner)
    {
        $user = auth()->user();

        if (!$kegiatan->peserta()->where('user_id', $user->id)->exists()) {
            abort(403);
        }

        $alreadyFilled = KuesionerResponse::where('kuesioner_id', $kuesioner->id)
            ->where('user_id', $user->id)
            ->exists();

        if ($alreadyFilled) {
            return redirect()->route('peserta.kegiatan.show', $kegiatan)
                ->with('info', 'Anda sudah mengisi kuesioner ini.');
        }

        $kuesioner->load('pertanyaan');

        return view('peserta.kuesioner.fill', compact('kegiatan', 'kuesioner'));
    }

    public function submit(Request $request, Kegiatan $kegiatan, Kuesioner $kuesioner)
    {
        $user = auth()->user();

        $request->validate([
            'jawaban' => 'required|array',
            'jawaban.*' => 'required|string',
        ]);

        $alreadyFilled = KuesionerResponse::where('kuesioner_id', $kuesioner->id)
            ->where('user_id', $user->id)
            ->exists();

        if ($alreadyFilled) {
            return redirect()->route('peserta.kegiatan.show', $kegiatan)
                ->with('info', 'Anda sudah mengisi kuesioner ini sebelumnya.');
        }

        $response = KuesionerResponse::create([
            'kuesioner_id' => $kuesioner->id,
            'user_id' => $user->id,
        ]);

        foreach ($request->jawaban as $pertanyaanId => $jawaban) {
            KuesionerJawaban::create([
                'response_id' => $response->id,
                'pertanyaan_id' => $pertanyaanId,
                'jawaban' => $jawaban,
            ]);
        }

        return redirect()->route('peserta.kegiatan.show', $kegiatan)
            ->with('success', 'Kuesioner berhasil diisi. Terima kasih!');
    }
}
