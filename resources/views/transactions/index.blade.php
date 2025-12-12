@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-md-7">
        <div class="card mb-3">
            <div class="card-header bg-white fw-bold">Pilih Produk</div>
            <div class="card-body">
                <form action="{{ route('transactions.add') }}" method="POST">
                    @csrf
                    <div class="input-group">
                        <select name="product_id" class="form-select">
                            <option value="">-- Pilih Barang --</option>
                            @foreach($products as $p)
                                <option value="{{ $p->id }}">{{ $p->name }} - Stok: {{ $p->stock }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-5">
        <div class="card">
            <div class="card-header bg-dark text-white">Keranjang Belanja</div>
            <div class="card-body">
                <table class="table table-sm">
                    <thead><tr><th>Barang</th><th>Qty</th><th>Subtotal</th></tr></thead>
                    <tbody>
                        @php $total = 0; @endphp
                        @if(session('cart'))
                            @foreach(session('cart') as $id => $details)
                                @php $total += $details['price'] * $details['qty'] @endphp
                                <tr>
                                    <td>{{ $details['name'] }}</td>
                                    <td>{{ $details['qty'] }}</td>
                                    <td>Rp {{ number_format($details['price'] * $details['qty']) }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr><td colspan="3" class="text-center">Keranjang Kosong</td></tr>
                        @endif
                    </tbody>
                </table>
                <h4 class="text-end fw-bold">Total: Rp {{ number_format($total) }}</h4>
                <form action="{{ route('transactions.checkout') }}" method="POST" class="d-grid mt-3">
                    @csrf
                    <button class="btn btn-success btn-lg" {{ empty(session('cart')) ? 'disabled' : '' }}>BAYAR SEKARANG</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection