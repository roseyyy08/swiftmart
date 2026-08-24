@extends('layouts.app')

@section('title', 'Create Categories')

@section('content')
    <div class="container py-4">
        <h1 class="page-title mb-3">Create Categories!</h1>

         <div class="row">
            <div class="col-md-6">
                <form action="{{ route('categories.store') }}" method="post">
                    @csrf

            <div class="form-group mb-2">
                <label for="name" class="form-label">Name<span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name">

                @error('name')
                <span class="invalid-feedback d-block" role="alert">
                    {{ $message }}
                </span>
                @enderror
            </div>

            <div class="form-group mb-2">
                <label for="description" class="form-label">Description <span class="text-danger">*</span></label>

                <textarea
                    name="description"
                    id="description"
                    class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>

                @error('description')
                    <span class="invalid-feedback d-block">
                        {{ $message }}
                    </span>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('categories.index') }}" class="btn btn-secondary">Batal</a>
        </form>
            </div>
        </div>

        

    </div> 
@endsection