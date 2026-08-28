@extends('layouts.app')

@section('title', 'Categories Page')

@section('content')
    <div class="container py-4">
        <h1 class="page-title mb-3">Categories Page!</h1>

        <a href="{{ route('categories.create') }}" class="btn btn-primary mb-3">
            <i class="bi bi-plus-price"></i>Tambah Category
        </a>

        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th width="50px">NO</th>
                    <th>CATEGORY NAME</th>
                    <th>DESCRIPTION</th>
                    <th>AKSI</th>
                </tr>
            </thead>

            <tbody>
                @if ($categories->count() == 0)
                    <tr>
                        <td colspan="4" class="text-center">
                            Data Categories not found!
                        </td>
                    </tr>
                @endif

                @foreach ($categories as $category)
                    <tr>
                        <td>{{ $category->id }}</td>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->description }}</td>

                        <td>
                            <a href="{{ route('categories.edit', $category->id) }}"
                                class="btn btn-link p-0">
                                <i class="bi bi-pencil-square"></i>
                            </a>

                            <a href="javascript:void(0)"
                                onclick="actionDestroy('{{ route('categories.destroy', $category->id) }}')"
                                class="btn btn-link text-danger p-0">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {!! $categories->links() !!}
    </div>

    {{-- Form Delete --}}
    <form action="" id="form-destroy" method="POST">
        @csrf
        @method('DELETE')
    </form>

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Delete Confirmation --}}
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

    {{-- Success Alert --}}
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