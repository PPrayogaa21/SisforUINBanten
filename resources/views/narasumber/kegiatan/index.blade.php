@extends('layouts.app')
@section('title', 'Kegiatan Narasumber')
@section('page-title', 'Kegiatan Saya')

@section('content')
<div class="space-y-4">
    @forelse($kegiatan as $item)
        @php $isSelesai = $item->status === 'completed'; @endphp
        <{{ $isSelesai ? 'div' : 'a' }} 
            @if(!$isSelesai) 
                href="{{ route('narasumber.kegiatan.show', $item) }}" 
                @if(!auth()->user()->biodata_verified) onclick="event.preventDefault(); openBiodataModal();" @endif
            @endif
            class="block p-5 rounded-2xl bg-white border border-slate-200/50 shadow-sm {{ $isSelesai ? 'opacity-75 cursor-not-allowed' : 'hover:shadow-md hover:border-amber-200 transition-all' }}">
            <div class="flex items-start justify-between gap-4">
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $item->jenis_badge }}">{{ ucfirst($item->jenis) }}</span>
                        @if($isSelesai)
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-slate-100 text-slate-500 border border-slate-200">Selesai</span>
                        @else
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $item->status_badge }}">{{ ucfirst($item->status) }}</span>
                        @endif
                    </div>
                    <h3 class="font-semibold {{ $isSelesai ? 'text-slate-500' : 'text-slate-800' }}">{{ $item->nama_kegiatan }}</h3>
                    <div class="flex flex-wrap gap-4 mt-2 text-xs text-slate-500">
                        <span><i class="fas fa-calendar mr-1 {{ $isSelesai ? 'text-slate-300' : 'text-amber-500' }}"></i>{{ $item->waktu_mulai->translatedFormat('d M Y, H:i') }}</span>
                        <span><i class="fas fa-location-dot mr-1 {{ $isSelesai ? 'text-slate-300' : 'text-amber-500' }}"></i>{{ $item->tempat }}</span>
                        <span><i class="fas fa-users mr-1 {{ $isSelesai ? 'text-slate-300' : 'text-amber-500' }}"></i>{{ $item->peserta->count() }} peserta</span>
                    </div>
                </div>
                @if(!$isSelesai)
                    <i class="fas fa-chevron-right text-slate-300 mt-2"></i>
                @else
                    <div class="mt-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest bg-slate-50 px-2 py-1 rounded-lg border border-slate-100">Arsip</div>
                @endif
            </div>
        </{{ $isSelesai ? 'div' : 'a' }}>
    @empty
    <div class="text-center py-16 text-slate-400">
        <i class="fas fa-calendar-xmark text-4xl mb-3 block text-slate-300"></i>
        Belum ada kegiatan sebagai narasumber
    </div>
    @endforelse
    {{ $kegiatan->links() }}
</div>
@endsection
