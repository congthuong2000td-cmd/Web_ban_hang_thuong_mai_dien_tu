@extends('layouts.admin')

@section('title', 'Chi tiết đơn hàng #' . $order->order_code . ' - CT mart Admin')

@section('content')
<div class="content-wrapper p-3 p-md-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm mb-2">
                <i class="bi bi-arrow-left me-1"></i>Quay lại danh sách đơn hàng
            </a>
            <h1 class="h3 fw-bold mb-1" style="color:var(--ct-primary)">
                Đơn hàng #{{ $order->order_code }}
            </h1>
            <span class="text-muted small">Thời gian đặt: {{ $order->created_at->format('d/m/Y H:i:s') }}</span>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge fs-6 {{ $order->status->badgeClass() }}">
                {{ $order->status->label() }}
            </span>
            <span class="badge fs-6 {{ $order->payment?->status?->badgeClass() ?? 'bg-secondary' }}">
                {{ $order->payment?->status?->label() ?? 'Chưa thanh toán' }}
            </span>
        </div>
    </div>

    <div class="row g-4">
        {{-- Cột trái: Cập nhật trạng thái & Sản phẩm --}}
        <div class="col-lg-8">
            {{-- Form cập nhật trạng thái đơn --}}
            <div class="card border-0 shadow-sm p-4 mb-4">
                <h2 class="h6 fw-bold mb-3 border-bottom pb-2 text-dark">
                    <i class="bi bi-gear text-primary me-2"></i>Cập nhật tiến trình đơn hàng
                </h2>
                <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="row g-3 align-items-end">
                        <div class="col-md-5">
                            <label for="status" class="form-label small fw-semibold text-dark">Chuyển trạng thái sang:</label>
                            <select name="status" id="status" class="form-select form-select-sm">
                                <option value="pending" {{ $order->status->value === 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
                                <option value="shipping" {{ $order->status->value === 'shipping' ? 'selected' : '' }}>Đang giao hàng</option>
                                <option value="completed" {{ $order->status->value === 'completed' ? 'selected' : '' }}>Đã giao thành công (Hoàn thành)</option>
                                <option value="cancelled" {{ $order->status->value === 'cancelled' ? 'selected' : '' }}>Hủy đơn hàng</option>
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label for="note" class="form-label small fw-semibold text-dark">Ghi chú tiến trình (Tùy chọn):</label>
                            <input type="text" name="note" id="note" class="form-control form-control-sm" placeholder="Ví dụ: Đã đóng gói giao bưu cục...">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary btn-sm w-100">
                                <i class="bi bi-check-lg me-1"></i>Lưu
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Danh sách sản phẩm --}}
            <div class="card border-0 shadow-sm p-4 mb-4">
                <h2 class="h6 fw-bold mb-3 border-bottom pb-2 text-dark">
                    <i class="bi bi-box-seam text-warning me-2"></i>Kiện hàng ({{ $order->items->count() }} sản phẩm)
                </h2>

                <div class="table-responsive">
                    <table class="table table-borderless align-middle mb-0">
                        <thead class="table-light small text-muted text-uppercase">
                            <tr>
                                <th style="width: 50px;">Ảnh</th>
                                <th style="min-width: 200px;">Sản phẩm</th>
                                <th class="text-center">Số lượng</th>
                                <th class="text-end">Đơn giá</th>
                                <th class="text-end">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                                <tr class="border-bottom">
                                    <td>
                                        <div style="width: 44px; height: 44px;">
                                            @if($item->product && $item->product->thumbnail)
                                                <img src="{{ asset('storage/' . $item->product->thumbnail) }}" alt="{{ $item->product_name }}" class="w-100 h-100 object-fit-contain rounded border">
                                            @else
                                                <div class="w-100 h-100 bg-light rounded border d-flex align-items-center justify-content-center text-muted">
                                                    <i class="bi bi-box"></i>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-dark small">{{ $item->product_name }}</div>
                                        <div class="text-muted" style="font-size: 0.72rem;">Mã SKU: <code>{{ $item->product_sku }}</code></div>
                                    </td>
                                    <td class="text-center small">x{{ $item->quantity }}</td>
                                    <td class="text-end small">{{ number_format($item->unit_price, 0, ',', '.') }}₫</td>
                                    <td class="text-end fw-bold small text-accent" style="color:var(--ct-accent);">
                                        {{ number_format($item->unit_price * $item->quantity, 0, ',', '.') }}₫
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Chi tiết thanh toán --}}
                <div class="row justify-content-end mt-3 pt-3 border-top">
                    <div class="col-md-6">
                        <div class="d-flex flex-column gap-2 small text-muted">
                            <div class="d-flex justify-content-between">
                                <span>Tiền hàng (Tạm tính):</span>
                                <span class="text-dark fw-semibold">{{ number_format($order->subtotal, 0, ',', '.') }}₫</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Phí vận chuyển:</span>
                                <span class="text-dark fw-semibold">{{ number_format($order->shipping_fee, 0, ',', '.') }}₫</span>
                            </div>
                            @if($order->discount_amount > 0)
                                <div class="d-flex justify-content-between text-success">
                                    <span>Mã giảm giá (Coupon):</span>
                                    <span class="fw-semibold">-{{ number_format($order->discount_amount, 0, ',', '.') }}₫</span>
                                </div>
                            @endif
                            <hr class="my-1">
                            <div class="d-flex justify-content-between fs-6 fw-bold text-dark">
                                <span>Tổng giá trị đơn:</span>
                                <span class="text-accent" style="color:var(--ct-accent);">{{ number_format($order->total_amount, 0, ',', '.') }}₫</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Lịch sử tiến trình --}}
            <div class="card border-0 shadow-sm p-4">
                <h2 class="h6 fw-bold mb-3 border-bottom pb-2 text-dark">
                    <i class="bi bi-clock-history text-primary me-2"></i>Lịch sử trạng thái đơn hàng
                </h2>
                <div class="timeline ps-3" style="border-left: 2px solid var(--ct-border);">
                    @foreach($order->statusHistories as $history)
                        <div class="position-relative mb-3 ps-3">
                            <div class="position-absolute rounded-circle bg-primary"
                                 style="width: 10px; height: 10px; left: -18px; top: 6px;"></div>
                            <div class="d-flex justify-content-between">
                                <strong class="text-dark small">{{ $history->to_status?->label() ?? $history->to_status }}</strong>
                                <span class="text-muted small">{{ $history->created_at?->format('d/m/Y H:i:s') }}</span>
                            </div>
                            <div class="small text-muted mt-1">
                                Người thực hiện: <span class="text-dark">{{ $history->changer?->name ?? 'Hệ thống / Khách hàng' }}</span>
                            </div>
                            @if($history->note)
                                <div class="small text-dark mt-1 fst-italic bg-light p-2 rounded">
                                    "{{ $history->note }}"
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Cột phải: Khách hàng & Giao nhận & Thanh toán --}}
        <div class="col-lg-4">
            {{-- Khách hàng & Người nhận --}}
            <div class="card border-0 shadow-sm p-4 mb-4">
                <h2 class="h6 fw-bold mb-3 border-bottom pb-2 text-dark">
                    <i class="bi bi-person text-primary me-2"></i>Thông tin nhận hàng
                </h2>
                <div class="small text-muted d-flex flex-column gap-2">
                    <div>Tài khoản đặt: <a href="{{ route('admin.customers.show', $order->user) }}" class="fw-semibold text-primary text-decoration-none">{{ $order->user->name }}</a></div>
                    <div>Email: <span class="text-dark">{{ $order->user->email }}</span></div>
                    <hr class="my-1">
                    <div>Người nhận hàng: <strong class="text-dark">{{ $order->receiver_name }}</strong></div>
                    <div>Số điện thoại: <strong class="text-dark">{{ $order->receiver_phone }}</strong></div>
                    <div>Địa chỉ giao: <span class="text-dark">{{ $order->shipping_address }}</span></div>
                    @if($order->note)
                        <div class="p-2 bg-light rounded mt-1">Ghi chú: <span class="fst-italic text-dark">"{{ $order->note }}"</span></div>
                    @endif
                </div>
            </div>

            {{-- Thông tin thanh toán --}}
            <div class="card border-0 shadow-sm p-4 mb-4">
                <h2 class="h6 fw-bold mb-3 border-bottom pb-2 text-dark">
                    <i class="bi bi-credit-card text-success me-2"></i>Thông tin thanh toán
                </h2>
                <div class="small text-muted d-flex flex-column gap-2">
                    <div>Phương thức: <strong class="text-dark">{{ $order->payment?->method?->label() ?? 'COD' }}</strong></div>
                    <div>Trạng thái:
                        <span class="badge {{ $order->payment?->status?->badgeClass() ?? 'bg-secondary' }}">
                            {{ $order->payment?->status?->label() ?? 'Chưa thanh toán' }}
                        </span>
                    </div>
                    <div>Số tiền: <strong class="text-dark">{{ number_format($order->payment?->amount ?? $order->total_amount, 0, ',', '.') }}₫</strong></div>
                    @if($order->payment?->txn_ref)
                        <div>Mã tham chiếu GD: <code>{{ $order->payment->txn_ref }}</code></div>
                    @endif
                    @if($order->payment?->gateway_txn_no)
                        <div>Mã GD VNPay: <code>{{ $order->payment->gateway_txn_no }}</code></div>
                    @endif
                    @if($order->payment?->paid_at)
                        <div>Ngày thanh toán: <span class="text-dark">{{ $order->payment->paid_at->format('d/m/Y H:i:s') }}</span></div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
