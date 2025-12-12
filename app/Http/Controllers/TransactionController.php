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
    // halaman transaksi (kasir)
    public function index() {
        $products = Product::where('stock', '>', 0)->get();
        $cart = session()->get('cart', []);
        return view('transactions.index', compact('products', 'cart'));
    }

    // tambah ke keranjang (session)
    public function addToCart(Request $request) {
        $product = Product::find($request->product_id);
        $cart = session()->get('cart', []);

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

    // proses checkout (mengurangi stok otomatis)
    public function checkout() {
        $cart = session()->get('cart');
        if(!$cart) return redirect()->back()->with('error', 'Keranjang kosong');

        DB::transaction(function() use ($cart) {
            // header Transaksi
            $total = 0;
            foreach($cart as $id => $details) {
                $total += $details['price'] * $details['qty'];
            }
            
            $trx = Transaction::create([
                'invoice_code' => 'TRX-' . time(),
                'total_price' => $total
            ]);

            // simpan detail & kurangi Stok
            foreach($cart as $id => $details) {
                TransactionDetail::create([
                    'transaction_id' => $trx->id,
                    'product_id' => $id,
                    'qty' => $details['qty'],
                    'subtotal' => $details['price'] * $details['qty']
                ]);

                // *** LOGIKA PENGURANGAN STOK ***
                $product = Product::find($id);
                if($product) {
                    $product->decrement('stock', $details['qty']);
                }
            }

            // LOG: Catat bahwa transaksi terjadi
            // simpan transaction_id agar nanti bisa ditampilkan detail barangnya di view
            ActivityLog::create([
                'action' => 'Transaksi Penjualan',
                'description' => "Transaksi berhasil dengan Invoice {$trx->invoice_code}",
                'transaction_id' => $trx->id
            ]);
        });

        session()->forget('cart');
        return redirect()->route('transactions.history')->with('success', 'Transaksi berhasil!');
    }

    // halaman Riwayat
    public function history() {
        // 3. UBAH LOGIKA: Ambil data dari ActivityLog, bukan Transaction langsung
        // Ini agar log transaksi bercampur dengan log edit/tambah produk dalam satu timeline
        $logs = ActivityLog::with('transaction.details.product')->latest()->get();
        
        return view('transactions.history', compact('logs'));
    }
}