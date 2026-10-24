@extends('layouts.admin')

@section('title', 'Chỉnh sửa danh mục - CT mart Admin')

@section('content')
<div class="content-wrapper p-3 p-md-4">
    <div class="mb-4">
        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary btn-sm mb-2">
            <i class="bi bi-arrow-left me-1"></i>Quay lại danh sách
        </a>
        <h1 class="h3 fw-bold mb-1" style="color:var(--ct-primary)">Chỉnh sửa danh mục: {{ $category->name }}</h1>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm p-4">
                <form action="{{ route('admin.categories.update', $category) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label small fw-semibold text-dark">Tên danh mục <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $category->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="slug" class="form-label small fw-semibold text-dark">Đường dẫn (Slug)</label>
                        <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug', $category->slug) }}">
                        @error('slug')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label small fw-semibold text-dark">Mô tả ngắn</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $category->description) }}</textarea>
                    </div>

                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label small fw-semibold" for="is_active">Kích hoạt hiển thị trên website</label>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-save me-1"></i>Lưu thay đổi
                        </button>
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary px-3">
                            Hủy bỏ
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
