@extends('layouts.app')

@section('title', 'Produk Page')

@section('content')
    <div class="container py-4">
        <h1 class="page-title mb-3">Produk Page!</h1>

        <a href="{{ route('products.create') }}" class="btn btn-primary mb-3">
            Tambah Produk
        </a>

        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th width="50px">NO</th>
                    <th width="70px">GAMBAR</th>
                    <th>NAMA PRODUK</th>
                    <th>KATEGORI</th>
                    <th>BARCODE</th>
                    <th>HARGA</th>
                    <th>STOK</th>
                    <th>AKSI</th>
                </tr>
            </thead>

            <tbody>
                @if ($products->count() == 0)
                    <tr>
                        <td colspan="8" class="text-center">
                            Data Produk not found!
                        </td>
                    </tr>
                @endif

                @foreach ($products as $product)
                    <tr>
                        <td>{{ $product->id }}</td>
                        <td>
                            @if ($product->image)
                                <img src="{{ asset('storage/'.$product->image) }}" width="50">
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->category->name ?? '-' }}</td>
                        <td>{{ $product->barcode }}</td>
                        <td>Rp{{ number_format($product->price, 0, ',', '.') }}</td>
                        <td>{{ $product->stock }}</td>
                        <td>
                            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-link p-0">
                                Edit
                            </a>

                            <a href="javascript:void(0)"
                                onclick="actionDestroy('{{ route('products.destroy', $product->id) }}')"
                                class="btn btn-link text-danger p-0">
                                Hapus
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {!! $products->links() !!}
    </div>

    <form action="" id="form-destroy" method="POST">
        @csrf
        @method('DELETE')
    </form>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function actionDestroy(url) {
            Swal.fire({
                title: 'Apakah anda yakin akan menghapusnya?',
                text: 'Kamu tidak bisa memulihkannya!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
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
        <script>
            Swal.fire({
                title: 'Berhasil!',
                text: '{{ Session::get('success') }}',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        </script>
    @endif
@endsection