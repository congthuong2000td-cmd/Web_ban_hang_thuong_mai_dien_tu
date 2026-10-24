@extends('layouts.admin')

@section('title', 'Quản lý đơn hàng - CT mart Admin')

@section('content')
<div class="content-wrapper p-3 p-md-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1" style="color:var(--ct-primary)">
                Quản lý đơn hàng
            </h1>
            <p class="text-muted small mb-0">Theo dõi, xác nhận và xử lý tiến trình giao hàng toàn hệ thống.</p>
        </div>
    </div>

    {{-- Bộ lọc & Tìm kiếm --}}
    <div class="card border-0 shadow-sm p-3 mb-4">
        <form action="{{ route('admin.orders.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-4">
                <input type="text" name="q" class="form-control form-control-sm" placeholder="Tìm theo mã đơn, tên hoặc SĐT khách..." value="{{ request('q') }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select form-select-sm">
                    <option value="">-- Trạng thái đơn hàng --</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
                    <option value="shipping" {{ request('status') === 'shipping' ? 'selected' : '' }}>Đang giao</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="payment_status" class="form-select form-select-sm">
                    <option value="">-- Trạng thái thanh toán --</option>
                    <option value="pending" {{ request('payment_status') === 'pending' ? 'selected' : '' }}>Chưa thanh toán / Chờ</option>
                    <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                    <option value="failed" {{ request('payment_status') === 'failed' ? 'selected' : '' }}>Thanh toán thất bại</option>
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-filter me-1"></i>Lọc
                </button>
            </div>
            @if(request()->anyFilled(['q', 'status', 'payment_status']))
                <div class="col-auto">
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-link btn-sm text-danger text-decoration-none">
                        Xóa bộ lọc
                    </a>
                </div>
            @endif
        </form>
    </div>

    {{-- Bảng đơn hàng --}}
    <div class="card border-0 shadow-sm p-0 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light small text-muted text-uppercase">
                    <tr>
                        <th class="ps-3">Mã đơn</th>
                        <th>Khách hàng</th>
                        <th>Ngày đặt</th>
                        <th>Tổng tiền</th>
                        <th>Phương thức</th>
                        <th class="text-center">Thanh toán</th>
                        <th class="text-center">Trạng thái</th>
                        <th class="text-end pe-3">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td class="ps-3">
                                <a href="{{ route('admin.orders.show', $order) }}" class="fw-bold text-dark text-decoration-none">
                                    #{{ $order->order_code }}
                                </a>
                                <div class="text-muted" style="font-size:0.75rem;">{{ $order->items->count() }} sản phẩm</div>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">{{ $order->receiver_name }}</div>
                                <div class="text-muted small">{{ $order->receiver_phone }}</div>
                            </td>
                            <td class="small text-muted">
                                {{ $order->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td>
                                <strong class="text-accent" style="color:var(--ct-accent);">{{ number_format($order->total_amount, 0, ',', '.') }}₫</strong>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">{{ $order->payment?->method?->label() ?? 'COD' }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge {{ $order->payment?->status?->badgeClass() ?? 'bg-secondary' }}">
                                    {{ $order->payment?->status?->label() ?? 'Chờ' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge {{ $order->status->badgeClass() }}">
                                    {{ $order->status->label() }}
                                </span>
                            </td>
                            <td class="text-end pe-3">
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-outline-primary btn-sm py-0 px-2" title="Xem chi tiết & xử lý">
                                    <i class="bi bi-eye me-1"></i>Chi tiết
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                Không tìm thấy đơn hàng nào.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
            <div class="p-3 border-top d-flex justify-content-center">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
