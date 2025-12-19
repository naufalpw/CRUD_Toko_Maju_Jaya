<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController; 

// --- MIDDLEWARE GUEST (Untuk yang belum login) ---
Route::middleware('guest')->group(function() {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// --- MIDDLEWARE AUTH (Untuk yang sudah login) ---
Route::middleware('auth')->group(function() {
    
    // 1. Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // 2. Redirect Halaman Utama (root) ke Dashboard
    Route::get('/', function () {
        return redirect()->route('dashboard.index');
    });

    // 3. Route Dashboard Utama
    // HANYA GUNAKAN SATU NAMA. Kita pakai 'dashboard.index' agar sesuai dengan controller.
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    // 4. Route Simpan Pengeluaran
    // Nama route tetap 'reports.store_expense' sesuai permintaan agar form blade tidak perlu diubah
    Route::post('/dashboard/expense', [DashboardController::class, 'storeExpense'])
        ->name('reports.store_expense');

    // 5. Fitur Produk (CRUD Otomatis: index, create, store, edit, update, destroy)
    Route::resource('products', ProductController::class);

    // 6. Fitur Kasir / Transaksi
    Route::controller(TransactionController::class)->group(function() {
        Route::get('/transaction', 'index')->name('transactions.index');
        Route::post('/transaction/add', 'addToCart')->name('transactions.add');
        Route::post('/transaction/checkout', 'checkout')->name('transactions.checkout');
        Route::get('/transactions/print/{id}', 'print')->name('transactions.print');
    });

});