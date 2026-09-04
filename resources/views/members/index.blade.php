@extends('layouts.app')

@section('title', 'Member')

@section('content')
    <div class="page-eyebrow">MASTER DATA</div>
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 class="page-heading">Member</h1>
            <p class="page-subtext mb-0">Kelola member SwiftMart.</p>
        </div>
        <a href="{{ route('members.create') }}" class="btn-swift-primary">
            <i class="bi bi-plus-lg me-1"></i>Tambah Member
        </a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-md-4">
            <div class="stat-card"><div class="stat-card-label">Total Member</div><div class="stat-card-value">{{ $totalMember }}</div></div>
        </div>
        <div class="col-6 col-md-4">
            <div class="stat-card"><div class="stat-card-label">Total Poin Beredar</div><div class="stat-card-value">{{ $totalPoinBeredar }}</div></div>
        </div>
        <div class="col-6 col-md-4">
            <div class="stat-card"><div class="stat-card-label">Rata-rata Poin</div><div class="stat-card-value">{{ $rataRataPoin }}</div></div>
        </div>
    </div>

    <div class="swift-card">
        <table class="swift-table">
            <thead><tr><th>NO</th><th>NAMA</th><th>NO. TELEPON</th><th>POIN</th><th>AKSI</th></tr></thead>
            <tbody>
                @if ($members->count() == 0)
                    <tr><td colspan="5" class="text-center text-muted py-4">Belum ada member.</td></tr>
                @endif

                @foreach ($members as $member)
                    <tr>
                        <td>{{ $member->id }}</td>
                        <td class="fw-semibold">{{ $member->name }}</td>
                        <td class="text-muted">{{ $member->phone }}</td>
                        <td><span class="pill pill-gold">{{ $member->points }} pts</span></td>
                        <td>
                            <a href="{{ route('members.edit', $member->id) }}" class="btn-swift-ghost">Edit</a>
                            <a href="javascript:void(0)" onclick="actionDestroy('{{ route('members.destroy', $member->id) }}')" class="btn-swift-ghost-danger">Hapus</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-3">{!! $members->links() !!}</div>
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