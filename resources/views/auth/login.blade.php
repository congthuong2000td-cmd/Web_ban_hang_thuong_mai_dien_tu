@extends('layouts.auth')

@section('title', 'Đăng nhập')

@section('content')
    <h2 class="fw-bold mb-1" style="color:var(--ct-primary)">Đăng nhập</h2>
    <p class="text-muted mb-4" style="font-size:0.9rem">Chào mừng quay lại CT mart!</p>

    @if($errors->any())
        <div class="alert alert-danger border-0 rounded-3 py-2 px-3 mb-3" style="font-size:0.9rem">
            <i class="bi bi-exclamation-triangle me-2"></i>
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" data-prevent-double-submit>
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label fw-semibold small">Địa chỉ email</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror"
                   id="email" name="email" value="{{ old('email') }}"
                   autocomplete="email" autofocus placeholder="ten@email.com" required>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <label for="password" class="form-label fw-semibold small mb-0">Mật khẩu</label>
                <a href="{{ route('password.request') }}" class="small" style="color:var(--ct-accent)">Quên mật khẩu?</a>
            </div>
            <div class="input-group">
                <input type="password" class="form-control @error('password') is-invalid @enderror"
                       id="password" name="password" autocomplete="current-password"
                       placeholder="Ít nhất 8 ký tự" required>
                <button class="btn btn-outline-secondary" type="button" id="togglePassword" aria-label="Hiện/ẩn mật khẩu">
                    <i class="bi bi-eye" id="togglePasswordIcon"></i>
                </button>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label small" for="remember">Ghi nhớ đăng nhập</label>
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold" style="border-radius:var(--ct-radius)">
            Đăng nhập
        </button>
    </form>

    <div class="text-center mt-4 small">
        Chưa có tài khoản?
        <a href="{{ route('register') }}" class="fw-semibold" style="color:var(--ct-accent)">Đăng ký ngay</a>
    </div>
@endsection

@push('scripts')
<script>
    document.getElementById('togglePassword')?.addEventListener('click', function() {
        const input = document.getElementById('password');
        const icon = document.getElementById('togglePasswordIcon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'bi bi-eye-slash';
        } else {
            input.type = 'password';
            icon.className = 'bi bi-eye';
        }
    });
</script>
@endpush
