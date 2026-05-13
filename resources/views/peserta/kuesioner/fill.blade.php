@extends('layouts.app')
@section('title', 'Isi Kuesioner')
@section('page-title', 'Kuesioner Evaluasi')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header Banner -->
    <div class="mb-6 overflow-hidden rounded-2xl bg-white border border-slate-200/60 shadow-sm">
        <div class="px-6 py-4 bg-slate-800 text-white">
            <h2 class="font-bold uppercase tracking-wider text-sm flex items-center gap-2">
                <i class="fas fa-clipboard-list"></i> EVALUASI KEGIATAN
            </h2>
        </div>
        <div class="p-6 bg-slate-50/50">
            <p class="text-sm text-slate-600 italic font-medium">Isilah secara lengkap kuesioner dibawah ini untuk membantu kami meningkatkan mutu kegiatan berikutnya.</p>
            <p class="text-xs text-slate-500 mt-1 font-bold">{{ $kegiatan->nama_kegiatan }}</p>
        </div>
    </div>

    <form method="POST" action="{{ route('peserta.kuesioner.submit', [$kegiatan, $kuesioner]) }}" class="space-y-6">
        @csrf

        @php
            $categories = [
                'Narasumber' => 'Narasumber',
                'Materi yang disampaikan' => 'Materi yang disampaikan',
                'Akomodasi / Tempat Kegiatan' => 'Akomodasi / Tempat Kegiatan',
                'Konsumsi' => 'Konsumsi',
                'Pelayanan Panitia' => 'Pelayanan Panitia',
                'Penilaian Keseluruhan' => 'Penilaian terhadap kegiatan secara keseluruhan'
            ];
            $sectionIndex = 1;
        @endphp

        @foreach($categories as $dbCat => $displayCat)
            @php
                $questions = $kuesioner->pertanyaan->filter(function($p) use ($dbCat) {
                    return isset($p->opsi['kategori']) && $p->opsi['kategori'] === $dbCat;
                })->sortBy('urutan');

                $ratings = $questions->filter(fn($p) => $p->tipe === 'rating');
                $comments = $questions->filter(fn($p) => $p->tipe === 'text');
            @endphp

            @if($questions->isNotEmpty())
                <div class="space-y-4">
                    <!-- Ratings Table Card -->
                    <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm p-6">
                        <h3 class="font-bold text-slate-800 mb-6 flex items-center gap-1">
                            {{ $sectionIndex }}. {{ $displayCat }} <span class="text-red-500 font-bold text-lg">*</span>
                        </h3>

                        <div class="overflow-x-auto -mx-6 sm:mx-0">
                            <table class="w-full text-sm border-collapse">
                                <thead>
                                    <tr>
                                        <th class="w-2/5"></th>
                                        <th class="pb-4 px-2 text-center text-xs font-bold text-slate-600 align-bottom min-w-[70px]">Tidak Baik</th>
                                        <th class="pb-4 px-2 text-center text-xs font-bold text-slate-600 align-bottom min-w-[70px]">Kurang Baik</th>
                                        <th class="pb-4 px-2 text-center text-xs font-bold text-slate-600 align-bottom min-w-[70px]">Cukup</th>
                                        <th class="pb-4 px-2 text-center text-xs font-bold text-slate-600 align-bottom min-w-[70px]">Baik</th>
                                        <th class="pb-4 px-2 text-center text-xs font-bold text-slate-600 align-bottom min-w-[70px]">Sangat Baik</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach($ratings as $p)
                                        <tr class="hover:bg-slate-50/80 transition-colors group">
                                            <td class="py-4 pr-4 font-medium text-slate-700">{{ $p->pertanyaan }}</td>
                                            @for($r = 1; $r <= 5; $r++)
                                                <td class="py-4 px-2 text-center">
                                                    <label class="relative inline-flex items-center justify-center p-2 group/cell cursor-pointer select-none">
                                                        <input type="radio" name="jawaban[{{ $p->id }}]" value="{{ $r }}" required 
                                                            class="peer sr-only">
                                                        <!-- Outer Ring -->
                                                        <div class="w-6 h-6 rounded-full border-2 border-slate-300 flex items-center justify-center peer-checked:border-slate-800 peer-checked:bg-white transition-all group-hover/cell:border-slate-500">
                                                            <!-- Inner Dot -->
                                                            <div class="w-3 h-3 rounded-full bg-transparent peer-checked:bg-slate-800 transition-all transform scale-0 peer-checked:scale-100"></div>
                                                        </div>
                                                    </label>
                                                </td>
                                            @endfor
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Comment Box Card (Indented Below the table card) -->
                    @foreach($comments as $p)
                        <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm p-6">
                            <label for="comment_{{ $p->id }}" class="block font-bold text-slate-800 mb-3 text-sm">
                                {{ $p->pertanyaan }}
                                @if($dbCat === 'Narasumber' || $dbCat === 'Penilaian Keseluruhan')
                                    <span class="text-red-500 font-bold">*</span>
                                @endif
                            </label>
                            <textarea 
                                id="comment_{{ $p->id }}" 
                                name="jawaban[{{ $p->id }}]" 
                                {{ ($dbCat === 'Narasumber' || $dbCat === 'Penilaian Keseluruhan') ? 'required' : '' }}
                                rows="2" 
                                class="w-full border-b-2 border-t-0 border-x-0 border-slate-200 focus:border-slate-800 focus:ring-0 px-0 py-2 text-sm bg-transparent transition-all resize-none placeholder:text-slate-300" 
                                placeholder="Tuliskan tanggapan atau jawaban Anda disini..."></textarea>
                        </div>
                    @endforeach
                    @php $sectionIndex++; @endphp
                </div>
            @endif
        @endforeach

        <!-- Global Custom Input Injection Logic (Fallback for custom added admin questions outside template) -->
        @php
            $otherQuestions = $kuesioner->pertanyaan->filter(function($p) {
                return !isset($p->opsi['kategori']) || !in_array($p->opsi['kategori'], ['Narasumber', 'Materi yang disampaikan', 'Konsumsi', 'Pelayanan Panitia', 'Penilaian Keseluruhan']);
            });
        @endphp

        @if($otherQuestions->isNotEmpty())
            <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm p-6 space-y-4">
                <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-1">Pertanyaan Tambahan</h3>
                @foreach($otherQuestions as $p)
                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                        <p class="font-medium text-slate-800 mb-3 text-sm">{{ $p->pertanyaan }}</p>
                        @if($p->tipe === 'rating')
                            <div class="flex gap-3">
                                @for($r = 1; $r <= 5; $r++)
                                <label class="cursor-pointer">
                                    <input type="radio" name="jawaban[{{ $p->id }}]" value="{{ $r }}" required class="hidden peer">
                                    <div class="w-10 h-10 rounded-xl border-2 border-slate-200 flex items-center justify-center text-sm font-bold text-slate-400 peer-checked:border-slate-800 peer-checked:text-slate-800 hover:border-slate-300 transition-all">{{ $r }}</div>
                                </label>
                                @endfor
                            </div>
                        @else
                            <textarea name="jawaban[{{ $p->id }}]" required rows="2" class="w-full border-b-2 border-t-0 border-x-0 border-slate-200 focus:border-slate-800 focus:ring-0 text-sm bg-transparent py-2 resize-none" placeholder="Jawaban Anda..."></textarea>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Submit Button Container -->
        <div class="flex items-center justify-between py-6">
            <a href="{{ route('peserta.kegiatan.show', $kegiatan) }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 font-semibold text-sm shadow-sm transition-all">
                <i class="fas fa-arrow-left"></i> Kembali ke Detail
            </a>
            <button type="submit" class="inline-flex items-center gap-2 px-8 py-3 rounded-xl bg-slate-800 text-white font-bold text-sm shadow-lg hover:bg-slate-900 transition-all transform active:scale-95">
                <i class="fas fa-paper-plane"></i> Kirim Kuesioner
            </button>
        </div>
    </form>
</div>

<style>
    /* Custom Radio Visual Logic simulating the paper bullet point inside */
    input[type="radio"]:checked + div {
        border-color: #1e293b !important; /* slate-800 */
    }
    input[type="radio"]:checked + div > div {
        background-color: #1e293b !important;
        transform: scale(1) !important;
    }
</style>
@endsection
