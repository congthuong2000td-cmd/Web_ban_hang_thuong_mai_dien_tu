@extends('layouts.auth')

@section('title', 'Quên mật khẩu')

@section('content')
    <h2 class="fw-bold mb-1" style="color:var(--ct-primary)">Quên mật khẩu?</h2>
    <p class="text-muted mb-4" style="font-size:0.9rem">Nhập email đăng ký để nhận liên kết đặt lại mật khẩu.</p>

    @if(session('status'))
        <div class="alert alert-success border-0 rounded-3 py-2 px-3 mb-3" style="font-size:0.9rem">
            <i class="bi bi-check-circle me-2"></i>{{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" data-prevent-double-submit>
        @csrf
        <div class="mb-4">
            <label for="email" class="form-label fw-semibold small">Địa chỉ email</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror"
                   id="email" name="email" value="{{ old('email') }}"
                   autofocus required placeholder="ten@email.com">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold" style="border-radius:var(--ct-radius)">
            <i class="bi bi-envelope-arrow-up me-2"></i>Gửi liên kết đặt lại
        </button>
    </form>

    <div class="text-center mt-4 small">
        <a href="{{ route('login') }}" class="fw-semibold" style="color:var(--ct-accent)">
            <i class="bi bi-arrow-left me-1"></i>Quay lại đăng nhập
        </a>
    </div>
@endsection
