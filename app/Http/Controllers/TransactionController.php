<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    // Halaman transaksi (kasir)
    public function index() {
        // Hanya tampilkan produk yang stoknya > 0 di pilihan menu
        $products = Product::where('stock', '>', 0)->get();
        $cart = session()->get('cart', []);
        return view('transactions.index', compact('products', 'cart'));
    }

    // Tambah ke keranjang (session) dengan VALIDASI STOK
    public function addToCart(Request $request) {
        $product = Product::find($request->product_id);
        $cart = session()->get('cart', []);

        // Hitung jumlah yang akan ada di keranjang jika ditambah 1
        $currentQty = isset($cart[$request->product_id]) ? $cart[$request->product_id]['qty'] : 0;
        $futureQty = $currentQty + 1;

        // 1. CEK STOK SAAT TAMBAH KE KERANJANG
        if($product->stock < $futureQty) {
            return redirect()->back()->with('error', "Stok tidak cukup! Sisa stok: {$product->stock}");
        }

        if(isset($cart[$request->product_id])) {
            $cart[$request->product_id]['qty']++;
        } else {
            $cart[$request->product_id] = [
                'name' => $product->name,
                'price' => $product->price,
                'qty' => 1,
                'id' => $product->id
            ];
        }
        
        session()->put('cart', $cart);
        return redirect()->back();
    }

    // Proses checkout dengan VALIDASI AKHIR
    public function checkout() {
        $cart = session()->get('cart');
        if(!$cart) return redirect()->back()->with('error', 'Keranjang kosong');

        // 2. CEK STOK LAGI SEBELUM MENYIMPAN KE DATABASE (PENTING!)
        // Loop semua barang di keranjang untuk memastikan stok masih tersedia
        foreach($cart as $id => $details) {
            $product = Product::find($id);
            
            // Jika produk mendadak dihapus admin saat kasir sedang input
            if(!$product) {
                return redirect()->back()->with('error', "Produk '{$details['name']}' sudah tidak tersedia di database.");
            }

            // Validasi inti: Apakah stok database < jumlah di keranjang?
            if($product->stock < $details['qty']) {
                return redirect()->back()->with('error', 
                    "Transaksi Dibatalkan! Stok barang '{$product->name}' tidak mencukupi. (Sisa: {$product->stock}, Diminta: {$details['qty']})"
                );
            }
        }

        // Jika semua lolos validasi, baru jalankan transaksi
        DB::transaction(function() use ($cart) {
            // Header Transaksi
            $total = 0;
            foreach($cart as $id => $details) {
                $total += $details['price'] * $details['qty'];
            }
            
            $trx = Transaction::create([
                'invoice_code' => 'TRX-' . time(),
                'total_price' => $total
            ]);

            // Simpan Detail & Kurangi Stok
            foreach($cart as $id => $details) {
                TransactionDetail::create([
                    'transaction_id' => $trx->id,
                    'product_id' => $id,
                    'qty' => $details['qty'],
                    'subtotal' => $details['price'] * $details['qty']
                ]);

                // Kurangi stok (sudah aman karena divalidasi di atas)
                $product = Product::find($id);
                $product->decrement('stock', $details['qty']);
            }

            // Catat Log
            ActivityLog::create([
                'action' => 'Transaksi Penjualan',
                'description' => "Transaksi berhasil dengan Invoice {$trx->invoice_code}",
                'transaction_id' => $trx->id
            ]);
        });

        session()->forget('cart');
        return redirect()->route('transactions.history')->with('success', 'Transaksi berhasil!');
    }

    // Halaman Riwayat
    public function history() {
        $logs = ActivityLog::with('transaction.details.product')->latest()->get();
        return view('transactions.history', compact('logs'));
    }
}