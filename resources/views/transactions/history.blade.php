@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-secondary"><i class="bi bi-clipboard-data me-2"></i>Laporan & Aktivitas</h3>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-bottom-0 pt-4 px-4">
            <ul class="nav nav-pills nav-fill" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active fw-bold border" id="transaksi-tab" data-bs-toggle="tab" data-bs-target="#transaksi" type="button" role="tab" aria-selected="true">
                        <i class="bi bi-receipt me-1"></i> Riwayat Transaksi
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold border ms-2" id="aktivitas-tab" data-bs-toggle="tab" data-bs-target="#aktivitas" type="button" role="tab" aria-selected="false">
                        <i class="bi bi-activity me-1"></i> Log Aktivitas Toko
                    </button>
                </li>
            </ul>
        </div>

        <div class="card-body p-4">
            <div class="tab-content" id="myTabContent">
                
                <div class="tab-pane fade show active" id="transaksi" role="tabpanel">
                    @php $hasTransaction = false; @endphp
                    
                    <div class="row">
                    @foreach($logs as $log)
                        @if($log->transaction_id)
                            @php $hasTransaction = true; @endphp
                            <div class="col-md-12 mb-4">
                                <div class="card shadow-sm h-100 border-start border-5 border-success">
                                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="badge bg-success me-2">LUNAS</span>
                                            <span class="fw-bold text-dark">{{ $log->transaction->invoice_code ?? 'INV' }}</span>
                                        </div>
                                        <small class="text-muted"><i class="bi bi-calendar3 me-1"></i> {{ $log->created_at->format('d M Y, H:i:s') }} WIB</small>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-sm table-borderless mb-0">
                                            <thead class="text-muted border-bottom">
                                                <tr><th>Barang</th><th class="text-center">Qty</th><th class="text-end">Subtotal</th></tr>
                                            </thead>
                                            <tbody>
                                                @foreach($log->transaction->details as $detail)
                                                <tr>
                                                    {{-- MODIFIKASI: Menangani Produk yang Terhapus --}}
                                                    <td>
                                                        @if($detail->product)
                                                            {{ $detail->product->name }}
                                                        @else
                                                            <span class="text-danger fst-italic">
                                                                <i class="bi bi-exclamation-circle me-1"></i> (Produk Dihapus)
                                                            </span>
                                                        @endif
                                                    </td>
                                                    
                                                    <td class="text-center">{{ $detail->qty }}</td>
                                                    <td class="text-end">Rp {{ number_format($detail->subtotal) }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot class="border-top mt-2">
                                                <tr>
                                                    <td colspan="2" class="fw-bold text-end pt-3">TOTAL BAYAR</td>
                                                    <td class="fw-bold text-end text-success fs-5 pt-3">Rp {{ number_format($log->transaction->total_price ?? 0) }}</td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                    </div>

                    @if(!$hasTransaction)
                        <div class="text-center py-5">
                            <i class="bi bi-receipt display-1 text-muted opacity-25"></i>
                            <p class="mt-3 text-muted">Belum ada riwayat transaksi penjualan.</p>
                        </div>
                    @endif
                </div>

                <div class="tab-pane fade" id="aktivitas" role="tabpanel">
                    <div class="list-group list-group-flush">
                        @php $hasActivity = false; @endphp

                        @foreach($logs as $log)
                            @if(!$log->transaction_id) 
                                @php $hasActivity = true; @endphp
                                
                                @php
                                    $bgClass = 'bg-light';
                                    $iconClass = 'text-primary';
                                    $icon = 'bi-info-circle-fill';
                                    
                                    if($log->action == 'Hapus Produk') { $iconClass = 'text-danger'; $icon = 'bi-trash-fill'; $bgClass = 'bg-danger-subtle'; }
                                    elseif($log->action == 'Edit Produk') { $iconClass = 'text-warning'; $icon = 'bi-pencil-square'; $bgClass = 'bg-warning-subtle'; }
                                    elseif($log->action == 'Tambah Produk') { $iconClass = 'text-success'; $icon = 'bi-plus-circle-fill'; $bgClass = 'bg-success-subtle'; }
                                @endphp

                                <div class="list-group-item d-flex justify-content-between align-items-center py-3 border-bottom">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle p-2 me-3 {{ $bgClass }} {{ $iconClass }}">
                                            <i class="bi {{ $icon }} fs-5"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-bold">{{ $log->action }}</h6>
                                            <small class="text-muted">{{ $log->description }}</small>
                                        </div>
                                    </div>
                                    <span class="badge bg-secondary rounded-pill fw-normal">
                                        {{ $log->created_at->format('d M, H:i') }}
                                    </span>
                                </div>
                            @endif
                        @endforeach

                        @if(!$hasActivity)
                            <div class="text-center py-5">
                                <i class="bi bi-activity display-1 text-muted opacity-25"></i>
                                <p class="mt-3 text-muted">Belum ada aktivitas admin yang tercatat.</p>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection