@extends('layouts.app')

@section('title', 'Update Member')

@section('content')
    <div class="container py-4">
        <h1 class="page-title mb-3">Update Members!</h1>

         <div class="row">
            <div class="col-md-6">
                <form action="{{ route('members.update', $member->id ) }}" method="post">
                    @csrf
                    @method('PUT')

            <div class="form-group mb-2">
                <label for="name" class="form-label">Name<span class="text-danger">*</span></label>
                <input type="text" value="{{ old('name')?? $member->name }}" class="form-control @error('name') is-invalid @enderror" name="name" id="name">

                @error('name')
                <span class="invalid-feedback d-block" role="alert">
                    {{ $message }}
                </span>
                @enderror
            </div>

            <div class="form-group mb-2">
                <label for="phone" class="form-label">Phone<span class="text-danger">*</span></label>

                <input
                    type="text"
                    name="phone"
                    id="phone"
                    value="{{ old('phone') ?? $member->phone }}"
                    class="form-control @error('phone') is-invalid @enderror">

                @error('phone')
                    <span class="invalid-feedback d-block">
                        {{ $message }}
                    </span>
                @enderror
            </div>


            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('members.index') }}" class="btn btn-secondary">Batal</a>
        </form>
            </div>
        </div>

        

    </div> 
@endsection