@extends('layouts.app')
@section('content')
<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Daftar Produk</h5>
        <a href="{{ route('products.create') }}" class="btn btn-sm btn-primary">Tambah Produk</a>
    </div>
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $p)
                <tr>
                    <td>{{ $p->name }}</td>
                    <td>Rp {{ number_format($p->price) }}</td>
                    <td>
                        <span class="badge {{ $p->stock < 5 ? 'bg-danger' : 'bg-success' }}">
                            {{ $p->stock }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('products.edit', $p->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('products.destroy', $p->id) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('Hapus?')">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection