<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko Maju Jaya - Sembako & Kebutuhan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #198754; /* Hijau Sembako */
            --secondary-color: #e9ecef;
            --accent-color: #ffc107; /* Kuning Emas */
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f6f9;
        }

        /* Navbar Styling */
        .navbar {
            background: linear-gradient(to right, #146c43, #198754) !important;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .navbar-brand {
            font-weight: 600;
            letter-spacing: 1px;
        }

        /* Card Styling */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            transition: transform 0.2s;
        }
        .card:hover {
            transform: translateY(-2px); 
        }

        /* Button Styling */
        .btn-primary {
            background-color: var(--primary-color);
            border: none;
        }
        .btn-primary:hover {
            background-color: #146c43;
        }

        /* Custom Table */
        .table thead {
            background-color: var(--primary-color);
            color: white;
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(25, 135, 84, 0.05);
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark mb-4 sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="#">
            <i class="bi bi-shop-window me-2 fs-4"></i> TOKO MAJU JAYA
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        @auth
        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav ms-auto align-items-center">
                
                {{-- MENU PRODUK --}}
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('products.*') ? 'active fw-bold' : '' }}" href="{{ route('products.index') }}">
                        <i class="bi bi-box-seam me-1"></i> Produk
                    </a>
                </li>

                {{-- MENU KASIR --}}
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('transactions.index') ? 'active fw-bold' : '' }}" href="{{ route('transactions.index') }}">
                        <i class="bi bi-calculator me-1"></i> Kasir
                    </a>
                </li>

                {{-- MENU RIWAYAT --}}
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('transactions.history') ? 'active fw-bold' : '' }}" href="{{ route('transactions.history') }}">
                        <i class="bi bi-clock-history me-1"></i> Riwayat
                    </a>
                </li>

                {{-- MENU LAPORAN (BARU DITAMBAHKAN) --}}
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('reports.*') ? 'active fw-bold' : '' }}" href="{{ route('reports.index') }}">
                        <i class="bi bi-bar-chart-line me-1"></i> Laporan
                    </a>
                </li>
                
                {{-- TOMBOL LOGOUT --}}
                <li class="nav-item ms-3">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="btn btn-outline-light btn-sm px-3 rounded-pill">
                            <i class="bi bi-box-arrow-right me-1"></i> Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
        @endauth
    </div>
</nav>

{{-- BAGIAN INI YANG MENGATUR TAMPILAN KONTEN & NOTIFIKASI --}}
<div class="container pb-5">
    
    {{-- 1. Pesan Sukses (Hijau) --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- 2. Pesan Error (Merah) --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    {{-- 3. Tempat Konten Halaman Lain Ditampilkan --}}
    @yield('content')

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>