@extends('layouts.admin')

@section('title', 'Quản lý sản phẩm - CT mart Admin')

@section('content')
<div class="content-wrapper p-3 p-md-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1" style="color:var(--ct-primary)">
                Quản lý sản phẩm
            </h1>
            <p class="text-muted small mb-0">Danh sách toàn bộ sản phẩm trong hệ thống cửa hàng CT mart.</p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i>Thêm sản phẩm mới
        </a>
    </div>

    {{-- Bộ lọc & Tìm kiếm --}}
    <div class="card border-0 shadow-sm p-3 mb-4">
        <form action="{{ route('admin.products.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-4">
                <input type="text" name="q" class="form-control form-control-sm" placeholder="Tìm kiếm theo tên sản phẩm hoặc mã SKU..." value="{{ request('q') }}">
            </div>
            <div class="col-md-3">
                <select name="category_id" class="form-select form-select-sm">
                    <option value="">-- Tất cả danh mục --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="stock_status" class="form-select form-select-sm">
                    <option value="">-- Tình trạng kho --</option>
                    <option value="in" {{ request('stock_status') === 'in' ? 'selected' : '' }}>Còn hàng (>5)</option>
                    <option value="low" {{ request('stock_status') === 'low' ? 'selected' : '' }}>Sắp hết hàng (1-5)</option>
                    <option value="out" {{ request('stock_status') === 'out' ? 'selected' : '' }}>Hết hàng (0)</option>
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-filter me-1"></i>Lọc
                </button>
            </div>
            @if(request()->anyFilled(['q', 'category_id', 'stock_status']))
                <div class="col-auto">
                    <a href="{{ route('admin.products.index') }}" class="btn btn-link btn-sm text-danger text-decoration-none">
                        Xóa bộ lọc
                    </a>
                </div>
            @endif
        </form>
    </div>

    {{-- Bảng sản phẩm --}}
    <div class="card border-0 shadow-sm p-0 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light small text-muted text-uppercase">
                    <tr>
                        <th class="ps-3" style="width: 50px;">Ảnh</th>
                        <th style="min-width: 250px;">Sản phẩm</th>
                        <th>Danh mục</th>
                        <th>Giá bán</th>
                        <th class="text-center">Kho</th>
                        <th class="text-center">Nổi bật</th>
                        <th class="text-center">Trạng thái</th>
                        <th class="text-end pe-3">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $p)
                        <tr>
                            <td class="ps-3">
                                <div style="width: 44px; height: 44px;">
                                    @if($p->thumbnail)
                                        <img src="{{ asset('storage/' . $p->thumbnail) }}" alt="{{ $p->name }}" class="w-100 h-100 object-fit-contain rounded border">
                                    @else
                                        <div class="w-100 h-100 bg-light rounded border d-flex align-items-center justify-content-center text-muted">
                                            <i class="bi bi-box"></i>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <a href="{{ route('products.show', $p->slug) }}" target="_blank" class="fw-semibold text-dark text-decoration-none" title="Xem trên website">
                                    {{ $p->name }} <i class="bi bi-arrow-up-right-square small text-muted ms-1"></i>
                                </a>
                                <div class="text-muted" style="font-size: 0.75rem;">Mã SKU: <code>{{ $p->sku }}</code></div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">{{ $p->category->name ?? 'N/A' }}</span>
                            </td>
                            <td>
                                <strong class="text-accent" style="color:var(--ct-accent);">{{ number_format($p->price, 0, ',', '.') }}₫</strong>
                                @if($p->original_price && $p->original_price > $p->price)
                                    <div class="text-muted small text-decoration-line-through" style="font-size: 0.75rem;">
                                        {{ number_format($p->original_price, 0, ',', '.') }}₫
                                    </div>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($p->stock_quantity <= 0)
                                    <span class="badge bg-danger-subtle text-danger">Hết hàng (0)</span>
                                @elseif($p->stock_quantity <= 5)
                                    <span class="badge bg-warning-subtle text-warning">Còn ít ({{ $p->stock_quantity }})</span>
                                @else
                                    <span class="badge bg-success-subtle text-success">{{ $p->stock_quantity }}</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($p->is_featured)
                                    <span class="badge bg-warning text-dark"><i class="bi bi-star-fill"></i> Nổi bật</span>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($p->is_active)
                                    <span class="badge bg-success-subtle text-success">Đang bán</span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary">Tạm ẩn</span>
                                @endif
                            </td>
                            <td class="text-end pe-3">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.products.edit', $p) }}" class="btn btn-outline-secondary btn-sm py-0 px-2" title="Chỉnh sửa">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $p) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">
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
                            <td colspan="8" class="text-center py-5 text-muted">
                                Không tìm thấy sản phẩm nào.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
            <div class="p-3 border-top d-flex justify-content-center">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
