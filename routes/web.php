<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\BarangKeluar;
use App\Http\Controllers\BarangKeluarController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function() {
    return view ('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/barang', [BarangController::class, 'index'])->middleware(['auth', 'verified'])->name('barang');

Route::get('/barang-masuk', [BarangMasukController::class, 'index'])->middleware(['auth', 'verified'])->name('barang-masuk');
Route::post('/barang-masuk/store', [BarangMasukController::class, 'store']);
Route::post('/barang-masuk/update/{id}', [BarangMasukController::class, 'update']);
Route::delete('/barang-masuk/delete/{id}', [BarangMasukController::class, 'destroy']);

Route::get('/barang-keluar', [BarangKeluarController::class, 'index'])->middleware(['auth', 'verified'])->name('barang-keluar');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource('barang', BarangController::class);

Route::post('/barang/store', [BarangController::class, 'store']);
Route::post('/barang/update/{id}', [BarangController::class, 'update']);
Route::delete('/barang/delete/{id}', [BarangController::class, 'destroy']);



require __DIR__.'/auth.php';
