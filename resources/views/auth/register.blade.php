@extends('layouts.auth')

@section('title', 'Đăng ký tài khoản')

@section('content')
    <h2 class="fw-bold mb-1" style="color:var(--ct-primary)">Tạo tài khoản</h2>
    <p class="text-muted mb-4" style="font-size:0.9rem">Mua sắm dễ dàng hơn với CT mart!</p>

    <form method="POST" action="{{ route('register') }}" data-prevent-double-submit>
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label fw-semibold small">Họ và tên</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror"
                   id="name" name="name" value="{{ old('name') }}"
                   autocomplete="name" autofocus placeholder="Nguyễn Văn A" required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label fw-semibold small">Địa chỉ email</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror"
                   id="email" name="email" value="{{ old('email') }}"
                   autocomplete="email" placeholder="ten@email.com" required>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label fw-semibold small">
                Số điện thoại <span class="text-muted fw-normal">(tuỳ chọn)</span>
            </label>
            <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                   id="phone" name="phone" value="{{ old('phone') }}"
                   autocomplete="tel" placeholder="0xxx xxx xxx">
            @error('phone')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label fw-semibold small">Mật khẩu</label>
            <input type="password" class="form-control @error('password') is-invalid @enderror"
                   id="password" name="password" autocomplete="new-password"
                   placeholder="Ít nhất 8 ký tự" required>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label for="password_confirmation" class="form-label fw-semibold small">Xác nhận mật khẩu</label>
            <input type="password" class="form-control"
                   id="password_confirmation" name="password_confirmation"
                   autocomplete="new-password" placeholder="Nhập lại mật khẩu" required>
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold" style="border-radius:var(--ct-radius)">
            Tạo tài khoản
        </button>
    </form>

    <div class="text-center mt-4 small">
        Đã có tài khoản?
        <a href="{{ route('login') }}" class="fw-semibold" style="color:var(--ct-accent)">Đăng nhập</a>
    </div>
@endsection
