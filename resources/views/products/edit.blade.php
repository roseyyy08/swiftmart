@extends('layouts.app')

@section('title', 'Edit Produk')

@section('content')
    <div class="container py-4">
        <h1 class="page-title mb-3">Edit Produk!</h1>

        <div class="row">
            <div class="col-md-6">
                <form action="{{ route('products.update', $product->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="form-group mb-2">
                        <label for="category_id" class="form-label">Kategori<span class="text-danger">*</span></label>
                        <select name="category_id" id="category_id" class="form-select @error('category_id') is-invalid @enderror">
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group mb-2">
                        <label for="name" class="form-label">Nama Produk<span class="text-danger">*</span></label>
                        <input type="text" value="{{ old('name', $product->name) }}" class="form-control @error('name') is-invalid @enderror" name="name" id="name">
                        @error('name')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group mb-2">
                        <label for="barcode" class="form-label">Barcode<span class="text-danger">*</span></label>
                        <input type="text" value="{{ old('barcode', $product->barcode) }}" class="form-control @error('barcode') is-invalid @enderror" name="barcode" id="barcode">
                        @error('barcode')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group mb-2">
                        <label for="price" class="form-label">Harga<span class="text-danger">*</span></label>
                        <input type="number" value="{{ old('price', $product->price) }}" class="form-control @error('price') is-invalid @enderror" name="price" id="price">
                        @error('price')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group mb-2">
                        <label for="stock" class="form-label">Stok<span class="text-danger">*</span></label>
                        <input type="number" value="{{ old('stock', $product->stock) }}" class="form-control @error('stock') is-invalid @enderror" name="stock" id="stock">
                        @error('stock')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    @if ($product->image)
                        <div class="mb-2">
                            <img src="{{ asset('storage/'.$product->image) }}" width="100" class="img-thumbnail">
                        </div>
                    @endif

                    <div class="form-group mb-3">
                        <label for="image" class="form-label">Ganti Gambar (opsional)</label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" name="image" id="image">
                        @error('image')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('products.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
@endsection