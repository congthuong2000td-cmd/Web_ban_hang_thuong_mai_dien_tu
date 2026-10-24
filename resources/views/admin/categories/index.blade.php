@extends('layouts.admin')

@section('title', 'Quản lý danh mục - CT mart Admin')

@section('content')
<div class="content-wrapper p-3 p-md-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1" style="color:var(--ct-primary)">
                Danh mục ngành hàng
            </h1>
            <p class="text-muted small mb-0">Quản lý cây danh mục sản phẩm của CT mart.</p>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i>Thêm danh mục mới
        </a>
    </div>

    {{-- Search bar --}}
    <div class="card border-0 shadow-sm p-3 mb-4">
        <form action="{{ route('admin.categories.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-4">
                <input type="text" name="q" class="form-control form-control-sm" placeholder="Tìm kiếm theo tên danh mục..." value="{{ request('q') }}">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-search me-1"></i>Tìm kiếm
                </button>
            </div>
            @if(request('q'))
                <div class="col-auto">
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-link btn-sm text-danger text-decoration-none">
                        Xóa tìm kiếm
                    </a>
                </div>
            @endif
        </form>
    </div>

    {{-- Bảng danh mục --}}
    <div class="card border-0 shadow-sm p-0 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light small text-muted text-uppercase">
                    <tr>
                        <th class="ps-3" style="width: 60px;">ID</th>
                        <th>Tên danh mục</th>
                        <th>Đường dẫn (Slug)</th>
                        <th class="text-center">Số sản phẩm</th>
                        <th class="text-center">Trạng thái</th>
                        <th class="text-end pe-3">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $cat)
                        <tr>
                            <td class="ps-3 fw-bold text-muted small">{{ $cat->id }}</td>
                            <td>
                                <strong class="text-dark">{{ $cat->name }}</strong>
                                @if($cat->description)
                                    <div class="text-muted small text-truncate" style="max-width: 300px;">{{ $cat->description }}</div>
                                @endif
                            </td>
                            <td>
                                <code class="text-secondary small">{{ $cat->slug }}</code>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark border">{{ $cat->products_count }}</span>
                            </td>
                            <td class="text-center">
                                @if($cat->is_active)
                                    <span class="badge bg-success-subtle text-success">Hoạt động</span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary">Đang ẩn</span>
                                @endif
                            </td>
                            <td class="text-end pe-3">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.categories.edit', $cat) }}" class="btn btn-outline-secondary btn-sm py-0 px-2" title="Chỉnh sửa">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa danh mục này?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm py-0 px-2" title="Xóa">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                Không tìm thấy danh mục nào.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($categories->hasPages())
            <div class="p-3 border-top d-flex justify-content-center">
                {{ $categories->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
