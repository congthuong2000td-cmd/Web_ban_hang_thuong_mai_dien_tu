@extends('layouts.admin')

@section('title', 'Quản lý mã giảm giá - CT mart Admin')

@section('content')
<div class="content-wrapper p-3 p-md-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1" style="color:var(--ct-primary)">
                Mã giảm giá (Coupon)
            </h1>
            <p class="text-muted small mb-0">Quản lý các chương trình ưu đãi, voucher khuyến mãi bán hàng.</p>
        </div>
        <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i>Tạo mã mới
        </a>
    </div>

    {{-- Bảng mã giảm giá --}}
    <div class="card border-0 shadow-sm p-0 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light small text-muted text-uppercase">
                    <tr>
                        <th class="ps-3">Mã Code</th>
                        <th>Tên chương trình</th>
                        <th>Mức giảm</th>
                        <th>Đơn tối thiểu</th>
                        <th>Giảm tối đa</th>
                        <th class="text-center">Đã dùng / Giới hạn</th>
                        <th class="text-center">Trạng thái</th>
                        <th class="text-end pe-3">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($coupons as $cp)
                        <tr>
                            <td class="ps-3">
                                <span class="badge bg-primary fs-6 font-monospace">{{ $cp->code }}</span>
                            </td>
                            <td>
                                <strong class="text-dark small">{{ $cp->name ?? '-' }}</strong>
                                @if($cp->expires_at)
                                    <div class="text-muted" style="font-size:0.72rem;">Hết hạn: {{ $cp->expires_at->format('d/m/Y') }}</div>
                                @endif
                            </td>
                            <td>
                                @if($cp->discount_type->value === 'percentage')
                                    <strong class="text-danger">{{ $cp->discount_value }}%</strong>
                                @else
                                    <strong class="text-success">{{ number_format($cp->discount_value, 0, ',', '.') }}₫</strong>
                                @endif
                            </td>
                            <td class="small text-muted">
                                {{ $cp->min_order_amount > 0 ? number_format($cp->min_order_amount, 0, ',', '.') . '₫' : 'Không yêu cầu' }}
                            </td>
                            <td class="small text-muted">
                                {{ $cp->max_discount ? number_format($cp->max_discount, 0, ',', '.') . '₫' : 'Không giới hạn' }}
                            </td>
                            <td class="text-center small">
                                <span class="fw-semibold text-dark">{{ $cp->used_count }}</span>
                                <span class="text-muted">/ {{ $cp->usage_limit ?? '∞' }}</span>
                            </td>
                            <td class="text-center">
                                @if($cp->is_active)
                                    <span class="badge bg-success-subtle text-success">Đang bật</span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary">Đã tắt</span>
                                @endif
                            </td>
                            <td class="text-end pe-3">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.coupons.edit', $cp) }}" class="btn btn-outline-secondary btn-sm py-0 px-2" title="Chỉnh sửa">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.coupons.destroy', $cp) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa mã giảm giá này?');">
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
                                Chưa có mã giảm giá nào.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($coupons->hasPages())
            <div class="p-3 border-top d-flex justify-content-center">
                {{ $coupons->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
