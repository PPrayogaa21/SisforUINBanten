<div id="biodata-reminder-modal" class="fixed inset-0 z-[9999] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" onclick="closeBiodataModal()"></div>

    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-slate-100 animate-scale-up">
            <!-- Top accent gradient -->
            <div class="h-2 w-full bg-gradient-to-r from-emerald-500 via-teal-500 to-cyan-500"></div>
            
            <div class="p-6 text-center">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-amber-100 mb-4">
                    <i class="fas fa-user-pen text-2xl text-amber-600"></i>
                </div>
                
                <h3 class="text-xl font-bold text-slate-900 mb-2">Lengkapi Biodata Anda</h3>
                
                <p class="text-slate-600 text-sm mb-6 leading-relaxed">
                    Maaf, Anda wajib melengkapi biodata Anda terlebih dahulu sebelum dapat mengikuti atau melihat detail kegiatan ini.
                </p>

                <div class="flex flex-col gap-3">
                    <a href="{{ route('biodata.create') }}" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-200 hover:bg-emerald-700 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                        <i class="fas fa-pencil-alt"></i>
                        Lengkapi Biodata Sekarang
                    </a>
                    
                    <button type="button" onclick="closeBiodataModal()" class="inline-flex w-full justify-center rounded-xl bg-white px-4 py-3 text-sm font-semibold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 transition-all duration-200">
                        Nanti Saja
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes scaleUp {
    from { opacity: 0; transform: scale(0.95); }
    to { opacity: 1; transform: scale(1); }
}
.animate-scale-up {
    animation: scaleUp 0.2s ease-out forwards;
}
</style>

<script>
function openBiodataModal() {
    const modal = document.getElementById('biodata-reminder-modal');
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeBiodataModal() {
    const modal = document.getElementById('biodata-reminder-modal');
    modal.classList.add('hidden');
    document.body.style.overflow = '';
}

document.addEventListener('DOMContentLoaded', function() {
    @if(session('show_biodata_modal'))
        openBiodataModal();
    @endif
});
</script>
