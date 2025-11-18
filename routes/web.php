<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\PelangganController;
use App\Http\Controllers\Admin\TagihanController;
use App\Http\Controllers\Admin\PembayaranController as AdminPembayaranController;
use App\Http\Controllers\Admin\KasController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Pelanggan\DashboardController as PelangganDashboard;
use App\Http\Controllers\Pelanggan\TagihanController as PelangganTagihanController;
use App\Http\Controllers\Pelanggan\PembayaranController as PelangganPembayaranController;

// ======================================
// REDIRECT ROOT KE LOGIN
// ======================================
Route::get('/', function () {
    return redirect()->route('login');
});

// ======================================
// AUTH ROUTES
// ======================================
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ======================================
// ADMIN ROUTES
// ======================================
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

        // Pelanggan
        Route::resource('pelanggan', PelangganController::class);

        // Tagihan
        Route::get('/tagihan', [TagihanController::class, 'index'])->name('tagihan.index');
        Route::post('/tagihan/generate', [TagihanController::class, 'generate'])->name('tagihan.generate');
        Route::get('/tagihan/tunggakan', [TagihanController::class, 'tunggakan'])->name('tagihan.tunggakan');

        // Pembayaran (verifikasi admin)
        Route::get('/pembayaran', [AdminPembayaranController::class, 'index'])->name('pembayaran.index');
        Route::get('/pembayaran/pending', [AdminPembayaranController::class, 'pending'])->name('pembayaran.pending');
        Route::get('/pembayaran/{pembayaran}', [AdminPembayaranController::class, 'show'])->name('pembayaran.show');
        Route::put('/pembayaran/{pembayaran}/approve', [AdminPembayaranController::class, 'approve'])->name('pembayaran.approve');
        Route::put('/pembayaran/{pembayaran}/reject', [AdminPembayaranController::class, 'reject'])->name('pembayaran.reject');

        // Kas
        Route::resource('kas', KasController::class)->except(['edit', 'update', 'show']);

        // Laporan
        Route::get('/laporan/laba-rugi', [LaporanController::class, 'labaRugi'])->name('laporan.laba-rugi');
        Route::get('/laporan/saldo', [LaporanController::class, 'saldo'])->name('laporan.saldo');
        Route::get('/laporan/laba-rugi/pdf', [LaporanController::class, 'exportLabaRugiPdf'])->name('laporan.laba-rugi.pdf');
        Route::get('/laporan/tunggakan/pdf', [LaporanController::class, 'exportTunggakanPdf'])->name('laporan.tunggakan.pdf');
    });

// ======================================
// PELANGGAN ROUTES
// ======================================
Route::middleware(['auth', 'role:pelanggan'])
    ->prefix('pelanggan')
    ->name('pelanggan.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [PelangganDashboard::class, 'index'])->name('dashboard');

        // Tagihan
        Route::get('/tagihan', [PelangganTagihanController::class, 'index'])->name('tagihan.index');
        Route::get('/tagihan/{tagihan}', [PelangganTagihanController::class, 'show'])->name('tagihan.show');

        // Pembayaran
        Route::get('/pembayaran', [PelangganPembayaranController::class, 'index'])->name('pembayaran.index'); // daftar pembayaran
        Route::get('/pembayaran/{tagihan}/create', [PelangganPembayaranController::class, 'create'])->name('pembayaran.create'); // form bayar
        Route::post('/pembayaran/{tagihan}', [PelangganPembayaranController::class, 'store'])->name('pembayaran.store'); // proses simpan pembayaran
        Route::get('/pembayaran/{pembayaran}', [PelangganPembayaranController::class, 'show'])->name('pembayaran.show'); // detail pembayaran
    });
