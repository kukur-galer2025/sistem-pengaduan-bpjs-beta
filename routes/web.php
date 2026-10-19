<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ComplaintManagementController;
use App\Http\Controllers\Admin\CategoryController; // Tambahkan import ini
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\AdminMiddleware; 

// Halaman Utama / Landing Page
Route::get('/', function () {
    return view('welcome');
});

Route::get('/faq', function () {
    $faqs = \App\Models\Faq::where('is_active', true)->latest()->get();
    return view('faq', compact('faqs'));
})->name('faq.public');
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

    // Route untuk Notifikasi
    Route::post('/notifications/mark-all-read', function () {
        auth()->user()->unreadNotifications->markAsRead();
        return back();
    })->name('notifications.markAllRead');

    // Route untuk Profil Pengguna
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // ------------------------------------------
    // AREA PELAPOR (Masyarakat)
    // ------------------------------------------
    Route::get('/pengaduan', [ComplaintController::class, 'index'])->name('pengaduan.index');
    Route::get('/pengaduan/create', [ComplaintController::class, 'create'])->name('pengaduan.create');
    Route::post('/pengaduan', [ComplaintController::class, 'store'])->name('pengaduan.store');
    Route::get('/pengaduan/{id}', [ComplaintController::class, 'show'])->name('pengaduan.show');
    Route::post('/pengaduan/{id}/rating', [ComplaintController::class, 'storeRating'])->name('pengaduan.rating');
    Route::post('/pengaduan/{id}/comments', [\App\Http\Controllers\TicketCommentController::class, 'store'])->name('comments.store');

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
        Route::post('/pengaduan/{id}/comments', [\App\Http\Controllers\TicketCommentController::class, 'store'])->name('comments.store');
        
        // Manajemen Pengguna
        Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
        Route::post('/users/{id}/role', [\App\Http\Controllers\Admin\UserController::class, 'updateRole'])->name('users.update-role');
        Route::delete('/users/{id}', [\App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');
        
        // Manajemen FAQ
        Route::get('/faq', [\App\Http\Controllers\Admin\FaqController::class, 'index'])->name('faq.index');
        Route::post('/faq', [\App\Http\Controllers\Admin\FaqController::class, 'store'])->name('faq.store');
        Route::put('/faq/{id}', [\App\Http\Controllers\Admin\FaqController::class, 'update'])->name('faq.update');
        Route::delete('/faq/{id}', [\App\Http\Controllers\Admin\FaqController::class, 'destroy'])->name('faq.destroy');
        
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