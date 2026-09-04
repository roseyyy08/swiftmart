@extends('layouts.guest')

@section('title', 'Login Admin - SwiftMart')

@section('content')
    <div class="guest-card text-start">
        <h5 class="mb-4 text-center" style="font-family:'Playfair Display',serif; font-weight:700;">Login Admin</h5>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label">{{ __('Email Address') }}</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                @error('email')
                    <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">{{ __('Password') }}</label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                @error('password')
                    <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label" for="remember">{{ __('Remember Me') }}</label>
            </div>

            <button type="submit" class="btn-swift-primary w-100 mb-2">{{ __('Login') }}</button>

            @if (Route::has('password.request'))
                <div class="text-center">
                    <a href="{{ route('password.request') }}" class="text-muted small">{{ __('Forgot Your Password?') }}</a>
                </div>
            @endif
        </form>
    </div>

    <div class="mt-3">
        <a href="{{ url('/') }}" class="text-muted small">&larr; Kembali ke Halaman Utama</a>
    </div>
@endsection