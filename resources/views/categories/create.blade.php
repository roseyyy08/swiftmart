@extends('layouts.app')

@section('title', 'Tambah Kategori')

@section('content')
    <div class="page-eyebrow">MASTER DATA</div>
    <h1 class="page-heading mb-4">Tambah Kategori</h1>

    <div class="swift-card" style="max-width: 560px;">
        <form action="{{ route('categories.store') }}" method="post">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">Nama Kategori<span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" value="{{ old('name') }}">
                @error('name')
                    <span class="invalid-feedback d-block">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="description" class="form-label">Deskripsi</label>
                <textarea name="description" id="description" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                @error('description')
                    <span class="invalid-feedback d-block">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn-swift-primary"><i class="bi bi-check-lg me-1"></i>Simpan</button>
            <a href="{{ route('categories.index') }}" class="btn-swift-ghost">Batal</a>
        </form>
    </div>
@endsection