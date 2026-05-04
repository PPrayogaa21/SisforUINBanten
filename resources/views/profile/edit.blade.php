@extends('layouts.app')
@section('title', 'Edit Profil')
@section('page-title', 'Pengaturan Profil')

@section('content')
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

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Bagian Foto Profil -->
        <div class="p-6 rounded-2xl bg-white border border-slate-200/50 shadow-sm flex flex-col sm:flex-row gap-6 items-center sm:items-start">
            <div class="relative group shrink-0" style="width: 120px; height: 120px;">
                <div class="w-full h-full rounded-full overflow-hidden border-4 border-white shadow-lg bg-slate-100 flex items-center justify-center">
                    @if($biodata && $biodata->foto)
                        <img src="{{ asset('storage/' . $biodata->foto) }}" alt="Foto Profil" class="w-full h-full object-cover">
                    @else
                        <span class="text-3xl font-bold text-slate-300">{{ strtoupper(substr($biodata->nama_lengkap ?? $user->username ?? 'U', 0, 1)) }}</span>
                    @endif
                </div>
                <label for="foto" class="absolute bottom-0 right-0 w-8 h-8 bg-emerald-500 text-white rounded-full flex items-center justify-center cursor-pointer hover:bg-emerald-600 shadow-md transition-colors">
                    <i class="fas fa-camera text-sm"></i>
                </label>
                <input type="file" name="foto" id="foto" class="hidden" accept="image/*" onchange="previewImage(this)">
            </div>
            <div class="flex-1 text-center sm:text-left">
                <h3 class="font-semibold text-slate-800">Foto Profil</h3>
                <p class="text-sm text-slate-500 mb-3">Upload foto format JPG atau PNG. Ukuran maksimal 2MB.</p>
                @error('foto') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Informasi Utama -->
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
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Nomor HP/WhatsApp</label>
                    <input type="text" name="no_hp" value="{{ old('no_hp', $biodata->no_hp ?? '') }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-sm transition-all bg-slate-50 focus:bg-white">
                    @error('no_hp') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- Pekerjaan & Instansi -->
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
                    <input type="text" name="pangkat_golongan" value="{{ old('pangkat_golongan', $biodata->pangkat_golongan ?? '') }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-sm transition-all bg-slate-50 focus:bg-white">
                </div>
            </div>
        </div>

        <!-- Alamat -->
        <div class="p-6 rounded-2xl bg-white border border-slate-200/50 shadow-sm">
            <h3 class="font-semibold text-slate-800 mb-4 border-b border-slate-100 pb-3">Alamat Lengkap</h3>
            <div>
                <textarea name="alamat" rows="3" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-sm transition-all bg-slate-50 focus:bg-white">{{ old('alamat', $biodata->alamat ?? '') }}</textarea>
            </div>
        </div>

        <!-- Submit -->
        <div class="flex items-center justify-end gap-3 pt-2">
            <button type="reset" class="px-6 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-medium hover:bg-slate-50 transition-colors">Batal</button>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-600 text-white text-sm font-medium hover:bg-emerald-700 shadow-lg shadow-emerald-500/30 transition-all active:scale-95 flex items-center gap-2">
                <i class="fas fa-save"></i> Simpan Perubahan
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var container = input.parentElement.querySelector('div');
                container.innerHTML = '<img src="'+e.target.result+'" class="w-full h-full object-cover">';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
@endsection
