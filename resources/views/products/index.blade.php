@extends('layouts.app')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold text-secondary"><i class="bi bi-boxes me-2"></i>Daftar Produk</h3>
    <a href="{{ route('products.create') }}" class="btn btn-success shadow-sm rounded-pill px-4">
        <i class="bi bi-plus-lg me-2"></i>Tambah Produk Baru
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-success text-white">
                    <tr>
                        <th class="ps-4 py-3">Nama Barang</th>
                        <th class="py-3">Harga Satuan</th>
                        <th class="py-3">Status Stok</th>
                        <th class="text-end pe-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $p)
                    <tr>
                        <td class="ps-4 fw-bold text-dark">{{ $p->name }}</td>
                        <td class="text-success fw-bold">Rp {{ number_format($p->price, 0, ',', '.') }}</td>
                        <td>
                            @if($p->stock < 5)
                                <span class="badge bg-danger rounded-pill px-3"><i class="bi bi-exclamation-circle me-1"></i> Kritis: {{ $p->stock }}</span>
                            @elseif($p->stock < 15)
                                <span class="badge bg-warning text-dark rounded-pill px-3"><i class="bi bi-dash-circle me-1"></i> Menipis: {{ $p->stock }}</span>
                            @else
                                <span class="badge bg-success rounded-pill px-3"><i class="bi bi-check-circle me-1"></i> Aman: {{ $p->stock }}</span>
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
                        <td colspan="4" class="text-center py-5 text-muted">
                            <i class="bi bi-box-seam display-4 d-block mb-3"></i>
                            Belum ada data produk. Silakan tambah produk baru.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection