@extends('layouts.app')

@section('title', 'Thông tin tài khoản - CT mart')

@section('content')
<div class="container-xl py-4">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb mb-0" style="font-size:0.875rem;">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('account.profile') }}">Tài khoản</a></li>
            <li class="breadcrumb-item active" aria-current="page">Hồ sơ</li>
        </ol>
    </nav>

    <div class="row g-4">
        <aside class="col-lg-3">
            @include('account.partials.sidebar')
        </aside>

        <main class="col-lg-9">
            {{-- Form thông tin cá nhân --}}
            <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: var(--ct-radius);">
                <h1 class="h5 fw-bold pb-2 mb-3 border-bottom" style="color:var(--ct-primary)">
                    <i class="bi bi-person-lines-fill text-primary me-2"></i>Hồ sơ cá nhân
                </h1>

                <form action="{{ route('account.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label small fw-semibold text-dark">Họ và tên <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="form-label small fw-semibold text-dark">Địa chỉ Email</label>
                            <input type="email" class="form-control bg-light" id="email" value="{{ $user->email }}" readonly disabled>
                            <div class="form-text small">Email dùng để đăng nhập và nhận thông báo đơn hàng.</div>
                        </div>

                        <div class="col-md-6">
                            <label for="phone" class="form-label small fw-semibold text-dark">Số điện thoại</label>
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                   id="phone" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="Ví dụ: 0912345678">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-save me-1"></i>Lưu thay đổi
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Form đổi mật khẩu --}}
            <div class="card border-0 shadow-sm p-4" style="border-radius: var(--ct-radius);">
                <h2 class="h5 fw-bold pb-2 mb-3 border-bottom" style="color:var(--ct-primary)">
                    <i class="bi bi-key-fill text-warning me-2"></i>Đổi mật khẩu
                </h2>

                <form action="{{ route('account.password.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="current_password" class="form-label small fw-semibold text-dark">Mật khẩu hiện tại <span class="text-danger">*</span></label>
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror"
                                   id="current_password" name="current_password" required>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="password" class="form-label small fw-semibold text-dark">Mật khẩu mới <span class="text-danger">*</span></label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                   id="password" name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="password_confirmation" class="form-label small fw-semibold text-dark">Xác nhận mật khẩu mới <span class="text-danger">*</span></label>
                            <input type="password" class="form-control"
                                   id="password_confirmation" name="password_confirmation" required>
                        </div>

                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-outline-primary px-4">
                                <i class="bi bi-shield-check me-1"></i>Cập nhật mật khẩu
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </main>
    </div>
</div>
@endsection
