<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataAsramaController;
use App\Http\Controllers\DataPelanggaranPondokController;
use App\Http\Controllers\DataPelanggaranSekolahController;
use App\Http\Controllers\DataSantriController;
use App\Http\Controllers\DataUserController;
use App\Http\Controllers\DataWaliSantriController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/', function () {
    return view('welcome');
});


Route::middleware(['auth'])->group(function () {
    Route::get('dashboard',[DashboardController::class,'index'])->name('dashboard');
    Route::prefix('dashboard')->group(function () {
        // santri
        Route::get('/search-santri', [DataSantriController::class, 'index'])->name('santri.search-santri');
        Route::post('update-status', [DataSantriController::class,'updateStatus'])->name('santri.update-status');
        Route::resource('santri', DataSantriController::class);
        // wali santri
        Route::get('/search', [DataWaliSantriController::class, 'index'])->name('search');
        Route::resource('wali-santri',DataWaliSantriController::class);
        // asrama
        Route::get('/search-asrama', [DataAsramaController::class, 'index'])->name('asrama.search');
        Route::resource('asrama',DataAsramaController::class);
        // user
        Route::get('/search-user', [DataUserController::class, 'index'])->name('user.search');
        Route::resource('user', DataUserController::class);
        // pelanggaran sekolah
        Route::get('pelanggaran-sekolah/cetak-pdf', [DataPelanggaranSekolahController::class, 'pdf'])->name('pelanggaran-sekolah.pdf');
        Route::get('/search-pelanggaran-sekolah', [DataPelanggaranSekolahController::class, 'index'])->name('pelanggaran-sekolah.search');
        Route::resource('pelanggaran-sekolah',DataPelanggaranSekolahController::class);
        // pelanggaran pondok
        Route::get('pelanggaran-pondok/cetak-pdf', [DataPelanggaranPondokController::class, 'pdf'])->name('pelanggaran-pondok.pdf');
        Route::get('/search-pelanggaran-pondok', [DataPelanggaranPondokController::class, 'index'])->name('pelanggaran-pondok.search');
        Route::resource('pelanggaran-pondok',DataPelanggaranPondokController::class);
        // laporan
        Route::get('laporan',[LaporanController::class,'index'])->name('laporan.index');
        Route::get('laporan/detail/{id}',[LaporanController::class,'detail'])->name('laporan.detail');
        Route::get('laporan/kirim-sekolah/{id}',[LaporanController::class,'kirimSekolah'])->name('laporan.kirim-sekolah');
        Route::get('laporan/kirim-pondok/{id}',[LaporanController::class,'kirimPondok'])->name('laporan.kirim-pondok');
        // generate laporan
        Route::get('laporan/generate-laporan',[LaporanController::class,'generateLaporanWeek'])->name('laporan.generate');

    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
