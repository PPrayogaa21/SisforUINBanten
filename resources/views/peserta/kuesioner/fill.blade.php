@extends('layouts.app')
@section('title', 'Isi Kuesioner')
@section('page-title', 'Kuesioner Evaluasi')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <h2 class="text-lg font-bold text-slate-800">{{ $kuesioner->judul }}</h2>
        <p class="text-sm text-slate-500">{{ $kegiatan->nama_kegiatan }}</p>
    </div>

    <form method="POST" action="{{ route('peserta.kuesioner.submit', [$kegiatan, $kuesioner]) }}" class="space-y-4">
        @csrf

        @foreach($kuesioner->pertanyaan as $i => $p)
        <div class="p-6 rounded-2xl bg-white border border-slate-200/50 shadow-sm">
            <p class="font-medium text-slate-800 mb-3">{{ $i + 1 }}. {{ $p->pertanyaan }}</p>

            @if($p->tipe === 'rating')
            <div class="flex gap-3">
                @for($r = 1; $r <= 5; $r++)
                <label class="cursor-pointer">
                    <input type="radio" name="jawaban[{{ $p->id }}]" value="{{ $r }}" required class="hidden peer">
                    <div class="w-12 h-12 rounded-xl border-2 border-slate-200 flex items-center justify-center text-lg font-bold text-slate-400 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 peer-checked:text-emerald-600 hover:border-emerald-300 transition-all">{{ $r }}</div>
                </label>
                @endfor
            </div>
            <p class="text-xs text-slate-400 mt-2">1 = Sangat Buruk, 5 = Sangat Baik</p>
            @elseif($p->tipe === 'text')
            <textarea name="jawaban[{{ $p->id }}]" required rows="3" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400/50 resize-none" placeholder="Tulis jawaban Anda..."></textarea>
            @elseif($p->tipe === 'pilihan_ganda' && $p->opsi)
            <div class="space-y-2">
                @foreach($p->opsi as $opsi)
                <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 cursor-pointer hover:border-emerald-300 has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50 transition-all">
                    <input type="radio" name="jawaban[{{ $p->id }}]" value="{{ $opsi }}" required class="text-emerald-500 focus:ring-emerald-400">
                    <span class="text-sm text-slate-700">{{ $opsi }}</span>
                </label>
                @endforeach
            </div>
            @endif
        </div>
        @endforeach

        <div class="flex justify-end gap-3">
            <a href="{{ route('peserta.kegiatan.show', $kegiatan) }}" class="px-6 py-3 rounded-xl border border-slate-200 text-slate-600 font-medium text-sm">Batal</a>
            <button type="submit" class="px-6 py-3 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-500 text-white font-medium text-sm shadow-lg shadow-emerald-500/20">
                <i class="fas fa-paper-plane mr-2"></i> Kirim Jawaban
            </button>
        </div>
    </form>
</div>
@endsection
