@extends('layouts.app')

@section('title', 'Edit Member')

@section('content')
    <div class="page-eyebrow">MASTER DATA</div>
    <h1 class="page-heading mb-4">Edit Member</h1>

    <div class="swift-card" style="max-width: 560px;">
        <form action="{{ route('members.update', $member->id) }}" method="post">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="name" class="form-label">Nama<span class="text-danger">*</span></label>
                <input type="text" value="{{ old('name', $member->name) }}" class="form-control @error('name') is-invalid @enderror" name="name" id="name">
                @error('name')
                    <span class="invalid-feedback d-block">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="phone" class="form-label">No. Telepon<span class="text-danger">*</span></label>
                <input type="text" name="phone" id="phone" value="{{ old('phone', $member->phone) }}" class="form-control @error('phone') is-invalid @enderror">
                @error('phone')
                    <span class="invalid-feedback d-block">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn-swift-primary"><i class="bi bi-check-lg me-1"></i>Simpan</button>
            <a href="{{ route('members.index') }}" class="btn-swift-ghost">Batal</a>
        </form>
    </div>
@endsection