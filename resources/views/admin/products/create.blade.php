@extends('layouts.admin')

@section('title', 'Thêm sản phẩm mới - CT mart Admin')

@section('content')
<div class="content-wrapper p-3 p-md-4">
    <div class="mb-4">
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary btn-sm mb-2">
            <i class="bi bi-arrow-left me-1"></i>Quay lại danh sách
        </a>
        <h1 class="h3 fw-bold mb-1" style="color:var(--ct-primary)">Thêm sản phẩm mới</h1>
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

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row g-4">
            {{-- Cột trái: Thông tin chính --}}
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm p-4 mb-4">
                    <h2 class="h6 fw-bold mb-3 border-bottom pb-2 text-dark">Thông tin cơ bản</h2>

                    <div class="mb-3">
                        <label for="name" class="form-label small fw-semibold text-dark">Tên sản phẩm <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Ví dụ: iPhone 15 Pro Max 256GB" required>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="slug" class="form-label small fw-semibold text-dark">Đường dẫn (Slug - Tùy chọn)</label>
                            <input type="text" class="form-control" id="slug" name="slug" value="{{ old('slug') }}" placeholder="iphone-15-pro-max (Để trống để tự tạo)">
                        </div>
                        <div class="col-md-6">
                            <label for="sku" class="form-label small fw-semibold text-dark">Mã SKU</label>
                            <input type="text" class="form-control" id="sku" name="sku" value="{{ old('sku') }}" placeholder="IP15PM-256 (Để trống để tự tạo)">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label small fw-semibold text-dark">Mô tả chi tiết sản phẩm</label>
                        <textarea class="form-control" id="description" name="description" rows="7" placeholder="Đặc điểm nổi bật, thông số kỹ thuật, hướng dẫn sử dụng...">{{ old('description') }}</textarea>
                    </div>
                </div>

                <div class="card border-0 shadow-sm p-4 mb-4">
                    <h2 class="h6 fw-bold mb-3 border-bottom pb-2 text-dark">Giá bán & Tồn kho</h2>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="price" class="form-label small fw-semibold text-dark">Giá bán thực tế (₫) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price') }}" min="0" step="1000" placeholder="28990000" required>
                        </div>
                        <div class="col-md-4">
                            <label for="original_price" class="form-label small fw-semibold text-dark">Giá niêm yết (Gốc - ₫)</label>
                            <input type="number" class="form-control" id="original_price" name="original_price" value="{{ old('original_price') }}" min="0" step="1000" placeholder="32990000">
                            <div class="form-text small">Hiển thị giá gạch ngang để kích thích mua.</div>
                        </div>
                        <div class="col-md-4">
                            <label for="stock_quantity" class="form-label small fw-semibold text-dark">Số lượng kho <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('stock_quantity') is-invalid @enderror" id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity', 10) }}" min="0" required>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Cột phải: Phân loại, Ảnh, Trạng thái --}}
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm p-4 mb-4">
                    <h2 class="h6 fw-bold mb-3 border-bottom pb-2 text-dark">Phân loại & Trạng thái</h2>

                    <div class="mb-3">
                        <label for="category_id" class="form-label small fw-semibold text-dark">Danh mục ngành hàng <span class="text-danger">*</span></label>
                        <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                            <option value="">-- Chọn danh mục --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }}>
                            <label class="form-check-label small fw-semibold" for="is_active">Kích hoạt bán ngay</label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="is_featured" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                            <label class="form-check-label small fw-semibold" for="is_featured">Ghim sản phẩm nổi bật</label>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm p-4 mb-4">
                    <h2 class="h6 fw-bold mb-3 border-bottom pb-2 text-dark">Hình ảnh</h2>

                    <div class="mb-3">
                        <label for="thumbnail" class="form-label small fw-semibold text-dark">Ảnh đại diện chính</label>
                        <input type="file" class="form-control form-control-sm" id="thumbnail" name="thumbnail" accept="image/*">
                        <div class="form-text small">Định dạng JPG, PNG, WEBP (Tối đa 4MB).</div>
                    </div>

                    <div class="mb-3">
                        <label for="gallery" class="form-label small fw-semibold text-dark">Ảnh thư viện chi tiết</label>
                        <input type="file" class="form-control form-control-sm" id="gallery" name="gallery[]" accept="image/*" multiple>
                        <div class="form-text small">Chọn cùng lúc nhiều ảnh minh họa.</div>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary py-2 fw-semibold">
                        <i class="bi bi-save me-1"></i>Lưu sản phẩm
                    </button>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                        Hủy bỏ
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
