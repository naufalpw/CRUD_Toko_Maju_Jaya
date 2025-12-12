<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ActivityLog; // JANGAN LUPA: Tambahkan Import ini
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // menampilkan daftar produk
    public function index()
    {
        $products = Product::all();
        return view('products.index', compact('products'));
    }

    // menampilkan form tambah produk
    public function create()
    {
        return view('products.create');
    }

    // menyimpan produk baru ke database
    public function store(Request $request)
    {
        // validasi
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
        ]);

        // Simpan produk ke variabel $product agar kita bisa ambil namanya untuk Log
        $product = Product::create($request->all());

        // --- TAMBAHAN LOG: Catat aktivitas tambah produk ---
        ActivityLog::create([
            'action' => 'Tambah Produk',
            'description' => "Menambahkan produk baru: {$product->name}, Stok awal: {$product->stock}"
        ]);

        return redirect()->route('products.index')
                         ->with('success', 'Produk berhasil ditambahkan');
    }

    // menampilkan form edit
    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    // update data produk di database
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
        ]);

        $product->update($request->all());

        // --- TAMBAHAN LOG: Catat aktivitas edit produk ---
        ActivityLog::create([
            'action' => 'Edit Produk',
            'description' => "Mengupdate data produk: {$product->name}, Harga: {$product->price}, Stok: {$product->stock}"
        ]);

        return redirect()->route('products.index')
                         ->with('success', 'Produk berhasil diupdate');
    }

    // menghapus produk
    public function destroy(Product $product)
    {
        // Simpan nama produk dulu sebelum dihapus agar bisa masuk log
        $productName = $product->name; 
        
        $product->delete();

        // --- TAMBAHAN LOG: Catat aktivitas hapus produk ---
        ActivityLog::create([
            'action' => 'Hapus Produk',
            'description' => "Menghapus produk: {$productName}"
        ]);

        return redirect()->route('products.index')
                         ->with('success', 'Produk berhasil dihapus');
    }
}