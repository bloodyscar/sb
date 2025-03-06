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

Route::get('/dashboard', [BarangController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/barang-masuk', [BarangMasukController::class, 'index'])->middleware(['auth', 'verified'])->name('barang-masuk');
Route::get('/barang-keluar', [BarangKeluarController::class, 'index'])->middleware(['auth', 'verified'])->name('barang-keluar');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource('barang', BarangController::class);

require __DIR__.'/auth.php';
