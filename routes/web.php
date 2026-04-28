<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\BiodataController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\RoleSelectionController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\KegiatanController as AdminKegiatanController;
use App\Http\Controllers\Admin\KuesionerController as AdminKuesionerController;
use App\Http\Controllers\Peserta\DashboardController as PesertaDashboardController;
use App\Http\Controllers\Peserta\KegiatanController as PesertaKegiatanController;
use App\Http\Controllers\Peserta\KuesionerController as PesertaKuesionerController;
use App\Http\Controllers\Narasumber\DashboardController as NarasumberDashboardController;
use App\Http\Controllers\Narasumber\KegiatanController as NarasumberKegiatanController;

// ===== PUBLIC =====
Route::get('/', [LandingController::class, 'index'])->name('landing');

// ===== AUTH (GUEST ONLY) =====
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// ===== AUTHENTICATED =====
Route::middleware('auth')->group(function () {
    // Biodata
    Route::get('/biodata', [BiodataController::class, 'create'])->name('biodata.create');
    Route::post('/biodata', [BiodataController::class, 'store'])->name('biodata.store');

    // Role Selection
    Route::get('/select-role', [RoleSelectionController::class, 'show'])->name('select-role');
    Route::post('/select-role', [RoleSelectionController::class, 'select'])->name('select-role.submit');
});

// ===== ADMIN =====
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Kegiatan CRUD
    Route::resource('kegiatan', AdminKegiatanController::class);

    // Kegiatan - Peserta
    Route::get('/kegiatan/{kegiatan}/peserta', [AdminKegiatanController::class, 'peserta'])->name('kegiatan.peserta');
    Route::post('/kegiatan/{kegiatan}/peserta', [AdminKegiatanController::class, 'addPeserta'])->name('kegiatan.peserta.add');
    Route::delete('/kegiatan/{kegiatan}/peserta/{user}', [AdminKegiatanController::class, 'removePeserta'])->name('kegiatan.peserta.remove');
    Route::patch('/kegiatan/{kegiatan}/peserta/{user}/kehadiran', [AdminKegiatanController::class, 'updateKehadiran'])->name('kegiatan.peserta.kehadiran');

    // Kegiatan - Narasumber
    Route::get('/kegiatan/{kegiatan}/narasumber', [AdminKegiatanController::class, 'narasumberList'])->name('kegiatan.narasumber');
    Route::post('/kegiatan/{kegiatan}/narasumber', [AdminKegiatanController::class, 'addNarasumber'])->name('kegiatan.narasumber.add');
    Route::delete('/kegiatan/{kegiatan}/narasumber/{user}', [AdminKegiatanController::class, 'removeNarasumber'])->name('kegiatan.narasumber.remove');

    // Kegiatan - Materi
    Route::get('/kegiatan/{kegiatan}/materi', [AdminKegiatanController::class, 'materiIndex'])->name('kegiatan.materi');
    Route::post('/kegiatan/{kegiatan}/materi', [AdminKegiatanController::class, 'uploadMateri'])->name('kegiatan.materi.upload');
    Route::delete('/kegiatan/{kegiatan}/materi/{materi}', [AdminKegiatanController::class, 'deleteMateri'])->name('kegiatan.materi.delete');

    // Kegiatan - Dokumentasi
    Route::get('/kegiatan/{kegiatan}/dokumentasi', [AdminKegiatanController::class, 'dokumentasiIndex'])->name('kegiatan.dokumentasi');
    Route::post('/kegiatan/{kegiatan}/dokumentasi', [AdminKegiatanController::class, 'uploadDokumentasi'])->name('kegiatan.dokumentasi.upload');
    Route::delete('/kegiatan/{kegiatan}/dokumentasi/{dokumentasi}', [AdminKegiatanController::class, 'deleteDokumentasi'])->name('kegiatan.dokumentasi.delete');

    // Kegiatan - Dokumen
    Route::get('/kegiatan/{kegiatan}/dokumen', [AdminKegiatanController::class, 'dokumenIndex'])->name('kegiatan.dokumen');
    Route::post('/kegiatan/{kegiatan}/dokumen', [AdminKegiatanController::class, 'uploadDokumen'])->name('kegiatan.dokumen.upload');
    Route::delete('/kegiatan/{kegiatan}/dokumen/{dokumen}', [AdminKegiatanController::class, 'deleteDokumen'])->name('kegiatan.dokumen.delete');

    // Kegiatan - Kuesioner
    Route::get('/kegiatan/{kegiatan}/kuesioner', [AdminKuesionerController::class, 'index'])->name('kegiatan.kuesioner');
    Route::post('/kegiatan/{kegiatan}/kuesioner', [AdminKuesionerController::class, 'store'])->name('kegiatan.kuesioner.store');
    Route::delete('/kuesioner/{kuesioner}', [AdminKuesionerController::class, 'destroy'])->name('kuesioner.destroy');
    Route::post('/kuesioner/{kuesioner}/pertanyaan', [AdminKuesionerController::class, 'addPertanyaan'])->name('kuesioner.pertanyaan.add');
    Route::delete('/pertanyaan/{pertanyaan}', [AdminKuesionerController::class, 'deletePertanyaan'])->name('pertanyaan.delete');
    Route::get('/kuesioner/{kuesioner}/hasil', [AdminKuesionerController::class, 'hasil'])->name('kuesioner.hasil');
});

// ===== PESERTA =====
Route::middleware(['auth', 'check.biodata', 'check.role:peserta'])->prefix('peserta')->name('peserta.')->group(function () {
    Route::get('/dashboard', [PesertaDashboardController::class, 'index'])->name('dashboard');
    Route::get('/kegiatan', [PesertaKegiatanController::class, 'index'])->name('kegiatan.index');
    Route::get('/kegiatan/{kegiatan}', [PesertaKegiatanController::class, 'show'])->name('kegiatan.show');
    Route::get('/materi/{materi}/download', [PesertaKegiatanController::class, 'downloadMateri'])->name('materi.download');
    Route::get('/kegiatan/{kegiatan}/kuesioner/{kuesioner}', [PesertaKuesionerController::class, 'fill'])->name('kuesioner.fill');
    Route::post('/kegiatan/{kegiatan}/kuesioner/{kuesioner}', [PesertaKuesionerController::class, 'submit'])->name('kuesioner.submit');
});

// ===== NARASUMBER =====
Route::middleware(['auth', 'check.biodata', 'check.role:narasumber'])->prefix('narasumber')->name('narasumber.')->group(function () {
    Route::get('/dashboard', [NarasumberDashboardController::class, 'index'])->name('dashboard');
    Route::get('/kegiatan', [NarasumberKegiatanController::class, 'index'])->name('kegiatan.index');
    Route::get('/kegiatan/{kegiatan}', [NarasumberKegiatanController::class, 'show'])->name('kegiatan.show');
    Route::post('/kegiatan/{kegiatan}/materi', [NarasumberKegiatanController::class, 'uploadMateri'])->name('kegiatan.materi.upload');
});
