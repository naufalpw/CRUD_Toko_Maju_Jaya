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

    // Tambah ke keranjang (session) dengan VALIDASI STOK & JUMLAH INPUT
    public function addToCart(Request $request) {
        $product = Product::find($request->product_id);
        $cart = session()->get('cart', []);

        // 1. AMBIL INPUT QTY
        // Ambil input 'qty', jika kosong atau invalid default ke 1
        $inputQty = (int) $request->input('qty', 1);
        
        // Pastikan tidak ada input negatif atau nol
        if ($inputQty <= 0) {
            $inputQty = 1;
        }

        // 2. HITUNG TOTAL JUMLAH YANG AKAN ADA DI KERANJANG
        // Cek jumlah yang SUDAH ada di keranjang sebelumnya
        $currentQtyInCart = isset($cart[$request->product_id]) ? $cart[$request->product_id]['qty'] : 0;
        
        // Jumlah total nanti = Jumlah di keranjang + Jumlah input baru
        $futureQty = $currentQtyInCart + $inputQty;

        // 3. CEK STOK SEBELUM DIMASUKKAN
        if($product->stock < $futureQty) {
            return redirect()->back()->with('error', "Stok tidak cukup! Stok tersedia: {$product->stock}. (Sudah di keranjang: {$currentQtyInCart}, Mau ditambah: {$inputQty})");
        }

        // 4. UPDATE DATA KERANJANG
        if(isset($cart[$request->product_id])) {
            // Jika produk sudah ada, tambahkan qty sesuai input (bukan ++)
            $cart[$request->product_id]['qty'] += $inputQty;
        } else {
            // Jika produk baru, masukkan qty sesuai input
            $cart[$request->product_id] = [
                'name' => $product->name,
                'price' => $product->price,
                'qty' => $inputQty, // Gunakan inputQty
                'id' => $product->id
            ];
        }
        
        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Berhasil menambahkan produk ke keranjang.');
    }

    // Proses checkout dengan VALIDASI AKHIR
    public function checkout() {
        $cart = session()->get('cart');
        if(!$cart) return redirect()->back()->with('error', 'Keranjang kosong');

        // CEK STOK LAGI SEBELUM MENYIMPAN KE DATABASE (PENTING!)
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
        // KITA SIMPAN HASIL TRANSACTION KE VARIABEL $trx AGAR BISA DIPAKAI DI REDIRECT
        $trx = DB::transaction(function() use ($cart) {
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

            // PENTING: Return variabel $trx agar bisa dibaca di luar fungsi DB::transaction
            return $trx;
        });

        session()->forget('cart');
        
        // REVISI: Redirect langsung ke halaman print menggunakan ID dari $trx
        return redirect()->route('transactions.print', $trx->id);
    }

    // Halaman Riwayat
    public function history() {
        $logs = ActivityLog::with('transaction.details.product')->latest()->get();
        return view('transactions.history', compact('logs'));
    }

    // Method untuk menampilkan tampilan struk
    public function print($id) {
        $transaction = Transaction::with('details.product')->findOrFail($id);
        return view('transactions.print', compact('transaction'));
    }
}