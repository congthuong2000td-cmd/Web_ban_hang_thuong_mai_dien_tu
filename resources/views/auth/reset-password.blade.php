@extends('layouts.auth')

@section('title', 'Đặt lại mật khẩu')

@section('content')
    <h2 class="fw-bold mb-1" style="color:var(--ct-primary)">Đặt lại mật khẩu</h2>
    <p class="text-muted mb-4" style="font-size:0.9rem">Nhập mật khẩu mới cho tài khoản của bạn.</p>

    <form method="POST" action="{{ route('password.store') }}" data-prevent-double-submit>
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="mb-3">
            <label for="email" class="form-label fw-semibold small">Địa chỉ email</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror"
                   id="email" name="email" value="{{ old('email', $request->email) }}"
                   autocomplete="email" autofocus required>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label fw-semibold small">Mật khẩu mới</label>
            <input type="password" class="form-control @error('password') is-invalid @enderror"
                   id="password" name="password" autocomplete="new-password"
                   placeholder="Ít nhất 8 ký tự" required>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label for="password_confirmation" class="form-label fw-semibold small">Xác nhận mật khẩu mới</label>
            <input type="password" class="form-control"
                   id="password_confirmation" name="password_confirmation"
                   autocomplete="new-password" required>
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold" style="border-radius:var(--ct-radius)">
            <i class="bi bi-shield-check me-2"></i>Đặt lại mật khẩu
        </button>
    </form>
@endsection
