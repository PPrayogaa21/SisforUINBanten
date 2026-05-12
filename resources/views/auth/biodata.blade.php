@extends('layouts.auth')
@section('title', 'Lengkapi Biodata')

@section('content')
<div class="mb-8">
    <h2 class="text-2xl font-black text-slate-900 mb-1">Lengkapi Biodata</h2>
    <p class="text-slate-500 text-sm">Lengkapi data diri Anda untuk melanjutkan ke sistem</p>
</div>

<form method="POST" action="{{ route('biodata.store') }}" enctype="multipart/form-data" class="space-y-5">
    @csrf

    {{-- Nama Lengkap --}}
    <div>
        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $biodata->nama_lengkap ?? '') }}" required
            class="auth-input" placeholder="Masukkan nama lengkap beserta gelar">
        @error('nama_lengkap') <span class="text-xs text-red-500 mt-1 block font-medium">{{ $message }}</span> @enderror
    </div>

    {{-- Email --}}
    <div>
        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Email</label>
        <input type="email" name="email" value="{{ old('email', $biodata->email ?? auth()->user()->email ?? '') }}"
            class="auth-input" placeholder="email@contoh.com">
    </div>

    {{-- Tempat & Tanggal Lahir --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Tempat Lahir</label>
            <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $biodata->tempat_lahir ?? '') }}"
                class="auth-input" placeholder="Contoh: Serang">
        </div>
        <div>
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Tanggal Lahir</label>
            <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $biodata->tanggal_lahir ?? '') }}"
                class="auth-input">
        </div>
    </div>

    {{-- Jabatan & Bagian --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Jabatan</label>
            <input type="text" name="jabatan" value="{{ old('jabatan', $biodata->jabatan ?? '') }}"
                class="auth-input" placeholder="Dosen / Staff / Jabatan Lain">
        </div>
        <div>
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Bagian / Unit Kerja</label>
            <input type="text" name="bagian" value="{{ old('bagian', $biodata->bagian ?? '') }}"
                class="auth-input" placeholder="Fakultas / Unit / Jurusan">
        </div>
    </div>

    {{-- Pangkat & No HP --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Pangkat / Golongan</label>
            <input type="text" name="pangkat_golongan" value="{{ old('pangkat_golongan', $biodata->pangkat_golongan ?? '') }}"
                class="auth-input" placeholder="Contoh: III/a">
        </div>
        <div>
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">No. HP / WhatsApp</label>
            <input type="text" name="no_hp" value="{{ old('no_hp', $biodata->no_hp ?? '') }}"
                class="auth-input" placeholder="08xxxxxxxxxx">
        </div>
    </div>

    {{-- Alamat Rumah --}}
    <div>
        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Alamat Rumah</label>
        <textarea name="alamat_rumah" rows="2"
            class="auth-input resize-none" style="padding-left:16px;"
            placeholder="Alamat rumah lengkap">{{ old('alamat_rumah', $biodata->alamat_rumah ?? '') }}</textarea>
    </div>

    {{-- Alamat Kantor --}}
    <div>
        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Alamat Kantor</label>
        <textarea name="alamat_kantor" rows="2"
            class="auth-input resize-none" style="padding-left:16px;"
            placeholder="Alamat kantor / instansi">{{ old('alamat_kantor', $biodata->alamat_kantor ?? '') }}</textarea>
    </div>

    {{-- Pendidikan --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div>
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Pendidikan S1</label>
            <input type="text" name="pendidikan_s1" value="{{ old('pendidikan_s1', $biodata->pendidikan_s1 ?? '') }}"
                class="auth-input" style="padding-left:16px;" placeholder="Universitas">
        </div>
        <div>
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Pendidikan S2</label>
            <input type="text" name="pendidikan_s2" value="{{ old('pendidikan_s2', $biodata->pendidikan_s2 ?? '') }}"
                class="auth-input" style="padding-left:16px;" placeholder="Universitas">
        </div>
        <div>
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Pendidikan S3</label>
            <input type="text" name="pendidikan_s3" value="{{ old('pendidikan_s3', $biodata->pendidikan_s3 ?? '') }}"
                class="auth-input" style="padding-left:16px;" placeholder="Universitas">
        </div>
    </div>

    {{-- No. Rekening & NPWP --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">No. Rekening</label>
            <input type="text" name="no_rekening" value="{{ old('no_rekening', $biodata->no_rekening ?? '') }}"
                class="auth-input" style="padding-left:16px;" placeholder="Nomor rekening bank">
        </div>
        <div>
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">NPWP</label>
            <input type="text" name="npwp" value="{{ old('npwp', $biodata->npwp ?? '') }}"
                class="auth-input" style="padding-left:16px;" placeholder="Nomor NPWP">
        </div>
    </div>

    {{-- Foto Profil --}}
    <div>
        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Foto Profil</label>
        <div class="mt-2 p-4 border-2 border-dashed border-slate-200 rounded-2xl bg-slate-50 flex items-center gap-4 group hover:border-emerald-500/50 transition-all">
            <div class="w-12 h-12 rounded-xl bg-white flex items-center justify-center text-slate-400 group-hover:text-emerald-500 transition-colors shadow-sm">
                <i class="fas fa-image text-xl"></i>
            </div>
            <div class="flex-1">
                <input type="file" name="foto" accept="image/*"
                    class="block w-full text-xs text-slate-500 file:mr-4 file:py-1.5 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-black file:uppercase file:tracking-widest file:bg-emerald-100 file:text-emerald-700 hover:file:bg-emerald-200 transition-all cursor-pointer">
                <p class="text-[10px] text-slate-400 mt-1 font-medium italic">* Maksimal 2MB, format JPG/PNG</p>
            </div>
        </div>
    </div>

    {{-- Tombol Aksi --}}
    <div class="flex gap-3 pt-4">
        <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('dashboard') }}"
            class="flex-1 py-4 px-4 rounded-xl border border-slate-200 text-slate-600 text-sm font-bold text-center hover:bg-slate-50 transition-all">
            Batal
        </a>
        <button type="submit" class="flex-[2] py-4 px-4 rounded-xl bg-emerald-600 text-white font-black text-sm shadow-lg shadow-emerald-600/20 hover:bg-emerald-700 hover:shadow-emerald-600/40 transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-2">
            <i class="fas fa-check-circle"></i>
            Simpan Biodata
        </button>
    </div>
</form>

<style>
    /* Override padding for inputs that don't use icons in the auth-input class */
    .auth-input { padding-left: 16px !important; }
</style>
@endsection
