@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="page-eyebrow">RINGKASAN</div>
    <h1 class="page-heading">Selamat datang kembali, {{ explode(' ', Auth::user()->name)[0] }}</h1>
    <p class="page-subtext">Berikut performa toko SwiftMart hari ini.</p>

    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-card d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-card-label">Pendapatan Hari Ini</div>
                    <div class="stat-card-value">Rp{{ number_format($pendapatanHariIni, 0, ',', '.') }}</div>
                </div>
                <div class="stat-card-icon"><i class="bi bi-currency-dollar"></i></div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-card-label">Transaksi Hari Ini</div>
                    <div class="stat-card-value">{{ $transaksiHariIni }}</div>
                </div>
                <div class="stat-card-icon"><i class="bi bi-receipt"></i></div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-card-label">Total Produk</div>
                    <div class="stat-card-value">{{ $totalProduk }}</div>
                </div>
                <div class="stat-card-icon"><i class="bi bi-box-seam"></i></div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-card-label">Stok Menipis</div>
                    <div class="stat-card-value">{{ $stokMenipis }}</div>
                </div>
                <div class="stat-card-icon" style="background:rgba(217,119,87,.15); color:var(--danger);"><i class="bi bi-exclamation-triangle"></i></div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-7">
            <div class="swift-card">
                <div class="fw-bold mb-1">Tren Penjualan</div>
                <div class="text-muted small mb-4">7 hari terakhir</div>

                <div class="d-flex align-items-end justify-content-between" style="height:200px;">
                    @foreach ($trenPenjualan as $hari)
                        @php $heightPct = $hari['total'] > 0 ? max(8, ($hari['total'] / $maxTren) * 100) : 4; @endphp
                        <div class="text-center" style="width: 12%;">
                            <div style="height:160px; display:flex; align-items:flex-end;">
                                <div style="width:100%; height:{{ $heightPct }}%; background:linear-gradient(to top, var(--teal), var(--teal-light)); border-radius:6px;"
                                     title="Rp{{ number_format($hari['total'], 0, ',', '.') }}"></div>
                            </div>
                            <div class="text-muted small mt-2">{{ $hari['label'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="swift-card">
                <div class="fw-bold mb-1">Produk Terlaris</div>
                <div class="text-muted small mb-3">Berdasarkan jumlah terjual</div>

                @forelse ($produkTerlaris as $i => $item)
                    <div class="d-flex align-items-center justify-content-between py-2 {{ !$loop->last ? 'border-bottom' : '' }}" style="border-color: var(--card-border) !important;">
                        <div class="d-flex align-items-center gap-3">
                            <div class="stat-card-icon" style="width:28px; height:28px; font-size:.8rem;">{{ $i + 1 }}</div>
                            <div>
                                <div class="fw-semibold">{{ $item->product->name ?? 'Produk dihapus' }}</div>
                                <div class="text-muted small">{{ $item->total_qty }} terjual</div>
                            </div>
                        </div>
                        <div class="fw-bold">Rp{{ number_format($item->total_omzet, 0, ',', '.') }}</div>
                    </div>
                @empty
                    <p class="text-muted small">Belum ada data penjualan.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection