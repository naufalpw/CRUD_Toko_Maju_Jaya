@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold">Laporan & Aktivitas</h3>
    </div>

    <ul class="nav nav-tabs mb-4" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active fw-bold" id="transaksi-tab" data-bs-toggle="tab" data-bs-target="#transaksi" type="button" role="tab" aria-controls="transaksi" aria-selected="true">
                <i class="bi bi-receipt me-1"></i> Riwayat Transaksi
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold" id="aktivitas-tab" data-bs-toggle="tab" data-bs-target="#aktivitas" type="button" role="tab" aria-controls="aktivitas" aria-selected="false">
                <i class="bi bi-activity me-1"></i> Log Aktivitas Toko
            </button>
        </li>
    </ul>

    <div class="tab-content" id="myTabContent">
        
        <div class="tab-pane fade show active" id="transaksi" role="tabpanel" aria-labelledby="transaksi-tab">
            @php $hasTransaction = false; @endphp
            
            @foreach($logs as $log)
                @if($log->transaction_id)
                    @php $hasTransaction = true; @endphp
                    <div class="card mb-4 shadow-sm border-primary">
                        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                            <span>
                                <i class="bi bi-cart-check-fill me-2"></i> 
                                <strong>{{ $log->transaction->invoice_code ?? 'INV' }}</strong> 
                                <span class="mx-2">|</span>
                                {{-- PERBAIKAN: Menampilkan Jam Realtime --}}
                                <small>{{ $log->created_at->format('d M Y, H:i:s') }} WIB</small>
                            </span>
                            <span class="fw-bold fs-5">
                                Rp {{ number_format($log->transaction->total_price ?? 0) }}
                            </span>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-sm table-striped mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Nama Barang</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-end pe-4">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($log->transaction->details as $detail)
                                    <tr>
                                        <td class="ps-4">{{ $detail->product->name ?? 'Produk dihapus' }}</td>
                                        <td class="text-center">{{ $detail->qty }}</td>
                                        <td class="text-end pe-4">Rp {{ number_format($detail->subtotal) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            @endforeach

            @if(!$hasTransaction)
                <div class="alert alert-info text-center py-4">Belum ada riwayat transaksi penjualan.</div>
            @endif
        </div>

        <div class="tab-pane fade" id="aktivitas" role="tabpanel" aria-labelledby="aktivitas-tab">
            <div class="list-group">
                @php $hasActivity = false; @endphp

                @foreach($logs as $log)
                    @if(!$log->transaction_id) 
                        @php $hasActivity = true; @endphp
                        
                        @php
                            $color = 'text-primary';
                            $icon = 'bi-info-circle';
                            if($log->action == 'Hapus Produk') { $color = 'text-danger'; $icon = 'bi-trash'; }
                            elseif($log->action == 'Edit Produk') { $color = 'text-warning'; $icon = 'bi-pencil-square'; }
                            elseif($log->action == 'Tambah Produk') { $color = 'text-success'; $icon = 'bi-plus-circle'; }
                        @endphp

                        <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold {{ $color }}">
                                    <i class="bi {{ $icon }} me-1"></i> {{ $log->action }}
                                </div>
                                {{ $log->description }}
                            </div>
                            
                            {{-- PERBAIKAN DI SINI: --}}
                            {{-- Mengubah diffForHumans() menjadi format jam spesifik --}}
                            <span class="badge bg-secondary rounded-pill" style="font-weight: normal; font-size: 0.85rem;">
                                {{ $log->created_at->format('d M Y, H:i:s') }} WIB
                            </span>
                        </div>
                    @endif
                @endforeach

                @if(!$hasActivity)
                    <div class="alert alert-warning text-center">Belum ada aktivitas admin yang tercatat.</div>
                @endif
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection