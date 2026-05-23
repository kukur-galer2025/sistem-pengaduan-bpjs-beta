<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ComplaintManagementController;
use App\Http\Controllers\Admin\CategoryController; // Tambahkan import ini
use App\Http\Middleware\AdminMiddleware; 

// Halaman Utama / Landing Page
Route::get('/', function () {
    return view('welcome');
});

// ==========================================
// AREA GUEST (Belum Login)
// ==========================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// ==========================================
// AREA AUTHENTICATED (Sudah Login)
// ==========================================
Route::middleware('auth')->group(function () {
    
    // Route untuk Logout (Bisa diakses Admin maupun Pelapor)
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // ------------------------------------------
    // AREA PELAPOR (Masyarakat)
    // ------------------------------------------
    Route::get('/pengaduan', [ComplaintController::class, 'index'])->name('pengaduan.index');
    Route::get('/pengaduan/create', [ComplaintController::class, 'create'])->name('pengaduan.create');
    Route::post('/pengaduan', [ComplaintController::class, 'store'])->name('pengaduan.store');
    Route::get('/pengaduan/{id}', [ComplaintController::class, 'show'])->name('pengaduan.show');

    // ------------------------------------------
    // AREA ADMIN (Petugas BPJS)
    // ------------------------------------------
    Route::middleware([AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
        
        // Halaman Dashboard Admin
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        // Manajemen Pengaduan
        Route::get('/pengaduan', [ComplaintManagementController::class, 'index'])->name('pengaduan.index');
        
        // Ekspor PDF (Harus di atas route {id})
        Route::get('/pengaduan/export-pdf', [ComplaintManagementController::class, 'exportPdf'])->name('pengaduan.export-pdf');
        
        Route::get('/pengaduan/{id}', [ComplaintManagementController::class, 'show'])->name('pengaduan.show');
        Route::post('/pengaduan/{id}/status', [ComplaintManagementController::class, 'updateStatus'])->name('pengaduan.update-status');
        
        // ==========================================
        // Manajemen Kategori (CRUD)
        // ==========================================
        Route::get('/kategori', [CategoryController::class, 'index'])->name('kategori.index');
        Route::post('/kategori', [CategoryController::class, 'store'])->name('kategori.store');
        Route::get('/kategori/{id}/edit', [CategoryController::class, 'edit'])->name('kategori.edit');
        Route::put('/kategori/{id}', [CategoryController::class, 'update'])->name('kategori.update');
        Route::delete('/kategori/{id}', [CategoryController::class, 'destroy'])->name('kategori.destroy');
        
    });
});