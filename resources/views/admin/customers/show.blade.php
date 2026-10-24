@extends('layouts.admin')

@section('title', 'Chi tiết khách hàng: ' . $customer->name . ' - CT mart Admin')

@section('content')
<div class="content-wrapper p-3 p-md-4">
    <div class="mb-4">
        <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-secondary btn-sm mb-2">
            <i class="bi bi-arrow-left me-1"></i>Quay lại danh sách khách hàng
        </a>
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
            <h1 class="h3 fw-bold mb-1" style="color:var(--ct-primary)">Hồ sơ khách hàng: {{ $customer->name }}</h1>
            <form action="{{ route('admin.customers.toggleActive', $customer) }}" method="POST">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn {{ $customer->is_active ? 'btn-danger' : 'btn-success' }} btn-sm" onclick="return confirm('Bạn có chắc chắn muốn thực hiện?');">
                    <i class="bi {{ $customer->is_active ? 'bi-lock-fill' : 'bi-unlock-fill' }} me-1"></i>
                    {{ $customer->is_active ? 'Khóa tài khoản này' : 'Mở khóa tài khoản' }}
                </button>
            </form>
        </div>
    </div>

    <div class="row g-4">
        {{-- Thông tin khách & Thống kê --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm p-4 mb-4">
                <div class="text-center mb-3">
                    <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center fw-bold fs-2 shadow-sm mb-2" style="width: 70px; height: 70px;">
                        {{ strtoupper(substr($customer->name, 0, 1)) }}
                    </div>
                    <h2 class="h5 fw-bold mb-0 text-dark">{{ $customer->name }}</h2>
                    <div class="text-muted small">{{ $customer->email }}</div>
                    <div class="mt-2">
                        @if($customer->is_active)
                            <span class="badge bg-success-subtle text-success">Đang hoạt động</span>
                        @else
                            <span class="badge bg-danger-subtle text-danger">Tài khoản bị khóa</span>
                        @endif
                    </div>
                </div>

                <div class="small text-muted d-flex flex-column gap-2 border-top pt-3">
                    <div class="d-flex justify-content-between">
                        <span>Số điện thoại:</span>
                        <strong class="text-dark">{{ $customer->phone ?? 'Chưa cập nhật' }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Ngày tham gia:</span>
                        <span class="text-dark">{{ $customer->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Tổng số đơn đặt:</span>
                        <strong class="text-dark">{{ $customer->orders->count() }} đơn</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Tổng chi tiêu thực tế:</span>
                        <strong class="text-accent" style="color:var(--ct-accent);">{{ number_format($totalSpent, 0, ',', '.') }}₫</strong>
                    </div>
                </div>
            </div>

            {{-- Sổ địa chỉ --}}
            <div class="card border-0 shadow-sm p-4">
                <h3 class="h6 fw-bold mb-3 border-bottom pb-2 text-dark">
                    <i class="bi bi-geo-alt-fill text-danger me-2"></i>Sổ địa chỉ lưu trữ ({{ $customer->addresses->count() }})
                </h3>
                <div class="d-flex flex-column gap-2">
                    @forelse($customer->addresses as $addr)
                        <div class="p-2 border rounded bg-light small">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <strong class="text-dark">{{ $addr->receiver_name }} ({{ $addr->receiver_phone }})</strong>
                                @if($addr->is_default)
                                    <span class="badge bg-primary text-white" style="font-size:0.65rem;">Mặc định</span>
                                @endif
                            </div>
                            <div class="text-muted">{{ $addr->street_address }}, {{ $addr->ward }}, {{ $addr->province }}</div>
                        </div>
                    @empty
                        <div class="text-muted small">Khách hàng chưa lưu địa chỉ nào.</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Lịch sử đơn hàng --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm p-4">
                <h2 class="h6 fw-bold mb-3 border-bottom pb-2 text-dark">
                    <i class="bi bi-bag-check-fill text-primary me-2"></i>Lịch sử mua hàng
                </h2>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light small text-muted text-uppercase">
                            <tr>
                                <th>Mã đơn</th>
                                <th>Ngày đặt</th>
                                <th>Tổng tiền</th>
                                <th>Thanh toán</th>
                                <th>Trạng thái</th>
                                <th class="text-end">Chi tiết</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customer->orders as $order)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.orders.show', $order) }}" class="fw-bold text-dark text-decoration-none">
                                            #{{ $order->order_code }}
                                        </a>
                                        <div class="text-muted" style="font-size:0.72rem;">{{ $order->items->count() }} món</div>
                                    </td>
                                    <td class="small text-muted">{{ $order->created_at->format('d/m/Y') }}</td>
                                    <td class="small fw-bold text-accent" style="color:var(--ct-accent);">
                                        {{ number_format($order->total_amount, 0, ',', '.') }}₫
                                    </td>
                                    <td>
                                        <span class="badge {{ $order->payment?->status?->badgeClass() ?? 'bg-secondary' }}" style="font-size:0.7rem;">
                                            {{ $order->payment?->status?->label() ?? 'Chờ' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $order->status->badgeClass() }}" style="font-size:0.7rem;">
                                            {{ $order->status->label() }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-outline-secondary btn-sm py-0 px-2" style="font-size:0.78rem;">
                                            Xem
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted small">Khách hàng chưa phát sinh đơn hàng nào.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
