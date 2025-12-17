@extends('layouts.app')

@section('content')
<div class="row justify-content-center align-items-center" style="min-height: 70vh;">
    <div class="col-md-5">
        <div class="text-center mb-4">
            <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow" style="width: 80px; height: 80px;">
                <i class="bi bi-shop fs-1"></i>
            </div>
            <h3 class="fw-bold text-success">Toko Maju Jaya</h3>
            <p class="text-muted">Sistem Manajemen Stok & Kasir Sembako</p>
        </div>

        <div class="card shadow-lg border-0">
            <div class="card-body p-4 p-md-5">
                @if($errors->any())
                    <div class="alert alert-danger d-flex align-items-center">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="form-floating mb-3">
                        <input type="email" name="email" class="form-control" id="emailInput" placeholder="name@example.com" value="{{ old('email') }}" required autofocus>
                        <label for="emailInput">Alamat Email</label>
                    </div>
                    <div class="form-floating mb-4">
                        <input type="password" name="password" class="form-control" id="passwordInput" placeholder="Password" required>
                        <label for="passwordInput">Password</label>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-success btn-lg py-3 fw-bold shadow-sm">
                            MASUK SISTEM <i class="bi bi-arrow-right-circle ms-2"></i>
                        </button>
                    </div>
                </form>
            </div>
            <div class="card-footer bg-light text-center py-3">
                <small class="text-muted">&copy; {{ date('Y') }} Toko Maju Jaya. All rights reserved.</small>
            </div>
        </div>
    </div>
</div>
@endsection