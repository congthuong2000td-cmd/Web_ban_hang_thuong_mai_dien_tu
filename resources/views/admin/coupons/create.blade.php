@extends('layouts.admin')

@section('title', 'Tạo mã giảm giá mới - CT mart Admin')

@section('content')
<div class="content-wrapper p-3 p-md-4">
    <div class="mb-4">
        <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-secondary btn-sm mb-2">
            <i class="bi bi-arrow-left me-1"></i>Quay lại danh sách
        </a>
        <h1 class="h3 fw-bold mb-1" style="color:var(--ct-primary)">Tạo mã giảm giá mới</h1>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger mb-4">
            <h6 class="fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-1"></i>Vui lòng kiểm tra lại:</h6>
            <ul class="mb-0 ps-3 small">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm p-4">
                <form action="{{ route('admin.coupons.store') }}" method="POST">
                    @csrf

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="code" class="form-label small fw-semibold text-dark">Mã giảm giá (Code) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control text-uppercase @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code') }}" placeholder="VD: SALE50, TET2026" required>
                        </div>
                        <div class="col-md-6">
                            <label for="name" class="form-label small fw-semibold text-dark">Tên chương trình khuyến mãi</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="VD: Khuyến mãi mừng khai trương">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="discount_type" class="form-label small fw-semibold text-dark">Loại giảm giá <span class="text-danger">*</span></label>
                            <select class="form-select" id="discount_type" name="discount_type" required>
                                <option value="percentage" {{ old('discount_type') === 'percentage' ? 'selected' : '' }}>Giảm theo phần trăm (%)</option>
                                <option value="fixed" {{ old('discount_type') === 'fixed' ? 'selected' : '' }}>Giảm số tiền cố định (VNĐ)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="discount_value" class="form-label small fw-semibold text-dark">Giá trị giảm <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="discount_value" name="discount_value" value="{{ old('discount_value') }}" min="1" placeholder="Nhập số % (VD: 10) hoặc số tiền (VD: 50000)" required>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="min_order_amount" class="form-label small fw-semibold text-dark">Đơn hàng tối thiểu (₫)</label>
                            <input type="number" class="form-control" id="min_order_amount" name="min_order_amount" value="{{ old('min_order_amount', 0) }}" min="0" placeholder="0 nếu không yêu cầu">
                        </div>
                        <div class="col-md-6">
                            <label for="max_discount" class="form-label small fw-semibold text-dark">Mức giảm tối đa (₫)</label>
                            <input type="number" class="form-control" id="max_discount" name="max_discount" value="{{ old('max_discount') }}" min="0" placeholder="Chỉ áp dụng với giảm % (Để trống nếu không giới hạn)">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label for="usage_limit" class="form-label small fw-semibold text-dark">Số lượt dùng tối đa</label>
                            <input type="number" class="form-control" id="usage_limit" name="usage_limit" value="{{ old('usage_limit') }}" min="1" placeholder="Để trống nếu vô hạn">
                        </div>
                        <div class="col-md-4">
                            <label for="starts_at" class="form-label small fw-semibold text-dark">Ngày bắt đầu</label>
                            <input type="datetime-local" class="form-control" id="starts_at" name="starts_at" value="{{ old('starts_at') }}">
                        </div>
                        <div class="col-md-4">
                            <label for="expires_at" class="form-label small fw-semibold text-dark">Ngày hết hạn</label>
                            <input type="datetime-local" class="form-control" id="expires_at" name="expires_at" value="{{ old('expires_at') }}">
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }}>
                            <label class="form-check-label small fw-semibold" for="is_active">Kích hoạt mã giảm giá ngay</label>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-save me-1"></i>Lưu mã giảm giá
                        </button>
                        <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-secondary px-3">
                            Hủy bỏ
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
