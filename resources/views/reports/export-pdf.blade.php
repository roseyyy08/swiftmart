<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Penjualan - SwiftMart</title>
    <style>
        :root {
            --teal: #1f7a6c;
            --gold: #cda45a;
            --text: #1c2622;
            --text-muted: #6b7a74;
            --border: #dfe6e2;
        }
        * { box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            color: var(--text);
            margin: 32px;
            font-size: 13px;
        }
        .toolbar {
            display: flex;
            justify-content: flex-end;
            gap: 8px;
            margin-bottom: 20px;
        }
        .btn {
            padding: 8px 16px;
            border-radius: 6px;
            border: 1px solid var(--teal);
            background: var(--teal);
            color: #fff;
            font-weight: 600;
            cursor: pointer;
            font-size: 13px;
        }
        .btn-outline {
            background: #fff;
            color: var(--teal);
        }
        header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            border-bottom: 3px solid var(--gold);
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .brand { font-size: 22px; font-weight: 800; }
        .brand .accent { color: var(--gold); }
        h1 { font-size: 18px; margin: 4px 0 0; }
        .meta { text-align: right; color: var(--text-muted); font-size: 12px; }
        .stats {
            display: flex;
            gap: 12px;
            margin-bottom: 20px;
        }
        .stat-box {
            flex: 1;
            border: 1px solid var(--border);
            border-top: 3px solid var(--teal);
            border-radius: 6px;
            padding: 10px 14px;
        }
        .stat-box .label { font-size: 11px; color: var(--text-muted); text-transform: uppercase; }
        .stat-box .value { font-size: 17px; font-weight: 700; margin-top: 2px; }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            text-align: left;
            padding: 8px 10px;
            border-bottom: 1px solid var(--border);
        }
        th {
            background: #f4f6f5;
            font-size: 11px;
            text-transform: uppercase;
            color: var(--text-muted);
        }
        td.num, th.num { text-align: right; }
        tfoot td {
            font-weight: 700;
            border-top: 2px solid var(--text);
            border-bottom: none;
        }
        footer {
            margin-top: 24px;
            font-size: 11px;
            color: var(--text-muted);
            text-align: center;
        }

        @media print {
            .toolbar { display: none; }
            body { margin: 12mm; }
        }
    </style>
</head>
<body>

    <div class="toolbar">
        <button class="btn" onclick="window.print()">🖨️ Cetak / Simpan sebagai PDF</button>
        <button class="btn btn-outline" onclick="window.close()">Tutup</button>
    </div>

    <header>
        <div class="brand">Swift<span class="accent">Mart</span></div>
        <div class="meta">
            <div>Dicetak: {{ now()->format('d M Y, H:i') }}</div>
        </div>
    </header>

    <h1>Laporan Penjualan</h1>
    <p style="color: var(--text-muted); margin-top: 4px;">
        Periode:
        {{ request('date_from') ? \Carbon\Carbon::parse(request('date_from'))->format('d M Y') : 'Semua' }}
        &mdash;
        {{ request('date_to') ? \Carbon\Carbon::parse(request('date_to'))->format('d M Y') : 'Semua' }}
        @if (request('payment_method'))
            &nbsp;|&nbsp; Metode: {{ strtoupper(request('payment_method')) }}
        @endif
        @if (request('tipe_pelanggan'))
            &nbsp;|&nbsp; Pelanggan: {{ ucfirst(request('tipe_pelanggan')) }}
        @endif
    </p>

    <div class="stats">
        <div class="stat-box">
            <div class="label">Total Pendapatan</div>
            <div class="value">Rp{{ number_format($totalPendapatan, 0, ',', '.') }}</div>
        </div>
        <div class="stat-box">
            <div class="label">Jumlah Transaksi</div>
            <div class="value">{{ $totalTransaksi }}</div>
        </div>
        <div class="stat-box">
            <div class="label">Rata-rata / Transaksi</div>
            <div class="value">Rp{{ number_format($rataRata, 0, ',', '.') }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Invoice</th>
                <th>Tanggal</th>
                <th>Pelanggan</th>
                <th class="num">Item</th>
                <th>Metode</th>
                <th class="num">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transactions as $trx)
                <tr>
                    <td>{{ $trx->invoice }}</td>
                    <td>{{ $trx->created_at->format('d M Y, H:i') }}</td>
                    <td>{{ $trx->member->name ?? 'Guest' }}</td>
                    <td class="num">{{ $trx->details->sum('quantity') }}</td>
                    <td>{{ strtoupper($trx->payment_method) }}</td>
                    <td class="num">Rp{{ number_format($trx->total, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr><td colspan="6" style="text-align:center; color: var(--text-muted);">Tidak ada transaksi pada periode ini.</td></tr>
            @endforelse
        </tbody>
        @if ($transactions->count() > 0)
            <tfoot>
                <tr>
                    <td colspan="5">Total</td>
                    <td class="num">Rp{{ number_format($totalPendapatan, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        @endif
    </table>

    <footer>
        SwiftMart Self-Checkout System &mdash; Laporan digenerate otomatis dari sistem.
    </footer>

    <script>
        window.addEventListener('load', function () {
            setTimeout(function () { window.print(); }, 300);
        });
    </script>

</body>
</html>