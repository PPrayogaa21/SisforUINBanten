@extends('layouts.app')
@section('title', 'Edit User')
@section('page-title', 'Edit User')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl border border-slate-200/50 shadow-sm overflow-hidden">
        <div class="p-6 sm:p-8">
            <h2 class="text-xl font-bold text-slate-800 mb-6">Edit Informasi User</h2>
            
            <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-8">
                @csrf
                @method('PUT')
                
                <!-- Section: Akun Login -->
                <div class="space-y-4">
                    <h3 class="text-sm font-bold text-slate-800 border-b border-slate-200 pb-2">Informasi Akun Login</h3>
                    <div class="grid sm:grid-cols-3 gap-6">
                        <div class="space-y-2">
                            <label class="text-xs font-medium text-slate-700">Username / ID <span class="text-red-500">*</span></label>
                            <input type="text" name="username" value="{{ old('username', $user->username) }}" required 
                                class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 @error('username') border-red-500 @enderror">
                            @error('username') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-medium text-slate-700">Email <span class="text-red-500">*</span></label>
                            <input type="email" name="email" value="{{ old('email', $user->biodata->email ?? '') }}" required 
                                class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 @error('email') border-red-500 @enderror">
                            @error('email') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-medium text-slate-700">Role Akses <span class="text-red-500">*</span></label>
                            <select name="role" required class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500">
                                <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User (Peserta/Narasumber)</option>
                                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                            @error('role') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Section: Data Pribadi -->
                <div class="space-y-4">
                    <h3 class="text-sm font-bold text-slate-800 border-b border-slate-200 pb-2">Data Pribadi</h3>
                    <div class="grid sm:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-xs font-medium text-slate-700">Nama Lengkap & Gelar <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $user->biodata->nama_lengkap ?? '') }}" required 
                                class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 @error('nama_lengkap') border-red-500 @enderror">
                            @error('nama_lengkap') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-medium text-slate-700">No. Handphone</label>
                            <input type="text" name="no_hp" value="{{ old('no_hp', $user->biodata->no_hp ?? '') }}" 
                                class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-medium text-slate-700">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $user->biodata->tempat_lahir ?? '') }}" 
                                class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-medium text-slate-700">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $user->biodata->tanggal_lahir ?? '') }}" 
                                class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500">
                        </div>
                    </div>
                </div>

                <!-- Section: Kepegawaian -->
                <div class="space-y-4">
                    <h3 class="text-sm font-bold text-slate-800 border-b border-slate-200 pb-2">Informasi Kepegawaian</h3>
                    <div class="grid sm:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-xs font-medium text-slate-700">NIP</label>
                            <input type="text" name="nip" value="{{ old('nip', $user->biodata->nip ?? '') }}" 
                                class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-medium text-slate-700">Pangkat (Gol/Ruang)</label>
                            <input type="text" name="pangkat_golongan" value="{{ old('pangkat_golongan', $user->biodata->pangkat_golongan ?? '') }}" 
                                class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500" placeholder="Misal: Penata Tk. I (III/d)">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-medium text-slate-700">Jabatan Terakhir</label>
                            <input type="text" name="jabatan" value="{{ old('jabatan', $user->biodata->jabatan ?? '') }}" 
                                class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-medium text-slate-700">Unit Kerja / Bagian</label>
                            <input type="text" name="bagian" value="{{ old('bagian', $user->biodata->bagian ?? '') }}" 
                                class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500">
                        </div>
                    </div>
                </div>

                <!-- Section: Pendidikan -->
                <div class="space-y-4">
                    <h3 class="text-sm font-bold text-slate-800 border-b border-slate-200 pb-2">Riwayat Pendidikan</h3>
                    <div class="grid sm:grid-cols-3 gap-6">
                        <div class="space-y-2">
                            <label class="text-xs font-medium text-slate-700">S1</label>
                            <input type="text" name="pendidikan_s1" value="{{ old('pendidikan_s1', $user->biodata->pendidikan_s1 ?? '') }}" 
                                class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-medium text-slate-700">S2</label>
                            <input type="text" name="pendidikan_s2" value="{{ old('pendidikan_s2', $user->biodata->pendidikan_s2 ?? '') }}" 
                                class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-medium text-slate-700">S3</label>
                            <input type="text" name="pendidikan_s3" value="{{ old('pendidikan_s3', $user->biodata->pendidikan_s3 ?? '') }}" 
                                class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500">
                        </div>
                    </div>
                </div>

                <!-- Section: Keuangan & Alamat -->
                <div class="space-y-4">
                    <h3 class="text-sm font-bold text-slate-800 border-b border-slate-200 pb-2">Keuangan & Alamat</h3>
                    <div class="grid sm:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-xs font-medium text-slate-700">No. Rekening</label>
                            <input type="text" name="no_rekening" value="{{ old('no_rekening', $user->biodata->no_rekening ?? '') }}" 
                                class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500" placeholder="Bank - No. Rekening">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-medium text-slate-700">NPWP</label>
                            <input type="text" name="npwp" value="{{ old('npwp', $user->biodata->npwp ?? '') }}" 
                                class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-medium text-slate-700">Alamat Kantor</label>
                            <textarea name="alamat_kantor" rows="2" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500">{{ old('alamat_kantor', $user->biodata->alamat_kantor ?? '') }}</textarea>
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-medium text-slate-700">Alamat Rumah</label>
                            <textarea name="alamat_rumah" rows="2" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500">{{ old('alamat_rumah', $user->biodata->alamat_rumah ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Section: Ubah Password -->
                <div class="mt-6 p-4 rounded-xl bg-slate-50 border border-slate-100 space-y-6">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2"><i class="fas fa-lock mr-1"></i> Ubah Password</p>
                    <p class="text-xs text-slate-400 mb-4">Kosongkan jika tidak ingin mengubah password.</p>
                    <div class="grid sm:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-xs font-medium text-slate-700">Password Baru</label>
                            <input type="password" name="password" 
                                class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 @error('password') border-red-500 @enderror"
                                placeholder="Biarkan kosong jika tidak diubah">
                            @error('password') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-medium text-slate-700">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" 
                                class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500"
                                placeholder="Ketik ulang password baru">
                        </div>
                    </div>
                </div>

                <div class="pt-6 border-t border-slate-200/50 flex justify-end gap-3">
                    <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50 transition-colors flex items-center gap-2">
                        <i class="fas fa-arrow-left text-slate-400"></i> Kembali
                    </a>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-amber-500 text-white font-semibold text-sm hover:bg-amber-600 transition-colors shadow-lg shadow-amber-500/20 flex items-center gap-2">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
