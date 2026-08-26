@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="container py-4">
        <h1 class="page-title mb-4">Dashboard</h1>

        <div class="row g-3">
            <div class="col-md-4">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <h6 class="card-title">Pendapatan Hari Ini</h6>
                        <p class="fs-4 fw-bold mb-0">
                            Rp{{ number_format($pendapatanHariIni, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <h6 class="card-title">Transaksi Hari Ini</h6>
                        <p class="fs-4 fw-bold mb-0">{{ $transaksiHariIni }}</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-white bg-info">
                    <div class="card-body">
                        <h6 class="card-title">Total Produk</h6>
                        <p class="fs-4 fw-bold mb-0">{{ $totalProduk }}</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-white bg-warning">
                    <div class="card-body">
                        <h6 class="card-title">Total Kategori</h6>
                        <p class="fs-4 fw-bold mb-0">{{ $totalKategori }}</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-white bg-secondary">
                    <div class="card-body">
                        <h6 class="card-title">Total Member</h6>
                        <p class="fs-4 fw-bold mb-0">{{ $totalMember }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection