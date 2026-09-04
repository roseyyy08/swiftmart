@extends('layouts.guest')

@section('title', 'SwiftMart - Halaman Utama')

@section('content')
    <div class="page-eyebrow mb-2">Sistem Kasir Mandiri</div>
    <p class="mb-4" style="color:var(--text-muted); font-size:1.05rem;">Selamat datang di SwiftMart, silakan pilih menu di bawah.</p>

    <div class="d-grid gap-3">
        <a href="{{ route('checkout.index') }}" class="btn-swift-primary">
            <i class="bi bi-cart3 me-2"></i>MULAI BELANJA
        </a>
        <a href="{{ route('login') }}" class="btn-swift-outline">
            <i class="bi bi-shield-lock me-2"></i>LOGIN ADMIN
        </a>
    </div>
@endsection