@extends('layouts.app')

@section('title', 'Produk')

@section('content')
    <div class="page-eyebrow">MASTER DATA</div>
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 class="page-heading">Produk</h1>
            <p class="page-subtext mb-0">Kelola katalog produk SwiftMart.</p>
        </div>
        <a href="{{ route('products.create') }}" class="btn-swift-primary">
            <i class="bi bi-plus-lg me-1"></i>Tambah Produk
        </a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-md-4">
            <div class="stat-card"><div class="stat-card-label">Total Produk</div><div class="stat-card-value">{{ $totalProduk }}</div></div>
        </div>
        <div class="col-6 col-md-4">
            <div class="stat-card"><div class="stat-card-label">Stok Menipis</div><div class="stat-card-value">{{ $stokMenipis }}</div></div>
        </div>
        <div class="col-6 col-md-4">
            <div class="stat-card"><div class="stat-card-label">Nonaktif</div><div class="stat-card-value">{{ $nonaktif }}</div></div>
        </div>
    </div>

    <form method="GET" class="d-flex gap-2 mb-3 flex-wrap">
        <div class="flex-grow-1" style="min-width:220px;">
            <input type="text" name="search" class="form-control" placeholder="Cari nama atau barcode..." value="{{ request('search') }}">
        </div>
        <select name="category_id" class="form-select" style="max-width:180px;" onchange="this.form.submit()">
            <option value="">Semua Kategori</option>
            @foreach ($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>
        <select name="stock_filter" class="form-select" style="max-width:180px;" onchange="this.form.submit()">
            <option value="">Semua Stok</option>
            <option value="low" {{ request('stock_filter') == 'low' ? 'selected' : '' }}>Stok Menipis</option>
            <option value="empty" {{ request('stock_filter') == 'empty' ? 'selected' : '' }}>Stok Habis</option>
        </select>
        <button type="submit" class="btn-swift-primary"><i class="bi bi-search"></i></button>
    </form>

    <div class="swift-card">
        <table class="swift-table">
            <thead>
                <tr><th>PRODUK</th><th>KATEGORI</th><th>BARCODE</th><th>HARGA</th><th>STOK</th><th>STATUS</th><th>AKSI</th></tr>
            </thead>
            <tbody>
                @if ($products->count() == 0)
                    <tr><td colspan="7" class="text-center text-muted py-4">Produk tidak ditemukan.</td></tr>
                @endif

                @foreach ($products as $product)
                    <tr>
                        <td class="d-flex align-items-center gap-2">
                            @if ($product->image)
                                <img src="{{ asset('storage/'.$product->image) }}" width="36" height="36" style="object-fit:cover; border-radius:6px;">
                            @else
                                <div style="width:36px; height:36px; background:var(--bg-2); border-radius:6px;"></div>
                            @endif
                            <span class="fw-semibold">{{ $product->name }}</span>
                        </td>
                        <td class="text-muted">{{ $product->category->name ?? '-' }}</td>
                        <td class="text-muted">{{ $product->barcode }}</td>
                        <td>Rp{{ number_format($product->price, 0, ',', '.') }}</td>
                        <td>
                            <span class="pill {{ $product->stock <= 5 ? 'pill-danger' : 'pill-success' }}">{{ $product->stock }}</span>
                        </td>
                        <td>
                            <span class="pill {{ $product->is_active ? 'pill-success' : 'pill-neutral' }}">{{ $product->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                        </td>
                        <td>
                            <a href="{{ route('products.edit', $product->id) }}" class="btn-swift-ghost">Edit</a>
                            <a href="javascript:void(0)" onclick="actionDestroy('{{ route('products.destroy', $product->id) }}')" class="btn-swift-ghost-danger">Hapus</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-3">{!! $products->links() !!}</div>
    </div>

    <form action="" id="form-destroy" method="POST">
        @csrf
        @method('DELETE')
    </form>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function actionDestroy(url) {
            Swal.fire({
                title: 'Apakah anda yakin akan menghapusnya?', text: 'Kamu tidak bisa memulihkannya!', icon: 'warning',
                showCancelButton: true, confirmButtonText: 'Ya, hapus!', cancelButtonText: 'Batal',
                background: '#131f19', color: '#e7f1ee'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('form-destroy');
                    form.action = url;
                    form.submit();
                }
            });
        }
    </script>

    @if (Session::has('success'))
        <script>Swal.fire({ title: 'Berhasil!', text: '{{ Session::get('success') }}', icon: 'success', background: '#131f19', color: '#e7f1ee' });</script>
    @endif
@endsection