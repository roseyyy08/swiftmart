@extends('layouts.app')

@section('title', 'Members Page')

@section('content')
    <div class="container py-4">
        <h1 class="page-title mb-3">Members Page!</h1>

        <a href="{{ route('members.create') }}" class="btn btn-primary mb-3">
            <i class="bi bi-plus-price"></i>Tambah Member
        </a>

        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th width="50px">NO</th>
                    <th>NAME</th>
                    <th>PHONE</th>
                    <th>POINTS</th>
                    <th>AKSI</th>
                </tr>
            </thead>

            <tbody>
                @if ($members->count() == 0)
                    <tr>
                        <td colspan="5" class="text-center">
                            Data Members not found!
                        </td>
                    </tr>
                @endif

                @foreach ($members as $member)
                    <tr>
                        <td>{{ $member->id }}</td>
                        <td>{{ $member->name }}</td>
                        <td>{{ $member->phone }}</td>
                        <td>{{ $member->points }}</td>

                        <td>
                            <a href="{{ route('members.edit', $member->id) }}"
                                class="btn btn-link p-0">
                                <i class="bi bi-pencil-square"></i>
                            </a>

                            <a href="javascript:void(0)"
                                onclick="actionDestroy('{{ route('members.destroy', $member->id) }}')"
                                class="btn btn-link text-danger p-0">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {!! $members->links() !!}
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