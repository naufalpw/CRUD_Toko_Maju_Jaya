@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-secondary"><i class="bi bi-bar-chart-line me-2"></i>Laporan Keuangan</h3>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold">Grafik Pemasukan vs Pengeluaran (7 Hari Terakhir)</h5>
        </div>
        <div class="card-body">
            <canvas id="financeChart" height="100"></canvas>
        </div>
    </div>

    <div class="row">
        <div class="col-md-5">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-danger text-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-dash-circle me-2"></i>Catat Pengeluaran</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('reports.store_expense') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label text-secondary fw-bold">Nama Pengeluaran</label>
                            <input type="text" name="name" class="form-control" placeholder="Contoh: Bayar Listrik, Beli Stok..." required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-secondary fw-bold">Jumlah (Rp)</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="amount" class="form-control" placeholder="0" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-secondary fw-bold">Tanggal</label>
                            <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-secondary fw-bold">Keterangan (Opsional)</label>
                            <textarea name="description" class="form-control" rows="2"></textarea>
                        </div>
                        <button type="submit" class="btn btn-danger w-100 fw-bold">Simpan Pengeluaran</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">Riwayat Pengeluaran Terbaru</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Tanggal</th>
                                <th>Nama</th>
                                <th class="text-end pe-4">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($latestExpenses as $exp)
                            <tr>
                                <td class="ps-4 text-muted small">{{ \Carbon\Carbon::parse($exp->date)->format('d M Y') }}</td>
                                <td class="fw-bold text-dark">{{ $exp->name }}<br><small class="text-muted fw-normal">{{ $exp->description }}</small></td>
                                <td class="text-end pe-4 text-danger fw-bold">- Rp {{ number_format($exp->amount, 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center py-4 text-muted">Belum ada data pengeluaran.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('financeChart').getContext('2d');
    
    // Data dari Controller Laravel (Blade variable to JS)
    const labels = {!! json_encode($dates) !!};
    const incomeData = {!! json_encode($incomes) !!};
    const expenseData = {!! json_encode($expenses) !!};

    const financeChart = new Chart(ctx, {
        type: 'bar', // Bisa diganti 'line' jika ingin grafik garis
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Pemasukan (Omset)',
                    data: incomeData,
                    backgroundColor: 'rgba(25, 135, 84, 0.7)', // Warna Hijau Success
                    borderColor: 'rgba(25, 135, 84, 1)',
                    borderWidth: 1,
                    borderRadius: 5
                },
                {
                    label: 'Pengeluaran',
                    data: expenseData,
                    backgroundColor: 'rgba(220, 53, 69, 0.7)', // Warna Merah Danger
                    borderColor: 'rgba(220, 53, 69, 1)',
                    borderWidth: 1,
                    borderRadius: 5
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            if (context.parsed.y !== null) {
                                label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(context.parsed.y);
                            }
                            return label;
                        }
                    }
                }
            }
        }
    });
</script>
@endsection