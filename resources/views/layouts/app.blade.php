<!DOCTYPE html>
<html>
<head>
    <title>Toko Maju Jaya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">TOKO MAJU JAYA</a>
        
        @auth
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="{{ route('products.index') }}">Produk</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('transactions.index') }}">Kasir</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('transactions.history') }}">Riwayat</a></li>
                
                <li class="nav-item ms-3">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="btn btn-danger btn-sm mt-1">Logout</button>
                    </form>
                </li>
            </ul>
        </div>
        @endauth

    </div>
</nav>

    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @yield('content')
    </div>
</body>
</html>