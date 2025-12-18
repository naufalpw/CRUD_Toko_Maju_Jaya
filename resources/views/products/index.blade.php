@extends('layouts.app')
@section('content')

{{-- HEADER & JUDUL --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold text-secondary"><i class="bi bi-boxes me-2"></i>Daftar Produk</h3>
    <a href="{{ route('products.create') }}" class="btn btn-success shadow-sm rounded-pill px-4">
        <i class="bi bi-plus-lg me-2"></i>Tambah Produk Baru
    </a>
</div>

{{-- BAGIAN FILTER & PENCARIAN --}}
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body bg-light rounded">
        <form action="{{ route('products.index') }}" method="GET" class="row g-2">
            
            {{-- Filter Kategori --}}
            <div class="col-md-3">
                <select name="category" class="form-select">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>
                            {{ $cat }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Input Search --}}
            <div class="col-md-7">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="Cari nama produk..." value="{{ request('search') }}">
                </div>
            </div>

            {{-- Tombol Filter --}}
            <div class="col-md-2 d-grid">
                <button type="submit" class="btn btn-primary fw-bold">Cari / Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-success text-white">
                    <tr>
                        <th class="ps-4 py-3">Nama Barang</th>
                        <th class="py-3">Kategori</th> {{-- Tambah Header Kategori --}}
                        <th class="py-3">Harga Satuan</th>
                        <th class="py-3">Status Stok</th>
                        <th class="text-end pe-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $p)
                    <tr>
                        <td class="ps-4 fw-bold text-dark">{{ $p->name }}</td>
                        <td><span class="badge bg-light text-dark border">{{ $p->category ?? '-' }}</span></td> {{-- Tampilkan Kategori --}}
                        <td class="text-success fw-bold">Rp {{ number_format($p->price, 0, ',', '.') }}</td>
                        <td>
                            @if($p->stock < 5)
                                <span class="badge bg-danger rounded-pill px-3"><i class="bi bi-exclamation-circle me-1"></i> {{ $p->stock }}</span>
                            @elseif($p->stock < 15)
                                <span class="badge bg-warning text-dark rounded-pill px-3"><i class="bi bi-dash-circle me-1" ></i> {{ $p->stock }}</span>
                            @else
                                <span class="badge bg-success rounded-pill px-3"><i class="bi bi-check-circle me-1"></i> {{ $p->stock }}</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <a href="{{ route('products.edit', $p->id) }}" class="btn btn-sm btn-outline-warning me-1" title="Edit">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <form action="{{ route('products.destroy', $p->id) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Yakin ingin menghapus produk ini?')" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-search display-4 d-block mb-3"></i>
                            Tidak ada produk yang ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection