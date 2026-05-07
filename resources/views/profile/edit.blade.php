@extends('layouts.app')
@section('title', 'Edit Profil')
@section('page-title', 'Pengaturan Profil')

@section('content')
@php
    $isNarasumber = auth()->user()->kegiatanSebagaiNarasumber()->exists() || (auth()->user()->biodata && strtoupper(auth()->user()->biodata->ket) === 'NARASUMBER');
@endphp
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center gap-4 mb-6">
        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center text-white shadow-lg shadow-emerald-500/30">
            <i class="fas fa-user-edit text-2xl"></i>
        </div>
        <div>
            <h2 class="text-xl font-bold text-slate-800">Profil Saya</h2>
            <p class="text-sm text-slate-500">Perbarui informasi pribadi dan biodata Anda.</p>
        </div>
    </div>

    @if(session('success'))
    <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm flex items-center gap-2">
        <i class="fas fa-check-circle text-emerald-500"></i> {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm flex items-center gap-2">
        <i class="fas fa-times-circle text-red-500"></i> {{ session('error') }}
    </div>
    @endif

    @if($isNarasumber)
    <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-sm flex items-start gap-3">
        <i class="fas fa-info-circle text-amber-500 mt-0.5 text-base"></i>
        <div>
            <p class="font-semibold text-slate-800">Mode Lihat-Saja (Read-Only)</p>
            <p class="text-xs text-amber-700 mt-0.5">Sebagai Narasumber, Anda tidak diperbolehkan mengedit biodata sendiri. Silakan hubungi Administrator jika terdapat perubahan atau pembaruan data profil.</p>
        </div>
    </div>
    @endif

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <fieldset @if($isNarasumber) disabled class="opacity-85" @endif class="space-y-6">

        {{-- Foto Profil --}}
        <div class="p-6 rounded-2xl bg-white border border-slate-200/50 shadow-sm flex flex-col sm:flex-row gap-6 items-center sm:items-start">
            <div class="relative group shrink-0" style="width: 120px; height: 120px;">
                <div id="foto-preview" class="w-full h-full rounded-full overflow-hidden border-4 border-white shadow-lg bg-slate-100 flex items-center justify-center">
                    @if($biodata && $biodata->foto)
                        <img src="{{ asset('storage/' . $biodata->foto) }}" alt="Foto Profil" class="w-full h-full object-cover">
                    @else
                        <span class="text-3xl font-bold text-slate-300">{{ strtoupper(substr($biodata->nama_lengkap ?? $user->username ?? 'U', 0, 1)) }}</span>
                    @endif
                </div>
                @if(!$isNarasumber)
                <label for="foto" class="absolute bottom-0 right-0 w-8 h-8 bg-emerald-500 text-white rounded-full flex items-center justify-center cursor-pointer hover:bg-emerald-600 shadow-md transition-colors">
                    <i class="fas fa-camera text-sm"></i>
                </label>
                <input type="file" name="foto" id="foto" class="hidden" accept="image/*" onchange="previewImage(this)">
                @endif
            </div>
            <div class="flex-1 text-center sm:text-left">
                <h3 class="font-semibold text-slate-800">Foto Profil</h3>
                <p class="text-sm text-slate-500 mb-3">Upload foto format JPG atau PNG. Ukuran maksimal 2MB.</p>
                @error('foto') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>
        </div>

        {{-- Informasi Utama --}}
        <div class="p-6 rounded-2xl bg-white border border-slate-200/50 shadow-sm">
            <h3 class="font-semibold text-slate-800 mb-4 border-b border-slate-100 pb-3">Informasi Akun & Kontak</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $biodata->nama_lengkap ?? '') }}" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-sm transition-all bg-slate-50 focus:bg-white">
                    @error('nama_lengkap') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">NIP / Username</label>
                    <input type="text" value="{{ $biodata->nip ?? $user->username }}" disabled class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-100 text-slate-500 text-sm cursor-not-allowed">
                    <p class="text-[11px] text-slate-400 mt-1">Hubungi admin untuk mengubah NIP/Username.</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Email</label>
                    <input type="email" name="email" value="{{ old('email', $biodata->email ?? $user->email ?? '') }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-sm transition-all bg-slate-50 focus:bg-white" placeholder="email@contoh.com">
                    @error('email') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Nomor HP / WhatsApp</label>
                    <input type="text" name="no_hp" value="{{ old('no_hp', $biodata->no_hp ?? '') }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-sm transition-all bg-slate-50 focus:bg-white" placeholder="08xxxxxxxxxx">
                    @error('no_hp') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        {{-- Tempat & Tanggal Lahir --}}
        <div class="p-6 rounded-2xl bg-white border border-slate-200/50 shadow-sm">
            <h3 class="font-semibold text-slate-800 mb-4 border-b border-slate-100 pb-3">Data Kelahiran</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $biodata->tempat_lahir ?? '') }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-sm transition-all bg-slate-50 focus:bg-white" placeholder="Serang">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $biodata->tanggal_lahir ?? '') }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-sm transition-all bg-slate-50 focus:bg-white">
                </div>
            </div>
        </div>

        {{-- Pekerjaan & Instansi --}}
        <div class="p-6 rounded-2xl bg-white border border-slate-200/50 shadow-sm">
            <h3 class="font-semibold text-slate-800 mb-4 border-b border-slate-100 pb-3">Pekerjaan & Instansi</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Jabatan</label>
                    <input type="text" name="jabatan" value="{{ old('jabatan', $biodata->jabatan ?? '') }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-sm transition-all bg-slate-50 focus:bg-white">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Bagian / Unit Kerja</label>
                    <input type="text" name="bagian" value="{{ old('bagian', $biodata->bagian ?? '') }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-sm transition-all bg-slate-50 focus:bg-white">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Pangkat / Golongan</label>
                    <input type="text" name="pangkat_golongan" value="{{ old('pangkat_golongan', $biodata->pangkat_golongan ?? '') }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-sm transition-all bg-slate-50 focus:bg-white" placeholder="III/a">
                </div>
            </div>
        </div>

        {{-- Alamat --}}
        <div class="p-6 rounded-2xl bg-white border border-slate-200/50 shadow-sm">
            <h3 class="font-semibold text-slate-800 mb-4 border-b border-slate-100 pb-3">Alamat</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Alamat Rumah</label>
                    <textarea name="alamat_rumah" rows="2" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-sm transition-all bg-slate-50 focus:bg-white resize-none" placeholder="Alamat rumah lengkap">{{ old('alamat_rumah', $biodata->alamat_rumah ?? '') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Alamat Kantor</label>
                    <textarea name="alamat_kantor" rows="2" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-sm transition-all bg-slate-50 focus:bg-white resize-none" placeholder="Alamat kantor / instansi">{{ old('alamat_kantor', $biodata->alamat_kantor ?? '') }}</textarea>
                </div>
            </div>
        </div>

        {{-- Pendidikan --}}
        <div class="p-6 rounded-2xl bg-white border border-slate-200/50 shadow-sm">
            <h3 class="font-semibold text-slate-800 mb-4 border-b border-slate-100 pb-3">Riwayat Pendidikan</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Pendidikan S1</label>
                    <input type="text" name="pendidikan_s1" value="{{ old('pendidikan_s1', $biodata->pendidikan_s1 ?? '') }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-sm transition-all bg-slate-50 focus:bg-white" placeholder="Nama Universitas">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Pendidikan S2</label>
                    <input type="text" name="pendidikan_s2" value="{{ old('pendidikan_s2', $biodata->pendidikan_s2 ?? '') }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-sm transition-all bg-slate-50 focus:bg-white" placeholder="Nama Universitas">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Pendidikan S3</label>
                    <input type="text" name="pendidikan_s3" value="{{ old('pendidikan_s3', $biodata->pendidikan_s3 ?? '') }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-sm transition-all bg-slate-50 focus:bg-white" placeholder="Nama Universitas">
                </div>
            </div>
        </div>

        {{-- Keuangan --}}
        <div class="p-6 rounded-2xl bg-white border border-slate-200/50 shadow-sm">
            <h3 class="font-semibold text-slate-800 mb-4 border-b border-slate-100 pb-3">Data Keuangan</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">No. Rekening</label>
                    <input type="text" name="no_rekening" value="{{ old('no_rekening', $biodata->no_rekening ?? '') }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-sm transition-all bg-slate-50 focus:bg-white" placeholder="Nomor rekening bank">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">NPWP</label>
                    <input type="text" name="npwp" value="{{ old('npwp', $biodata->npwp ?? '') }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-sm transition-all bg-slate-50 focus:bg-white" placeholder="Nomor NPWP">
                </div>
            </div>
        </div>

        </fieldset>

        {{-- Tombol Aksi --}}
        <div class="flex items-center justify-end gap-3 pt-2">
            @if($isNarasumber)
                <a href="{{ route('dashboard') }}"
                   class="px-6 py-2.5 rounded-xl bg-slate-100 border border-slate-200 text-slate-700 text-sm font-semibold hover:bg-slate-200 transition-colors flex items-center gap-2">
                    <i class="fas fa-arrow-left"></i> Kembali ke Beranda
                </a>
            @else
                <a href="{{ url()->previous() != url()->current() ? url()->previous() : route('dashboard') }}"
                   class="px-6 py-2.5 rounded-xl bg-slate-100 text-slate-600 text-sm font-semibold hover:bg-slate-200 hover:text-slate-700 transition-all flex items-center gap-2 border border-slate-200/50">
                    <i class="fas fa-times"></i> Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-600 text-white text-sm font-medium hover:bg-emerald-700 shadow-lg shadow-emerald-500/30 transition-all active:scale-95 flex items-center gap-2">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
            @endif
        </div>
    </form>
</div>

@push('scripts')
<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var container = document.getElementById('foto-preview');
                container.innerHTML = '<img src="'+e.target.result+'" class="w-full h-full object-cover">';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
@endsection
