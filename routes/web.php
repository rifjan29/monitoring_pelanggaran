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
        Route::resource('santri', DataSantriController::class);
        // wali santri
        Route::get('/search', [DataWaliSantriController::class, 'index'])->name('search');
        Route::resource('wali-santri',DataWaliSantriController::class);
        // asrama
        Route::resource('asrama',DataAsramaController::class);
        // user
        Route::resource('user', DataUserController::class);
        // pelanggaran sekolah
        Route::resource('pelanggaran-sekolah',DataPelanggaranSekolahController::class);
        // pelanggaran pondok
        Route::resource('pelanggaran-pondok',DataPelanggaranPondokController::class);
        // laporan
        Route::get('laporan',[LaporanController::class,'index'])->name('laporan.index');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
