@extends('layouts.app')

@section('title', 'Laporan Penjualan')

@section('content')
    <div class="container py-4">
        <h1 class="page-title mb-3"><i class="bi bi-bar-chart-line"></i> Laporan Penjualan</h1>

        <div class="row g-3 mb-3">
            <div class="col-md-4">
                <div class="card text-bg-primary">
                    <div class="card-body">
                        <h6 class="card-subtitle mb-2">Total Pendapatan</h6>
                        <h4 class="mb-0">Rp{{ number_format($totalPendapatan, 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-bg-success">
                    <div class="card-body">
                        <h6 class="card-subtitle mb-2">Jumlah Transaksi</h6>
                        <h4 class="mb-0">{{ $totalTransaksi }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-3">
                <label class="form-label">Dari Tanggal</label>
                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Sampai Tanggal</label>
                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Metode Bayar</label>
                <select name="payment_method" class="form-select">
                    <option value="">Semua</option>
                    <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Tunai</option>
                    <option value="debit" {{ request('payment_method') == 'debit' ? 'selected' : '' }}>Debit</option>
                    <option value="qris" {{ request('payment_method') == 'qris' ? 'selected' : '' }}>QRIS</option>
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-funnel me-1"></i>Filter</button>
                <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</a>
            </div>
        </form>

        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Invoice</th>
                    <th>Tanggal</th>
                    <th>Pelanggan</th>
                    <th>Item</th>
                    <th>Metode</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @if ($transactions->count() == 0)
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada transaksi ditemukan</td>
                    </tr>
                @endif

                @foreach ($transactions as $trx)
                    <tr>
                        <td>{{ $trx->invoice }}</td>
                        <td>{{ $trx->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $trx->member->name ?? 'Guest' }}</td>
                        <td>{{ $trx->details->sum('quantity') }} produk</td>
                        <td>{{ strtoupper($trx->payment_method) }}</td>
                        <td>Rp{{ number_format($trx->total, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {!! $transactions->links() !!}
    </div>
@endsection