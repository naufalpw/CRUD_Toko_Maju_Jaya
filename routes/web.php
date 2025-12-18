<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\AuthController;

// Middleware 'guest' mencegah user yang sudah login masuk ke halaman login lagi
Route::middleware('guest')->group(function() {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});


// Middleware 'auth' akan menendang user yang belum login kembali ke halaman login
Route::middleware('auth')->group(function() {
    
    // Route untuk Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Redirect halaman utama ke dashboard/produk
    Route::get('/', function () {
        return redirect()->route('products.index');
    });

    // Semua fitur toko ada di sini
    Route::resource('products', ProductController::class);
    Route::get('/transaction', [TransactionController::class, 'index'])->name('transactions.index');
    Route::post('/transaction/add', [TransactionController::class, 'addToCart'])->name('transactions.add');
    Route::post('/transaction/checkout', [TransactionController::class, 'checkout'])->name('transactions.checkout');
    Route::get('/history', [TransactionController::class, 'history'])->name('transactions.history');
    Route::get('/transactions/print/{id}', [App\Http\Controllers\TransactionController::class, 'print'])->name('transactions.print');
});