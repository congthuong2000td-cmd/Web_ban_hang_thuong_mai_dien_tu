@extends('layouts.app')

@section('title', 'Chỉnh sửa địa chỉ - CT mart')

@section('content')
<div class="container-xl py-4">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb mb-0" style="font-size:0.875rem;">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('account.profile') }}">Tài khoản</a></li>
            <li class="breadcrumb-item"><a href="{{ route('account.addresses.index') }}">Sổ địa chỉ</a></li>
            <li class="breadcrumb-item active" aria-current="page">Chỉnh sửa</li>
        </ol>
    </nav>

    <div class="row g-4">
        <aside class="col-lg-3">
            @include('account.partials.sidebar')
        </aside>

        <main class="col-lg-9">
            <div class="card border-0 shadow-sm p-4" style="border-radius: var(--ct-radius);">
                <h1 class="h5 fw-bold pb-2 mb-3 border-bottom" style="color:var(--ct-primary)">
                    <i class="bi bi-pencil-square text-primary me-2"></i>Chỉnh sửa địa chỉ nhận hàng
                </h1>

                <form action="{{ route('account.addresses.update', $address) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="receiver_name" class="form-label small fw-semibold text-dark">Họ và tên người nhận <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('receiver_name') is-invalid @enderror"
                                   id="receiver_name" name="receiver_name" value="{{ old('receiver_name', $address->receiver_name) }}" required>
                            @error('receiver_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="receiver_phone" class="form-label small fw-semibold text-dark">Số điện thoại <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control @error('receiver_phone') is-invalid @enderror"
                                   id="receiver_phone" name="receiver_phone" value="{{ old('receiver_phone', $address->receiver_phone) }}" required>
                            @error('receiver_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="province" class="form-label small fw-semibold text-dark">Tỉnh / Thành phố <span class="text-danger">*</span></label>
                            <select class="form-select @error('province') is-invalid @enderror" id="province" name="province" required>
                                <option value="">-- Chọn Tỉnh / Thành phố --</option>
                                @foreach($shippingRates as $rate)
                                    <option value="{{ $rate->province_name }}" {{ old('province', $address->province) === $rate->province_name ? 'selected' : '' }}>
                                        {{ $rate->province_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('province')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="ward" class="form-label small fw-semibold text-dark">Quận / Huyện / Phường <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('ward') is-invalid @enderror"
                                   id="ward" name="ward" value="{{ old('ward', $address->ward) }}" required>
                            @error('ward')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="street_address" class="form-label small fw-semibold text-dark">Địa chỉ chi tiết (Số nhà, đường) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('street_address') is-invalid @enderror"
                                   id="street_address" name="street_address" value="{{ old('street_address', $address->street_address) }}" required>
                            @error('street_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_default" value="1" id="is_default" {{ old('is_default', $address->is_default) ? 'checked' : '' }}>
                                <label class="form-check-label small" for="is_default">
                                    Đặt làm địa chỉ nhận hàng mặc định
                                </label>
                            </div>
                        </div>

                        <div class="col-12 mt-4 d-flex gap-2">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-save me-1"></i>Cập nhật địa chỉ
                            </button>
                            <a href="{{ route('account.addresses.index') }}" class="btn btn-outline-secondary px-3">
                                Hủy bỏ
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </main>
    </div>
</div>
@endsection
