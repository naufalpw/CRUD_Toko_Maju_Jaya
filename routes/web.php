<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;

Route::get('/', function () { return redirect('/products'); });

// Produk
Route::resource('products', ProductController::class);

// Transaksi
Route::get('/transaction', [TransactionController::class, 'index'])->name('transactions.index');
Route::post('/transaction/add', [TransactionController::class, 'addToCart'])->name('transactions.add');
Route::post('/transaction/checkout', [TransactionController::class, 'checkout'])->name('transactions.checkout');
Route::get('/history', [TransactionController::class, 'history'])->name('transactions.history');
