@extends('layouts.auth')
@section('title', 'Pilih Role')

@section('content')
<h2 class="text-xl font-bold text-white mb-1">Pilih Role Aktif</h2>
<p class="text-slate-400 text-sm mb-6">Pilih peran Anda untuk sesi ini</p>

<form method="POST" action="{{ route('select-role.submit') }}" class="space-y-4" id="role-form">
    @csrf
    <input type="hidden" name="role" id="selected-role" value="">

    <button type="button" onclick="selectRole('peserta')" id="btn-peserta"
        class="w-full p-5 rounded-xl border-2 border-white/10 bg-white/5 hover:border-emerald-400/50 hover:bg-emerald-500/10 transition-all duration-300 text-left group">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                <i class="fas fa-user-graduate text-white text-xl"></i>
            </div>
            <div>
                <h3 class="text-white font-semibold text-lg">Peserta</h3>
                <p class="text-slate-400 text-sm">Lihat kegiatan, download materi, dan isi kuesioner</p>
            </div>
            <i class="fas fa-chevron-right text-slate-600 ml-auto group-hover:text-emerald-400 transition-colors"></i>
        </div>
    </button>

    <button type="button" onclick="selectRole('narasumber')" id="btn-narasumber"
        class="w-full p-5 rounded-xl border-2 border-white/10 bg-white/5 hover:border-emerald-400/50 hover:bg-emerald-500/10 transition-all duration-300 text-left group">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                <i class="fas fa-chalkboard-user text-white text-xl"></i>
            </div>
            <div>
                <h3 class="text-white font-semibold text-lg">Narasumber</h3>
                <p class="text-slate-400 text-sm">Upload materi dan lihat daftar peserta</p>
            </div>
            <i class="fas fa-chevron-right text-slate-600 ml-auto group-hover:text-emerald-400 transition-colors"></i>
        </div>
    </button>
</form>

<script>
function selectRole(role) {
    document.getElementById('selected-role').value = role;
    // Highlight selected
    document.getElementById('btn-peserta').classList.toggle('border-emerald-400/50', role === 'peserta');
    document.getElementById('btn-peserta').classList.toggle('bg-emerald-500/10', role === 'peserta');
    document.getElementById('btn-narasumber').classList.toggle('border-emerald-400/50', role === 'narasumber');
    document.getElementById('btn-narasumber').classList.toggle('bg-emerald-500/10', role === 'narasumber');
    // Submit after brief delay for visual feedback
    setTimeout(() => document.getElementById('role-form').submit(), 200);
}
</script>
@endsection
