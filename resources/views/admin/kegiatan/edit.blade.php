@extends('layouts.app')
@section('title', 'Edit Kegiatan')
@section('page-title', 'Edit Kegiatan')

@push('styles')
@endpush

@section('content')
<div class="max-w-4xl mx-auto">
    <form method="POST" action="{{ route('admin.kegiatan.update', $kegiatan) }}" class="space-y-6">
        @csrf @method('PUT')

        <div class="p-6 rounded-2xl bg-white border border-slate-200/50 shadow-sm space-y-5">
            <h3 class="font-semibold text-slate-800 text-lg flex items-center gap-2"><i class="fas fa-info-circle text-emerald-500"></i> Informasi Kegiatan</h3>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Nama Kegiatan *</label>
                <input type="text" name="nama_kegiatan" value="{{ old('nama_kegiatan', $kegiatan->nama_kegiatan) }}" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-400/50 text-sm">
            </div>

            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Jenis *</label>
                    <select name="jenis" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-400/50 text-sm">
                        @foreach(['rapat','seminar','pelatihan','workshop','lainnya'] as $j)
                        <option value="{{ $j }}" {{ $kegiatan->jenis == $j ? 'selected' : '' }}>{{ ucfirst($j) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Status *</label>
                    <select name="status" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-400/50 text-sm">
                        @foreach(['draft','published','ongoing','completed','cancelled'] as $s)
                        <option value="{{ $s }}" {{ $kegiatan->status == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Deskripsi</label>
                <textarea name="deskripsi" rows="4" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-400/50 text-sm resize-none">{{ old('deskripsi', $kegiatan->deskripsi) }}</textarea>
            </div>
        </div>

        <div class="p-6 rounded-2xl bg-white border border-slate-200/50 shadow-sm space-y-5">
            <h3 class="font-semibold text-slate-800 text-lg flex items-center gap-2"><i class="fas fa-clock text-blue-500"></i> Waktu & Tempat</h3>

            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Waktu Mulai *</label>
                    <input type="datetime-local" name="waktu_mulai" value="{{ old('waktu_mulai', $kegiatan->waktu_mulai->format('Y-m-d\TH:i')) }}" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-400/50 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Waktu Selesai *</label>
                    <input type="datetime-local" name="waktu_selesai" value="{{ old('waktu_selesai', $kegiatan->waktu_selesai->format('Y-m-d\TH:i')) }}" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-400/50 text-sm">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Tempat *</label>
                <input type="text" name="tempat" id="tempat" value="{{ old('tempat', $kegiatan->tempat) }}" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-400/50 text-sm">
            </div>

            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Alamat Lengkap</label>
                    <textarea name="alamat_lengkap" id="alamat_lengkap" rows="4" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-400/50 text-sm resize-none" placeholder="Alamat lengkap lokasi kegiatan">{{ old('alamat_lengkap', $kegiatan->alamat_lengkap) }}</textarea>
                </div>
                <div class="flex flex-col gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Link Google Maps</label>
                        <input type="url" name="link_maps" id="link_maps" value="{{ old('link_maps', $kegiatan->link_maps) }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-400/50 text-sm" placeholder="https://maps.app.goo.gl/...">
                        <div class="flex justify-between items-center mt-1">
                            <p class="text-[10px] text-slate-500">Tempelkan link dari fitur Share di Google Maps.</p>
                            <p id="maps-status" class="text-[10px] font-bold hidden"></p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Latitude (Opsional)</label>
                            <input type="number" step="any" name="latitude" id="latitude" value="{{ old('latitude', $kegiatan->latitude) }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400/50" placeholder="-6.9175">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Longitude (Opsional)</label>
                            <input type="number" step="any" name="longitude" id="longitude" value="{{ old('longitude', $kegiatan->longitude) }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400/50" placeholder="107.6191">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.kegiatan.show', $kegiatan) }}" class="px-6 py-3 rounded-xl border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50 transition-colors flex items-center gap-2">
                <i class="fas fa-arrow-left text-slate-400"></i> Kembali
            </a>
            <button type="submit" class="px-6 py-3 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-500 text-white font-semibold text-sm shadow-lg shadow-emerald-500/20 hover:shadow-emerald-500/30 transition-all flex items-center gap-2">
                <i class="fas fa-save"></i> Update Kegiatan
            </button>
        </div>
    </form>
</div>
@push('scripts')
<script>
    const mapsInput = document.getElementById('link_maps');
    const statusEl = document.getElementById('maps-status');
    const latInput = document.getElementById('latitude');
    const lngInput = document.getElementById('longitude');
    const tempatInput = document.getElementById('tempat');
    const alamatInput = document.getElementById('alamat_lengkap');

    function setStatus(text, colorClass) {
        statusEl.innerText = text;
        statusEl.className = `text-[10px] font-bold ${colorClass}`;
        statusEl.classList.remove('hidden');
    }

    function hideStatus() {
        statusEl.classList.add('hidden');
    }

    mapsInput.addEventListener('input', function(e) {
        const url = e.target.value.trim();
        if (!url) { hideStatus(); return; }

        // 1. Client-side extraction for instant feedback (Long URL format)
        const match = url.match(/@(-?\d+\.\d+),(-?\d+\.\d+)/);
        if (match) {
            latInput.value = match[1];
            lngInput.value = match[2];
            setStatus('✅ Koordinat terdeteksi!', 'text-emerald-600');
        }

        // 2. Trigger server extraction for all Google Maps links to fetch Place/Address
        if (url.includes('google.com/maps') || url.includes('maps.app.goo.gl') || url.includes('goo.gl/maps')) {
            setStatus('🔍 Mendeteksi data...', 'text-blue-600');
            
            fetch('{{ route("admin.kegiatan.extract-maps") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ url: url })
            })
            .then(res => res.json())
            .then(data => {
                let hasData = false;

                if (data.lat && data.lng) {
                    latInput.value = data.lat;
                    lngInput.value = data.lng;
                    hasData = true;
                }
                // Fill Address automatically and overwrite previous
                if (data.alamat) {
                    alamatInput.value = data.alamat;
                    hasData = true;
                }

                if (hasData) {
                    setStatus('✅ Data berhasil dideteksi!', 'text-emerald-600');
                    
                    // Visual bounce highlight
                    [latInput, lngInput, alamatInput].forEach(el => {
                        if (!el) return;
                        el.classList.add('bg-emerald-50', 'ring-2', 'ring-emerald-200');
                        setTimeout(() => el.classList.remove('bg-emerald-50', 'ring-2', 'ring-emerald-200'), 1500);
                    });
                } else {
                     setStatus('⚠️ Data tidak ditemukan', 'text-amber-500');
                }
            })
            .catch(() => {
                setStatus('⚠️ Gangguan koneksi', 'text-red-500');
            });
        }
    });
</script>
@endpush

@endsection
