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

use App\Http\Controllers\RuanganController;
use App\Http\Controllers\PeminjamanRuanganController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WAConfigController;
use App\Http\Controllers\WhatsAppSettingController;
use App\Http\Controllers\NotificationController;

Route::middleware('auth')->group(function () {
    // Notification routes (In-App System Notification)
    Route::get('/notifications', [NotificationController::class, 'fetch'])->name('notifications.fetch');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.read-all');

    // Akses publik (semua user login)
    Route::resource('ruangan', RuanganController::class)->only(['index']);
    Route::get('peminjaman-ruangan/{id}/dokumen/{type}', [PeminjamanRuanganController::class, 'lihatDokumen'])->name('peminjaman-ruangan.dokumen');
    Route::get('peminjaman-ruangan/{id}/izin-digital', [PeminjamanRuanganController::class, 'unduhIzinDigital'])->name('peminjaman-ruangan.izin-digital');
    Route::patch('peminjaman-ruangan/{id}/ajukan-kembali', [PeminjamanRuanganController::class, 'ajukanKembali'])->name('peminjaman-ruangan.ajukan-kembali');
    Route::resource('peminjaman-ruangan', PeminjamanRuanganController::class);

    // Akses khusus admin
    Route::middleware('role:admin')->group(function () {
        Route::get('laporan/peminjaman-ruangan', [PeminjamanRuanganController::class, 'laporan'])->name('peminjaman-ruangan.laporan');
        Route::get('laporan/peminjaman-ruangan/export-excel', [PeminjamanRuanganController::class, 'exportExcel'])->name('peminjaman-ruangan.export-excel');
        Route::get('laporan/peminjaman-ruangan/export-csv', [PeminjamanRuanganController::class, 'exportCsv'])->name('peminjaman-ruangan.export-csv');
        Route::resource('users', UserController::class);
        Route::resource('ruangan', RuanganController::class)->except(['index', 'show']);
        
        // Routes for approval
        Route::patch('peminjaman-ruangan/{id}/approve', [PeminjamanRuanganController::class, 'approve'])->name('peminjaman-ruangan.approve');
        Route::patch('peminjaman-ruangan/{id}/reject', [PeminjamanRuanganController::class, 'reject'])->name('peminjaman-ruangan.reject');

        // Routes for WhatsApp Setting & WA Config
        Route::get('/whatsapp-setting', [WAConfigController::class, 'index'])->name('whatsapp-setting.index');
        Route::post('/whatsapp-setting/save-config', [WAConfigController::class, 'saveConfig'])->name('whatsapp-setting.save-config');
        Route::post('/whatsapp-setting/add-recipient', [WAConfigController::class, 'addRecipient'])->name('whatsapp-setting.add-recipient');
        Route::post('/whatsapp-setting/test-connection', [WAConfigController::class, 'testConnection'])->name('whatsapp-setting.test-connection');
        Route::post('/whatsapp-setting/send-test', [WAConfigController::class, 'testSend'])->name('whatsapp-setting.send-test');
        Route::post('/whatsapp-setting/test-send', [WAConfigController::class, 'testSend'])->name('whatsapp-setting.test-send');
    });
});
