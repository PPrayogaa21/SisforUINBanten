@extends('layouts.app')
@section('title', 'Hasil Kuesioner')
@section('page-title', 'Hasil Kuesioner')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-lg font-bold text-slate-800">{{ $kuesioner->judul }}</h2>
            <p class="text-sm text-slate-500">{{ $kegiatan->nama_kegiatan }} · {{ $kuesioner->responses->count() }} responden</p>
        </div>
        <a href="{{ route('admin.kegiatan.kuesioner', $kegiatan) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-semibold text-sm shadow-sm transition-all">
            <i class="fas fa-arrow-left text-slate-400"></i> Kembali
        </a>
    </div>

    <!-- RESPONDENTS LIST CARD -->
    @if($kuesioner->responses->isNotEmpty())
    <div class="p-6 rounded-2xl bg-white border border-slate-200/50 shadow-sm">
        <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
            <i class="fas fa-users text-emerald-500"></i> Daftar Responden
        </h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-slate-500">
                <thead class="text-xs text-slate-700 uppercase bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 font-bold">Responden</th>
                        <th class="px-4 py-3 font-bold">Tanggal Isi</th>
                        <th class="px-4 py-3 font-bold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($kuesioner->responses as $res)
                    <tr class="hover:bg-slate-50/50 transition-all">
                        <td class="px-4 py-3 text-slate-800 font-semibold">
                            {{ $res->user->biodata->nama_lengkap ?? $res->user->username }}
                        </td>
                        <td class="px-4 py-3 text-xs text-slate-500">
                            {{ $res->created_at->translatedFormat('d F Y, H:i') }}
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.kuesioner.response.cetak-pdf', $res) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-800 hover:bg-slate-950 text-white font-bold text-xs rounded-lg shadow-sm transition-colors">
                                <i class="fas fa-print"></i> Cetak PDF
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <h3 class="font-bold text-slate-800 text-base border-b border-slate-100 pb-2 mt-8"><i class="fas fa-chart-pie mr-2 text-blue-500"></i>Ringkasan Averages & Jawaban</h3>

    @foreach($kuesioner->pertanyaan as $p)
    <div class="p-6 rounded-2xl bg-white border border-slate-200/50 shadow-sm">
        <h3 class="font-medium text-slate-800 mb-3">{{ $p->pertanyaan }}</h3>
        @if($p->tipe === 'rating')
            @php
                $jawaban = $p->jawaban->pluck('jawaban')->map(fn($j) => (int)$j);
                $avg = $jawaban->count() > 0 ? round($jawaban->avg(), 1) : 0;
            @endphp
            <div class="flex items-center gap-3">
                <span class="text-3xl font-bold text-emerald-500">{{ $avg }}</span>
                <span class="text-sm text-slate-400">/ 5 rata-rata dari {{ $jawaban->count() }} respons</span>
            </div>
        @else
            <div class="space-y-1">
                @foreach($p->jawaban as $j)
                <p class="text-sm text-slate-600 p-2 rounded-lg bg-slate-50">{{ $j->jawaban }}</p>
                @endforeach
            </div>
        @endif
    </div>
    @endforeach
</div>
@endsection
