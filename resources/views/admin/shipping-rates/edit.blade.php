@extends('layouts.admin')

@section('title', 'Chỉnh sửa biểu phí: ' . $shippingRate->province_name . ' - CT mart Admin')

@section('content')
<div class="content-wrapper p-3 p-md-4">
    <div class="mb-4">
        <a href="{{ route('admin.shipping-rates.index') }}" class="btn btn-outline-secondary btn-sm mb-2">
            <i class="bi bi-arrow-left me-1"></i>Quay lại danh sách
        </a>
        <h1 class="h3 fw-bold mb-1" style="color:var(--ct-primary)">Chỉnh sửa biểu phí: {{ $shippingRate->province_name }}</h1>
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
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm p-4">
                <form action="{{ route('admin.shipping-rates.update', $shippingRate) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="province_name" class="form-label small fw-semibold text-dark">Tên Tỉnh / Thành phố <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('province_name') is-invalid @enderror" id="province_name" name="province_name" value="{{ old('province_name', $shippingRate->province_name) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="fee" class="form-label small fw-semibold text-dark">Cước phí giao hàng (₫) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('fee') is-invalid @enderror" id="fee" name="fee" value="{{ old('fee', $shippingRate->fee) }}" min="0" step="1000" required>
                    </div>

                    <div class="mb-4">
                        <label for="estimated_days" class="form-label small fw-semibold text-dark">Thời gian giao dự kiến (Số ngày) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('estimated_days') is-invalid @enderror" id="estimated_days" name="estimated_days" value="{{ old('estimated_days', $shippingRate->estimated_days) }}" min="1" max="30" required>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-save me-1"></i>Lưu thay đổi
                        </button>
                        <a href="{{ route('admin.shipping-rates.index') }}" class="btn btn-outline-secondary px-3">
                            Hủy bỏ
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
