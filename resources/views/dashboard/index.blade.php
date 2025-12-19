@extends('layouts.app')

@section('content')
<div class="container-fluid px-4"> 
    
    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4 mt-3">
        <div>
            <h3 class="fw-bold text-dark mb-0">Dashboard Ringkasan</h3>
            <p class="text-muted small mb-0">Pantau performa toko dan keuangan Anda hari ini.</p>
        </div>
        <div class="text-end">
            <span class="badge bg-primary fs-6 shadow-sm py-2 px-3">
                <i class="bi bi-clock me-2"></i>
                <span id="liveClock">{{ date('d M Y') }}</span>
            </span>
        </div>
    </div>

{{-- BAGIAN KARTU LABA/RUGI (PROFIT) --}}
    <div class="row g-3 mb-4">
        
        {{-- 1. Laba Hari Ini --}}
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100 border-start border-4 {{ $todayProfit < 0 ? 'border-danger' : 'border-success' }}">
                <div class="card-body p-3">
                    <small class="text-muted fw-bold d-block mb-1">Laba Hari Ini</small>
                    <h5 class="fw-bold mb-0 {{ $todayProfit < 0 ? 'text-danger' : 'text-success' }}">
                        @if($todayProfit < 0)
                            <i class="bi bi-arrow-down-right me-1"></i>
                        @else
                            <i class="bi bi-arrow-up-right me-1"></i>
                        @endif
                        Rp {{ number_format($todayProfit, 0, ',', '.') }}
                    </h5>
                </div>
            </div>
        </div>

        {{-- 2. Laba Minggu Ini --}}
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100 border-start border-4 {{ $weekProfit < 0 ? 'border-danger' : 'border-success' }}">
                <div class="card-body p-3">
                    <small class="text-muted fw-bold d-block mb-1">Laba Minggu Ini</small>
                    <h5 class="fw-bold mb-0 {{ $weekProfit < 0 ? 'text-danger' : 'text-success' }}">
                        @if($weekProfit < 0)
                            <i class="bi bi-arrow-down-right me-1"></i>
                        @else
                            <i class="bi bi-arrow-up-right me-1"></i>
                        @endif
                        Rp {{ number_format($weekProfit, 0, ',', '.') }}
                    </h5>
                </div>
            </div>
        </div>

        {{-- 3. Laba Bulan Ini --}}
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100 border-start border-4 {{ $monthProfit < 0 ? 'border-danger' : 'border-success' }}">
                <div class="card-body p-3">
                    <small class="text-muted fw-bold d-block mb-1">Laba Bulan Ini</small>
                    <h5 class="fw-bold mb-0 {{ $monthProfit < 0 ? 'text-danger' : 'text-success' }}">
                        @if($monthProfit < 0)
                            <i class="bi bi-arrow-down-right me-1"></i>
                        @else
                            <i class="bi bi-arrow-up-right me-1"></i>
                        @endif
                        Rp {{ number_format($monthProfit, 0, ',', '.') }}
                    </h5>
                </div>
            </div>
        </div>

        {{-- 4. Laba Tahun Ini --}}
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100 border-start border-4 {{ $yearProfit < 0 ? 'border-danger' : 'border-success' }}">
                <div class="card-body p-3">
                    <small class="text-muted fw-bold d-block mb-1">Laba Tahun Ini</small>
                    <h5 class="fw-bold mb-0 {{ $yearProfit < 0 ? 'text-danger' : 'text-success' }}">
                        @if($yearProfit < 0)
                            <i class="bi bi-arrow-down-right me-1"></i>
                        @else
                            <i class="bi bi-arrow-up-right me-1"></i>
                        @endif
                        Rp {{ number_format($yearProfit, 0, ',', '.') }}
                    </h5>
                </div>
            </div>
        </div>
    </div>

    {{-- BAGIAN 1: GRAFIK ANALITIK --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3 border-bottom">
            <h5 class="mb-0 fw-bold text-secondary"><i class="bi bi-bar-chart-line me-2"></i>Tren Keuangan (7 Hari Terakhir)</h5>
        </div>
        <div class="card-body">
            <div style="height: 300px;"> 
                <canvas id="financeChart"></canvas>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- BAGIAN 2: KOLOM KIRI (FORM AKSI CEPAT) --}}
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-danger text-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-dash-circle me-2"></i>Catat Pengeluaran Baru</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('reports.store_expense') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label text-secondary fw-bold small">Nama Pengeluaran</label>
                            <input type="text" name="name" class="form-control" placeholder="Cth: Listrik, Plastik..." required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-secondary fw-bold small">Jumlah (Rp)</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="amount" class="form-control" placeholder="0" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-secondary fw-bold small">Tanggal</label>
                            <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-secondary fw-bold small">Keterangan</label>
                            <textarea name="description" class="form-control" rows="2" placeholder="Opsional..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-danger w-100 fw-bold mt-2">
                            <i class="bi bi-save me-1"></i> Simpan Data
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- BAGIAN 3: KOLOM KANAN (DATA CENTER - TABULASI) --}}
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white pt-4 px-4 border-bottom-0">
                    <ul class="nav nav-pills nav-fill gap-2" id="dashboardTab" role="tablist">
                        {{-- Tab 1: Penjualan --}}
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active fw-bold border" id="transaksi-tab" data-bs-toggle="tab" data-bs-target="#transaksi" type="button" role="tab">
                                <i class="bi bi-receipt me-1"></i> Penjualan (Omset)
                            </button>
                        </li>
                        {{-- Tab 2: Pengeluaran --}}
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-bold border" id="pengeluaran-tab" data-bs-toggle="tab" data-bs-target="#pengeluaran" type="button" role="tab">
                                <i class="bi bi-wallet2 me-1"></i> Riwayat Pengeluaran
                            </button>
                        </li>
                        {{-- Tab 3: Log Sistem --}}
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-bold border" id="aktivitas-tab" data-bs-toggle="tab" data-bs-target="#aktivitas" type="button" role="tab">
                                <i class="bi bi-activity me-1"></i> Log Sistem
                            </button>
                        </li>
                    </ul>
                </div>

                <div class="card-body p-4">
                    <div class="tab-content" id="dashboardTabContent">
                        
                        {{-- KONTEN TAB 1: TRANSAKSI PENJUALAN --}}
                        <div class="tab-pane fade show active" id="transaksi" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-bold text-muted">Transaksi Terakhir</h6>
                                <a href="#" class="small text-decoration-none">Lihat Semua</a>
                            </div>

                            @php $hasTransaction = false; @endphp
                            <div class="row g-3">
                            @foreach($logs as $log)
                                @if($log->transaction_id)
                                    @php $hasTransaction = true; @endphp
                                    <div class="col-12">
                                        <div class="card border-start border-4 border-success bg-light shadow-sm">
                                            <div class="card-body py-2">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <div>
                                                        <span class="badge bg-success">LUNAS</span>
                                                        <span class="fw-bold ms-2">{{ $log->transaction->invoice_code ?? 'INV' }}</span>
                                                    </div>
                                                    <small class="text-muted">{{ $log->created_at->format('d M H:i') }}</small>
                                                </div>
                                                
                                                <ul class="list-unstyled mb-2 small ps-2 border-start">
                                                    @foreach($log->transaction->details as $detail)
                                                        <li>{{ $detail->product->name ?? 'Item Dihapus' }} (x{{ $detail->qty }})</li>
                                                    @endforeach
                                                </ul>

                                                <div class="d-flex justify-content-between align-items-center border-top pt-2">
                                                    <span class="fw-bold text-dark fs-5">Rp {{ number_format($log->transaction->total_price ?? 0) }}</span>
                                                    <a href="{{ route('transactions.print', $log->transaction->id) }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                                                        <i class="bi bi-printer"></i> Cetak
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                            </div>
                            @if(!$hasTransaction)
                                <div class="text-center text-muted py-4">Belum ada penjualan terbaru.</div>
                            @endif
                        </div>

                        {{-- KONTEN TAB 2: RIWAYAT PENGELUARAN --}}
                        <div class="tab-pane fade" id="pengeluaran" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Keperluan</th>
                                            <th class="text-end">Nominal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($latestExpenses as $exp)
                                        <tr>
                                            <td class="text-muted small">{{ \Carbon\Carbon::parse($exp->date)->format('d M') }}</td>
                                            <td>
                                                <span class="fw-bold d-block">{{ $exp->name }}</span>
                                                <small class="text-muted">{{ Str::limit($exp->description, 30) }}</small>
                                            </td>
                                            <td class="text-end text-danger fw-bold">- Rp {{ number_format($exp->amount, 0, ',', '.') }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="3" class="text-center py-4 text-muted">Belum ada pengeluaran.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- KONTEN TAB 3: LOG AKTIVITAS --}}
                        <div class="tab-pane fade" id="aktivitas" role="tabpanel">
                            <div class="list-group list-group-flush">
                                @php $hasActivity = false; @endphp
                                @foreach($logs as $log)
                                    @if(!$log->transaction_id) 
                                        @php $hasActivity = true; 
                                             $icon = 'bi-info-circle'; $color = 'text-primary';
                                             if($log->action == 'Hapus Produk') { $icon='bi-trash'; $color='text-danger'; }
                                             elseif($log->action == 'Edit Produk') { $icon='bi-pencil'; $color='text-warning'; }
                                             elseif($log->action == 'Tambah Produk') { $icon='bi-plus-circle'; $color='text-success'; }
                                        @endphp
                                        <div class="list-group-item py-3 px-0 border-bottom">
                                            <div class="d-flex">
                                                <div class="me-3 {{ $color }}"><i class="bi {{ $icon }} fs-5"></i></div>
                                                <div>
                                                    <h6 class="mb-0 fw-bold small">{{ $log->action }}</h6>
                                                    <p class="mb-0 small text-muted">{{ $log->description }}</p>
                                                    <small class="text-secondary fst-italic" style="font-size: 10px;">{{ $log->created_at->diffForHumans() }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                                @if(!$hasActivity)
                                    <div class="text-center text-muted py-4">Tidak ada aktivitas sistem.</div>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- SCRIPT --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    function updateClock() {
        const now = new Date();
        const dateOptions = { weekday: 'short', day: 'numeric', month: 'short', year: 'numeric' };
        const dateString = now.toLocaleDateString('id-ID', dateOptions);
        const timeString = now.toLocaleTimeString('id-ID', { hour12: false });
        document.getElementById('liveClock').innerText = `${dateString} - ${timeString} WIB`;
    }
    setInterval(updateClock, 1000);
    updateClock();

    const ctx = document.getElementById('financeChart').getContext('2d');
    const labels = {!! json_encode($dates) !!};
    const incomeData = {!! json_encode($incomes) !!};
    const expenseData = {!! json_encode($expenses) !!};

    const financeChart = new Chart(ctx, {
        type: 'bar', 
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Pemasukan',
                    data: incomeData,
                    backgroundColor: 'rgba(25, 135, 84, 0.8)',
                    borderColor: 'rgba(25, 135, 84, 1)',
                    borderWidth: 1,
                    borderRadius: 4
                },
                {
                    label: 'Pengeluaran',
                    data: expenseData,
                    backgroundColor: 'rgba(220, 53, 69, 0.8)',
                    borderColor: 'rgba(220, 53, 69, 1)',
                    borderWidth: 1,
                    borderRadius: 4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { borderDash: [2, 4] },
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + (value/1000).toLocaleString('id-ID') + 'rb';
                        }
                    }
                },
                x: { grid: { display: false } }
            },
            plugins: {
                legend: { position: 'top' },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) label += ': ';
                            if (context.parsed.y !== null) label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(context.parsed.y);
                            return label;
                        }
                    }
                }
            }
        }
    });
</script>
@endsection