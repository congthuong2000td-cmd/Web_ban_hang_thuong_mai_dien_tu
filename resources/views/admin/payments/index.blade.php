@extends('layouts.admin')

@section('title', 'Quản lý thanh toán - CT mart Admin')

@section('content')
<div class="content-wrapper p-3 p-md-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1" style="color:var(--ct-primary)">
                Giao dịch thanh toán
            </h1>
            <p class="text-muted small mb-0">Theo dõi dòng tiền và nhật ký thanh toán COD / VNPay.</p>
        </div>
    </div>

    {{-- Bộ lọc --}}
    <div class="card border-0 shadow-sm p-3 mb-4">
        <form action="{{ route('admin.payments.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-4">
                <input type="text" name="q" class="form-control form-control-sm" placeholder="Tìm theo mã GD, mã đơn hoặc mã VNPay..." value="{{ request('q') }}">
            </div>
            <div class="col-md-3">
                <select name="method" class="form-select form-select-sm">
                    <option value="">-- Phương thức thanh toán --</option>
                    <option value="cod" {{ request('method') === 'cod' ? 'selected' : '' }}>COD (Tiền mặt)</option>
                    <option value="vnpay" {{ request('method') === 'vnpay' ? 'selected' : '' }}>VNPay</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select form-select-sm">
                    <option value="">-- Trạng thái --</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Chờ thanh toán</option>
                    <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                    <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Thất bại / Hủy</option>
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-filter me-1"></i>Lọc
                </button>
            </div>
            @if(request()->anyFilled(['q', 'method', 'status']))
                <div class="col-auto">
                    <a href="{{ route('admin.payments.index') }}" class="btn btn-link btn-sm text-danger text-decoration-none">
                        Xóa bộ lọc
                    </a>
                </div>
            @endif
        </form>
    </div>

    {{-- Bảng thanh toán --}}
    <div class="card border-0 shadow-sm p-0 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light small text-muted text-uppercase">
                    <tr>
                        <th class="ps-3">Đơn hàng</th>
                        <th>Khách hàng</th>
                        <th>Số tiền</th>
                        <th>Phương thức</th>
                        <th class="text-center">Trạng thái</th>
                        <th>Mã GD tham chiếu</th>
                        <th>Thời gian</th>
                        <th class="text-end pe-3">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $pay)
                        <tr>
                            <td class="ps-3">
                                @if($pay->order)
                                    <a href="{{ route('admin.orders.show', $pay->order) }}" class="fw-bold text-dark text-decoration-none">
                                        #{{ $pay->order->order_code }}
                                    </a>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                <div class="small fw-semibold text-dark">{{ $pay->order->receiver_name ?? 'Khách hàng' }}</div>
                                <div class="text-muted" style="font-size:0.75rem;">{{ $pay->order->user->email ?? '' }}</div>
                            </td>
                            <td>
                                <strong class="text-accent" style="color:var(--ct-accent);">{{ number_format($pay->amount, 0, ',', '.') }}₫</strong>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">{{ $pay->method->label() }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge {{ $pay->status->badgeClass() }}">
                                    {{ $pay->status->label() }}
                                </span>
                            </td>
                            <td>
                                <div><code>{{ $pay->txn_ref ?? '-' }}</code></div>
                                @if($pay->gateway_txn_no)
                                    <div class="text-muted small" style="font-size:0.72rem;">VNPay: {{ $pay->gateway_txn_no }}</div>
                                @endif
                            </td>
                            <td class="small text-muted">
                                {{ $pay->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="text-end pe-3">
                                <a href="{{ route('admin.payments.show', $pay) }}" class="btn btn-outline-secondary btn-sm py-0 px-2" title="Xem chi tiết giao dịch">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                Không tìm thấy giao dịch thanh toán nào.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($payments->hasPages())
            <div class="p-3 border-top d-flex justify-content-center">
                {{ $payments->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
