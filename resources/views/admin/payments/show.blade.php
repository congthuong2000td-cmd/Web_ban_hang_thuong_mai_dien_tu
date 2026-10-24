@extends('layouts.admin')

@section('title', 'Chi tiết giao dịch thanh toán - CT mart Admin')

@section('content')
<div class="content-wrapper p-3 p-md-4">
    <div class="mb-4">
        <a href="{{ route('admin.payments.index') }}" class="btn btn-outline-secondary btn-sm mb-2">
            <i class="bi bi-arrow-left me-1"></i>Quay lại danh sách giao dịch
        </a>
        <h1 class="h3 fw-bold mb-1" style="color:var(--ct-primary)">Chi tiết giao dịch thanh toán</h1>
        <span class="text-muted small">Mã tham chiếu: <code>{{ $payment->txn_ref ?? ('PAY-' . $payment->id) }}</code></span>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm p-4 h-100">
                <h2 class="h6 fw-bold mb-3 border-bottom pb-2 text-dark">
                    <i class="bi bi-credit-card-2-front text-primary me-2"></i>Thông số giao dịch
                </h2>
                <div class="small text-muted d-flex flex-column gap-3">
                    <div class="d-flex justify-content-between">
                        <span>Số tiền giao dịch:</span>
                        <strong class="text-accent fs-5" style="color:var(--ct-accent);">{{ number_format($payment->amount, 0, ',', '.') }}₫</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Phương thức thanh toán:</span>
                        <strong class="text-dark">{{ $payment->method->label() }}</strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Trạng thái giao dịch:</span>
                        <span class="badge fs-6 {{ $payment->status->badgeClass() }}">
                            {{ $payment->status->label() }}
                        </span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Mã tham chiếu (Txn Ref):</span>
                        <code>{{ $payment->txn_ref ?? '-' }}</code>
                    </div>
                    @if($payment->gateway_txn_no)
                        <div class="d-flex justify-content-between">
                            <span>Mã GD cổng thanh toán:</span>
                            <code>{{ $payment->gateway_txn_no }}</code>
                        </div>
                    @endif
                    @if($payment->bank_code)
                        <div class="d-flex justify-content-between">
                            <span>Ngân hàng thanh toán:</span>
                            <strong class="text-dark">{{ $payment->bank_code }}</strong>
                        </div>
                    @endif
                    @if($payment->response_code)
                        <div class="d-flex justify-content-between">
                            <span>Mã phản hồi cổng:</span>
                            <code>{{ $payment->response_code }}</code>
                        </div>
                    @endif
                    <div class="d-flex justify-content-between">
                        <span>Thời gian tạo giao dịch:</span>
                        <span class="text-dark">{{ $payment->created_at->format('d/m/Y H:i:s') }}</span>
                    </div>
                    @if($payment->paid_at)
                        <div class="d-flex justify-content-between">
                            <span>Thời gian thanh toán thành công:</span>
                            <span class="text-success fw-semibold">{{ $payment->paid_at->format('d/m/Y H:i:s') }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 shadow-sm p-4 h-100">
                <h2 class="h6 fw-bold mb-3 border-bottom pb-2 text-dark">
                    <i class="bi bi-receipt text-warning me-2"></i>Đơn hàng liên kết
                </h2>
                @if($payment->order)
                    <div class="small text-muted d-flex flex-column gap-2 mb-4">
                        <div class="d-flex justify-content-between">
                            <span>Mã đơn hàng:</span>
                            <a href="{{ route('admin.orders.show', $payment->order) }}" class="fw-bold text-primary text-decoration-none">
                                #{{ $payment->order->order_code }}
                            </a>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Khách hàng đặt:</span>
                            <strong class="text-dark">{{ $payment->order->receiver_name }}</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Số điện thoại:</span>
                            <span class="text-dark">{{ $payment->order->receiver_phone }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Trạng thái đơn hàng:</span>
                            <span class="badge {{ $payment->order->status->badgeClass() }}">
                                {{ $payment->order->status->label() }}
                            </span>
                        </div>
                    </div>

                    <a href="{{ route('admin.orders.show', $payment->order) }}" class="btn btn-outline-primary btn-sm w-100">
                        <i class="bi bi-box-arrow-up-right me-1"></i>Xem chi tiết đơn hàng
                    </a>
                @else
                    <div class="text-muted small">Không tìm thấy thông tin đơn hàng liên kết.</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
