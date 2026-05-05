@extends('layouts.auth')
@section('title', 'Lengkapi Biodata')

@section('content')
<h2 class="text-xl font-bold text-white mb-1">Lengkapi Biodata</h2>
<p class="text-slate-400 text-sm mb-6">Lengkapi data diri Anda untuk melanjutkan</p>

<form method="POST" action="{{ route('biodata.store') }}" enctype="multipart/form-data" class="space-y-4">
    @csrf

    {{-- Nama Lengkap --}}
    <div>
        <label class="block text-sm font-medium text-slate-300 mb-1.5">Nama Lengkap <span class="text-red-400">*</span></label>
        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $biodata->nama_lengkap ?? '') }}" required
            class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-400/50 transition-all">
        @error('nama_lengkap') <span class="text-xs text-red-400 mt-1 block">{{ $message }}</span> @enderror
    </div>

    {{-- Email --}}
    <div>
        <label class="block text-sm font-medium text-slate-300 mb-1.5">Email</label>
        <input type="email" name="email" value="{{ old('email', $biodata->email ?? auth()->user()->email ?? '') }}"
            class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-400/50 transition-all"
            placeholder="email@contoh.com">
    </div>

    {{-- Tempat & Tanggal Lahir --}}
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-slate-300 mb-1.5">Tempat Lahir</label>
            <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $biodata->tempat_lahir ?? '') }}"
                class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-400/50 transition-all"
                placeholder="Serang">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-300 mb-1.5">Tanggal Lahir</label>
            <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $biodata->tanggal_lahir ?? '') }}"
                class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-400/50 transition-all">
        </div>
    </div>

    {{-- Jabatan & Bagian --}}
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-slate-300 mb-1.5">Jabatan</label>
            <input type="text" name="jabatan" value="{{ old('jabatan', $biodata->jabatan ?? '') }}"
                class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-400/50 transition-all"
                placeholder="Dosen / Staff">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-300 mb-1.5">Bagian / Unit Kerja</label>
            <input type="text" name="bagian" value="{{ old('bagian', $biodata->bagian ?? '') }}"
                class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-400/50 transition-all"
                placeholder="Fakultas / Unit / Jurusan">
        </div>
    </div>

    {{-- Pangkat & No HP --}}
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-slate-300 mb-1.5">Pangkat / Golongan</label>
            <input type="text" name="pangkat_golongan" value="{{ old('pangkat_golongan', $biodata->pangkat_golongan ?? '') }}"
                class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-400/50 transition-all"
                placeholder="III/a">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-300 mb-1.5">No. HP / WhatsApp</label>
            <input type="text" name="no_hp" value="{{ old('no_hp', $biodata->no_hp ?? '') }}"
                class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-400/50 transition-all"
                placeholder="08xxxxxxxxxx">
        </div>
    </div>

    {{-- Alamat Rumah --}}
    <div>
        <label class="block text-sm font-medium text-slate-300 mb-1.5">Alamat Rumah</label>
        <textarea name="alamat_rumah" rows="2"
            class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-400/50 transition-all resize-none"
            placeholder="Alamat rumah lengkap">{{ old('alamat_rumah', $biodata->alamat_rumah ?? '') }}</textarea>
    </div>

    {{-- Alamat Kantor --}}
    <div>
        <label class="block text-sm font-medium text-slate-300 mb-1.5">Alamat Kantor</label>
        <textarea name="alamat_kantor" rows="2"
            class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-400/50 transition-all resize-none"
            placeholder="Alamat kantor / instansi">{{ old('alamat_kantor', $biodata->alamat_kantor ?? '') }}</textarea>
    </div>

    {{-- Pendidikan --}}
    <div class="grid grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium text-slate-300 mb-1.5">Pendidikan S1</label>
            <input type="text" name="pendidikan_s1" value="{{ old('pendidikan_s1', $biodata->pendidikan_s1 ?? '') }}"
                class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-400/50 transition-all"
                placeholder="Nama Universitas">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-300 mb-1.5">Pendidikan S2</label>
            <input type="text" name="pendidikan_s2" value="{{ old('pendidikan_s2', $biodata->pendidikan_s2 ?? '') }}"
                class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-400/50 transition-all"
                placeholder="Nama Universitas">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-300 mb-1.5">Pendidikan S3</label>
            <input type="text" name="pendidikan_s3" value="{{ old('pendidikan_s3', $biodata->pendidikan_s3 ?? '') }}"
                class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-400/50 transition-all"
                placeholder="Nama Universitas">
        </div>
    </div>

    {{-- No. Rekening & NPWP --}}
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-slate-300 mb-1.5">No. Rekening</label>
            <input type="text" name="no_rekening" value="{{ old('no_rekening', $biodata->no_rekening ?? '') }}"
                class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-400/50 transition-all"
                placeholder="Nomor rekening bank">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-300 mb-1.5">NPWP</label>
            <input type="text" name="npwp" value="{{ old('npwp', $biodata->npwp ?? '') }}"
                class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-400/50 transition-all"
                placeholder="Nomor NPWP">
        </div>
    </div>

    {{-- Foto Profil --}}
    <div>
        <label class="block text-sm font-medium text-slate-300 mb-1.5">Foto Profil</label>
        <input type="file" name="foto" accept="image/*"
            class="w-full text-sm text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-emerald-500/20 file:text-emerald-400 hover:file:bg-emerald-500/30 transition-all">
    </div>

    {{-- Tombol Aksi --}}
    <div class="flex gap-3 pt-2">
        <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('dashboard') }}"
            class="flex-1 py-3 px-4 rounded-xl border border-white/10 text-slate-300 text-sm font-medium text-center hover:bg-white/5 transition-all">
            <i class="fas fa-times mr-2"></i>Batal
        </a>
        <button type="submit" class="flex-1 py-3 px-4 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-500 text-white font-semibold shadow-lg shadow-emerald-500/25 hover:shadow-emerald-500/40 transform hover:-translate-y-0.5 transition-all duration-200">
            <i class="fas fa-save mr-2"></i>Simpan Biodata
        </button>
    </div>
</form>
@endsection
