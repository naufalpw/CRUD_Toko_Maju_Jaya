<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Daftar kategori yang tersedia (Bisa juga dibuat tabel database terpisah jika ingin dinamis)
    private $categories = ['Makanan', 'Minuman', 'Elektronik', 'Pakaian', 'Alat Tulis', 'Sembako', 'Lainnya'];

    // Menampilkan daftar produk dengan FITUR SEARCH & FILTER
    public function index(Request $request)
    {
        // Mulai Query
        $query = Product::query();

        // 1. Logika Search (Berdasarkan Nama)
        if ($request->has('search') && $request->search != null) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // 2. Logika Filter Kategori
        if ($request->has('category') && $request->category != null) {
            $query->where('category', $request->category);
        }

        // Ambil data (menggunakan latest agar yang baru tampil diatas)
        $products = $query->latest()->get();
        
        // Kirim juga variabel $categories untuk dropdown filter di view
        $categories = $this->categories;

        return view('products.index', compact('products', 'categories'));
    }

    public function create()
    {
        // Kirim data kategori ke view
        $categories = $this->categories;
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'category' => 'required', // Validasi kategori
            'price' => 'required|numeric',
            'stock' => 'required|integer',
        ]);

        $product = Product::create($request->all());

        ActivityLog::create([
            'action' => 'Tambah Produk',
            'description' => "Menambahkan produk: {$product->name} ({$product->category})"
        ]);

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan');
    }

    public function edit(Product $product)
    {
        $categories = $this->categories;
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required',
            'category' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
        ]);

        $product->update($request->all());

        ActivityLog::create([
            'action' => 'Edit Produk',
            'description' => "Mengupdate produk: {$product->name}"
        ]);

        return redirect()->route('products.index')->with('success', 'Produk berhasil diupdate');
    }

    public function destroy(Product $product)
    {
        $productName = $product->name; 
        $product->delete();

        ActivityLog::create([
            'action' => 'Hapus Produk',
            'description' => "Menghapus produk: {$productName}"
        ]);

        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus');
    }
}   