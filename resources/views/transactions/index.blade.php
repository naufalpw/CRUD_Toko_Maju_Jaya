@extends('layouts.app')

@section('content')
<div class="row g-4">
    {{-- BAGIAN KIRI: INPUT BARANG --}}
    <div class="col-md-7">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-success text-white py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-upc-scan me-2"></i>Input Barang</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('transactions.add') }}" method="POST">
                    @csrf
                    
                    {{-- 1. Pilih Produk --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold text-secondary">Pilih Produk</label>
                        <select name="product_id" class="form-select form-select-lg" required>
                            <option value="">-- Cari Nama Barang --</option>
                            @foreach($products as $p)
                                <option value="{{ $p->id }}" {{ $p->stock <= 0 ? 'disabled' : '' }}>
                                    {{ $p->name }} | Rp {{ number_format($p->price) }} | Stok: {{ $p->stock }}
                                    {{ $p->stock <= 0 ? '(HABIS)' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- 2. Input Quantity & Tombol Submit --}}
                    <div class="row g-2 align-items-end mb-3">
                        <div class="col-3 col-md-3">
                            <label class="form-label fw-bold text-secondary">Qty</label>
                            {{-- Input QTY ditambahkan disini --}}
                            <input type="number" name="qty" class="form-control form-control-lg text-center" value="1" min="1" required>
                        </div>
                        <div class="col-9 col-md-9">
                            <button type="submit" class="btn btn-success btn-lg w-100">
                                <i class="bi bi-cart-plus me-1"></i> Tambah Ke Keranjang
                            </button>
                        </div>
                    </div>

                    <div class="alert alert-light border border-success text-success small">
                        <i class="bi bi-info-circle me-1"></i> Pastikan stok mencukupi sebelum menambahkan barang.
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- BAGIAN KANAN: STRUK BELANJA (Tidak banyak berubah, hanya layout tampilan) --}}
    <div class="col-md-5">
        <div class="card shadow border-0 h-100" style="background-color: #fffdf8;"> 
            <div class="card-header bg-dark text-white text-center py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-receipt me-2"></i>STRUK BELANJA</h5>
            </div>
            <div class="card-body d-flex flex-column">
                
                <div class="table-responsive flex-grow-1">
                    <table class="table table-sm table-borderless">
                        <thead class="border-bottom border-dark">
                            <tr class="text-uppercase small text-muted">
                                <th>Item</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody style="font-family: 'Courier New', Courier, monospace;">
                            @php $total = 0; @endphp
                            @if(session('cart'))
                                @foreach(session('cart') as $id => $details)
                                    @php $total += $details['price'] * $details['qty'] @endphp
                                    <tr>
                                        <td>{{ $details['name'] }}<br><small class="text-muted">@ {{ number_format($details['price']) }}</small></td>
                                        <td class="text-center align-middle">x{{ $details['qty'] }}</td>
                                        <td class="text-end align-middle fw-bold">{{ number_format($details['price'] * $details['qty']) }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted fst-italic">Keranjang masih kosong...</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <div class="border-top border-dark mt-3 pt-3">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <span class="h5 mb-0 text-muted">TOTAL BAYAR</span>
                        <span class="h3 mb-0 fw-bold text-success">Rp {{ number_format($total) }}</span>
                    </div>
                    
                    <form action="{{ route('transactions.checkout') }}" method="POST" class="d-grid">
                        @csrf
                        <button class="btn btn-success btn-lg fw-bold py-3 shadow-sm" {{ empty(session('cart')) ? 'disabled' : '' }}>
                            <i class="bi bi-cash-coin me-2"></i> PROSES PEMBAYARAN
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection