@extends('layouts.app')
@section('title', 'Edit Kegiatan')
@section('page-title', 'Edit Kegiatan')

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
                <input type="text" name="tempat" value="{{ old('tempat', $kegiatan->tempat) }}" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-400/50 text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Alamat Lengkap</label>
                <textarea name="alamat_lengkap" rows="2" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-400/50 text-sm resize-none">{{ old('alamat_lengkap', $kegiatan->alamat_lengkap) }}</textarea>
            </div>

            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Latitude</label>
                    <input type="number" step="any" name="latitude" value="{{ old('latitude', $kegiatan->latitude) }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-400/50 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Longitude</label>
                    <input type="number" step="any" name="longitude" value="{{ old('longitude', $kegiatan->longitude) }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-400/50 text-sm">
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.kegiatan.show', $kegiatan) }}" class="px-6 py-3 rounded-xl border border-slate-200 text-slate-600 font-medium text-sm hover:bg-slate-50 transition-colors">Batal</a>
            <button type="submit" class="px-6 py-3 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-500 text-white font-medium text-sm shadow-lg shadow-emerald-500/20 transition-all">
                <i class="fas fa-save mr-2"></i> Update Kegiatan
            </button>
        </div>
    </form>
</div>
@endsection
