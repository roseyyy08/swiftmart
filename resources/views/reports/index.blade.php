@extends('layouts.app')

@section('title', 'Laporan')

@section('content')
    <div class="page-eyebrow">LAPORAN</div>
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 class="page-heading">Laporan Penjualan</h1>
            <p class="page-subtext mb-0">Rekap transaksi berdasarkan periode, metode pembayaran, dan tipe pelanggan.</p>
        </div>
        <button type="button" class="btn-swift-primary" disabled title="Belum tersedia">
            <i class="bi bi-download me-1"></i>Export PDF
        </button>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-md-4">
            <div class="stat-card"><div class="stat-card-label">Total Pendapatan</div><div class="stat-card-value">Rp{{ number_format($totalPendapatan, 0, ',', '.') }}</div></div>
        </div>
        <div class="col-6 col-md-4">
            <div class="stat-card"><div class="stat-card-label">Jumlah Transaksi</div><div class="stat-card-value">{{ $totalTransaksi }}</div></div>
        </div>
        <div class="col-6 col-md-4">
            <div class="stat-card"><div class="stat-card-label">Rata-rata / Transaksi</div><div class="stat-card-value">Rp{{ number_format($rataRata, 0, ',', '.') }}</div></div>
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
        <div class="col-md-2">
            <label class="form-label">Metode Bayar</label>
            <select name="payment_method" class="form-select">
                <option value="">Semua</option>
                <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Tunai</option>
                <option value="debit" {{ request('payment_method') == 'debit' ? 'selected' : '' }}>Debit</option>
                <option value="qris" {{ request('payment_method') == 'qris' ? 'selected' : '' }}>QRIS</option>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">Tipe Pelanggan</label>
            <select name="tipe_pelanggan" class="form-select">
                <option value="">Semua</option>
                <option value="member" {{ request('tipe_pelanggan') == 'member' ? 'selected' : '' }}>Member</option>
                <option value="guest" {{ request('tipe_pelanggan') == 'guest' ? 'selected' : '' }}>Guest</option>
            </select>
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn-swift-primary w-100">Terapkan Filter</button>
        </div>
    </form>

    <div class="swift-card">
        <table class="swift-table">
            <thead><tr><th>INVOICE</th><th>TANGGAL</th><th>PELANGGAN</th><th>ITEM</th><th>METODE</th><th>TOTAL</th></tr></thead>
            <tbody>
                @if ($transactions->count() == 0)
                    <tr><td colspan="6" class="text-center text-muted py-4">Tidak ada transaksi ditemukan.</td></tr>
                @endif

                @foreach ($transactions as $trx)
                    <tr>
                        <td class="text-muted">{{ $trx->invoice }}</td>
                        <td class="text-muted">{{ $trx->created_at->format('d M Y, H:i') }}</td>
                        <td><span class="pill pill-neutral">{{ $trx->member->name ?? 'guest' }}</span></td>
                        <td class="text-muted">{{ $trx->details->sum('quantity') }} produk</td>
                        <td>
                            <span class="pill {{ $trx->payment_method == 'qris' ? 'pill-success' : 'pill-neutral' }}">{{ strtoupper($trx->payment_method) }}</span>
                        </td>
                        <td class="fw-bold">Rp{{ number_format($trx->total, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-3">{!! $transactions->links() !!}</div>
    </div>
@endsection