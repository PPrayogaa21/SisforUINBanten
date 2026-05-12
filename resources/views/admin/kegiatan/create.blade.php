@extends('layouts.app')
@section('title', 'Buat Kegiatan')
@section('page-title', 'Buat Kegiatan Baru')

@push('styles')
@endpush

@section('content')
<div class="max-w-4xl mx-auto">
    <form method="POST" action="{{ route('admin.kegiatan.store') }}" class="space-y-6">
        @csrf

        <div class="p-6 rounded-2xl bg-white border border-slate-200/50 shadow-sm space-y-5">
            <h3 class="font-semibold text-slate-800 text-lg flex items-center gap-2"><i class="fas fa-info-circle text-emerald-500"></i> Informasi Kegiatan</h3>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Nama Kegiatan <span class="text-red-400">*</span></label>
                <input type="text" name="nama_kegiatan" value="{{ old('nama_kegiatan') }}" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-400/50 text-sm" placeholder="Contoh: Seminar Nasional Teknologi 2026">
            </div>

            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Jenis Kegiatan <span class="text-red-400">*</span></label>
                    <select name="jenis" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-400/50 text-sm">
                        <option value="rapat" {{ old('jenis') == 'rapat' ? 'selected' : '' }}>Rapat</option>
                        <option value="seminar" {{ old('jenis') == 'seminar' ? 'selected' : '' }}>Seminar</option>
                        <option value="pelatihan" {{ old('jenis') == 'pelatihan' ? 'selected' : '' }}>Pelatihan</option>
                        <option value="workshop" {{ old('jenis') == 'workshop' ? 'selected' : '' }}>Workshop</option>
                        <option value="lainnya" {{ old('jenis') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Status <span class="text-red-400">*</span></label>
                    <select name="status" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-400/50 text-sm">
                        <option value="draft">Draft</option>
                        <option value="published">Published</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Deskripsi</label>
                <textarea name="deskripsi" rows="4" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-400/50 text-sm resize-none" placeholder="Deskripsi kegiatan...">{{ old('deskripsi') }}</textarea>
            </div>
        </div>

        <div class="p-6 rounded-2xl bg-white border border-slate-200/50 shadow-sm space-y-5">
            <h3 class="font-semibold text-slate-800 text-lg flex items-center gap-2"><i class="fas fa-clock text-blue-500"></i> Waktu & Tempat</h3>

            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Waktu Mulai <span class="text-red-400">*</span></label>
                    <input type="datetime-local" name="waktu_mulai" value="{{ old('waktu_mulai') }}" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-400/50 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Waktu Selesai <span class="text-red-400">*</span></label>
                    <input type="datetime-local" name="waktu_selesai" value="{{ old('waktu_selesai') }}" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-400/50 text-sm">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Tempat <span class="text-red-400">*</span></label>
                <input type="text" name="tempat" value="{{ old('tempat') }}" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-400/50 text-sm" placeholder="Nama gedung / ruangan">
            </div>

            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Alamat Lengkap</label>
                    <textarea name="alamat_lengkap" id="alamat_lengkap" rows="4" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-400/50 text-sm resize-none" placeholder="Alamat lengkap lokasi kegiatan">{{ old('alamat_lengkap') }}</textarea>
                </div>
                <div class="flex flex-col gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Link Google Maps</label>
                        <input type="url" name="link_maps" value="{{ old('link_maps') }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-400/50 text-sm" placeholder="https://maps.app.goo.gl/...">
                        <p class="text-xs text-slate-500 mt-1">Tempelkan link dari fitur Share di Google Maps.</p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Latitude (Opsional)</label>
                            <input type="number" step="any" name="latitude" id="latitude" value="{{ old('latitude') }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400/50" placeholder="-6.9175">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Longitude (Opsional)</label>
                            <input type="number" step="any" name="longitude" id="longitude" value="{{ old('longitude') }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400/50" placeholder="107.6191">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.kegiatan.index') }}" class="px-6 py-3 rounded-xl border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50 transition-colors flex items-center gap-2">
                <i class="fas fa-arrow-left text-slate-400"></i> Kembali
            </a>
            <button type="submit" class="px-6 py-3 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-500 text-white font-semibold text-sm shadow-lg shadow-emerald-500/20 hover:shadow-emerald-500/30 transition-all flex items-center gap-2">
                <i class="fas fa-save"></i> Simpan Kegiatan
            </button>
        </div>
    </form>
</div>

@endsection
