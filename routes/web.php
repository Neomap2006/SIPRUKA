<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

use App\Http\Controllers\BukuController;
use App\Http\Controllers\RuanganController;
use App\Http\Controllers\PeminjamanBukuController;
use App\Http\Controllers\PeminjamanRuanganController;

Route::middleware('auth')->group(function () {
    // Akses publik (semua user login)
    Route::resource('buku', BukuController::class)->only(['index']);
    Route::resource('ruangan', RuanganController::class)->only(['index']);
    Route::resource('peminjaman-buku', PeminjamanBukuController::class);
    Route::resource('peminjaman-ruangan', PeminjamanRuanganController::class);

    // Akses khusus admin
    Route::middleware('role:admin')->group(function () {
        Route::resource('buku', BukuController::class)->except(['index', 'show']);
        Route::resource('ruangan', RuanganController::class)->except(['index', 'show']);
        
        // Routes for approval
        Route::patch('peminjaman-ruangan/{id}/approve', [PeminjamanRuanganController::class, 'approve'])->name('peminjaman-ruangan.approve');
        Route::patch('peminjaman-ruangan/{id}/reject', [PeminjamanRuanganController::class, 'reject'])->name('peminjaman-ruangan.reject');
        
        Route::patch('peminjaman-buku/{id}/approve', [PeminjamanBukuController::class, 'approve'])->name('peminjaman-buku.approve');
        Route::patch('peminjaman-buku/{id}/reject', [PeminjamanBukuController::class, 'reject'])->name('peminjaman-buku.reject');
        Route::patch('peminjaman-buku/{id}/return', [PeminjamanBukuController::class, 'returnBook'])->name('peminjaman-buku.return');
    });
});
