@extends('layouts.auth')
@section('title', 'Lengkapi Biodata')

@section('content')
<h2 class="text-xl font-bold text-white mb-1">Lengkapi Biodata</h2>
<p class="text-slate-400 text-sm mb-6">Lengkapi data diri Anda untuk melanjutkan</p>

<form method="POST" action="{{ route('biodata.store') }}" enctype="multipart/form-data" class="space-y-4">
    @csrf

    <div>
        <label class="block text-sm font-medium text-slate-300 mb-1.5">Nama Lengkap <span class="text-red-400">*</span></label>
        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $biodata->nama_lengkap ?? auth()->user()->nama) }}" required
            class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-400/50 transition-all">
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-slate-300 mb-1.5">Jabatan</label>
            <input type="text" name="jabatan" value="{{ old('jabatan', $biodata->jabatan ?? '') }}"
                class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-400/50 transition-all"
                placeholder="Dosen / Staff">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-300 mb-1.5">Bagian</label>
            <input type="text" name="bagian" value="{{ old('bagian', $biodata->bagian ?? '') }}"
                class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-400/50 transition-all"
                placeholder="Fakultas / Unit / Jurusan">
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-slate-300 mb-1.5">Pangkat/Golongan</label>
            <input type="text" name="pangkat_golongan" value="{{ old('pangkat_golongan', $biodata->pangkat_golongan ?? '') }}"
                class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-400/50 transition-all"
                placeholder="III/a">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-300 mb-1.5">No. HP</label>
            <input type="text" name="no_hp" value="{{ old('no_hp', $biodata->no_hp ?? '') }}"
                class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-400/50 transition-all"
                placeholder="08xxxxxxxxxx">
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-300 mb-1.5">Alamat</label>
        <textarea name="alamat" rows="2"
            class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-400/50 transition-all resize-none"
            placeholder="Alamat lengkap">{{ old('alamat', $biodata->alamat ?? '') }}</textarea>
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-300 mb-1.5">Foto Profil</label>
        <input type="file" name="foto" accept="image/*"
            class="w-full text-sm text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-emerald-500/20 file:text-emerald-400 hover:file:bg-emerald-500/30 transition-all">
    </div>

    <button type="submit" class="w-full py-3 px-4 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-500 text-white font-semibold shadow-lg shadow-emerald-500/25 hover:shadow-emerald-500/40 transform hover:-translate-y-0.5 transition-all duration-200">
        <i class="fas fa-save mr-2"></i> Simpan Biodata
    </button>
</form>
@endsection
