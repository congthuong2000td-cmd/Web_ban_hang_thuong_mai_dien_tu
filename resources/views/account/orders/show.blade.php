@extends('layouts.app')

@section('title', 'Chi tiết đơn hàng #' . $order->order_code . ' - CT mart')

@section('content')
<div class="container-xl py-4">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb mb-0" style="font-size:0.875rem;">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('account.orders.index') }}">Đơn hàng</a></li>
            <li class="breadcrumb-item active" aria-current="page">#{{ $order->order_code }}</li>
        </ol>
    </nav>

    <div class="row g-4">
        <aside class="col-lg-3">
            @include('account.partials.sidebar')
        </aside>

        <main class="col-lg-9">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
                <div>
                    <h1 class="h5 fw-bold mb-1" style="color:var(--ct-primary)">
                        Đơn hàng #{{ $order->order_code }}
                    </h1>
                    <span class="text-muted small">Đặt ngày: {{ $order->created_at->format('d/m/Y H:i') }}</span>
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

            {{-- Timeline lịch sử đơn hàng --}}
            @if($order->statusHistories->isNotEmpty())
                <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: var(--ct-radius);">
                    <h2 class="h6 fw-bold mb-3 pb-2 border-bottom" style="color:var(--ct-primary)">
                        <i class="bi bi-clock-history text-primary me-2"></i>Tiến trình xử lý
                    </h2>
                    <div class="timeline ps-3" style="border-left: 2px solid var(--ct-border);">
                        @foreach($order->statusHistories as $history)
                            <div class="position-relative mb-3 ps-3">
                                <div class="position-absolute rounded-circle bg-primary"
                                     style="width: 10px; height: 10px; left: -18px; top: 6px;"></div>
                                <div class="d-flex justify-content-between">
                                    <strong class="text-dark small">{{ $history->to_status?->label() ?? $history->to_status }}</strong>
                                    <span class="text-muted small">{{ $history->created_at?->format('d/m/Y H:i') }}</span>
                                </div>
                                @if($history->note)
                                    <div class="text-muted small mt-1">{{ $history->note }}</div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Thông tin giao hàng & thanh toán --}}
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm p-3 h-100" style="border-radius: var(--ct-radius);">
                        <h2 class="h6 fw-bold mb-2 text-dark border-bottom pb-2">
                            <i class="bi bi-geo-alt-fill text-danger me-2"></i>Địa chỉ nhận hàng
                        </h2>
                        <div class="small text-muted d-flex flex-column gap-1">
                            <div>Người nhận: <strong class="text-dark">{{ $order->receiver_name }}</strong></div>
                            <div>Điện thoại: <strong class="text-dark">{{ $order->receiver_phone }}</strong></div>
                            <div>Địa chỉ: <span class="text-dark">{{ $order->shipping_address }}</span></div>
                            @if($order->note)
                                <div class="mt-2 p-2 bg-light rounded fst-italic">Ghi chú: "{{ $order->note }}"</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card border-0 shadow-sm p-3 h-100" style="border-radius: var(--ct-radius);">
                        <h2 class="h6 fw-bold mb-2 text-dark border-bottom pb-2">
                            <i class="bi bi-credit-card-fill text-primary me-2"></i>Phương thức thanh toán
                        </h2>
                        <div class="small text-muted d-flex flex-column gap-1">
                            <div>Phương thức: <strong class="text-dark">{{ $order->payment?->method?->label() ?? 'COD' }}</strong></div>
                            <div>Trạng thái:
                                <span class="badge {{ $order->payment?->status?->badgeClass() ?? 'bg-secondary' }}">
                                    {{ $order->payment?->status?->label() ?? 'Chờ xử lý' }}
                                </span>
                            </div>
                            @if($order->payment?->paid_at)
                                <div>Thời gian thanh toán: <span class="text-dark">{{ $order->payment->paid_at->format('d/m/Y H:i') }}</span></div>
                            @endif
                            @if($order->payment?->gateway_txn_no)
                                <div>Mã GD VNPay: <span class="text-dark">{{ $order->payment->gateway_txn_no }}</span></div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Bảng sản phẩm --}}
            <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: var(--ct-radius);">
                <h2 class="h6 fw-bold mb-3 pb-2 border-bottom text-dark">
                    <i class="bi bi-box-seam-fill text-warning me-2"></i>Sản phẩm đã đặt ({{ $order->items->count() }})
                </h2>

                <div class="table-responsive">
                    <table class="table table-borderless align-middle mb-0">
                        <thead class="table-light small text-muted text-uppercase">
                            <tr>
                                <th scope="col" style="min-width: 260px;">Sản phẩm</th>
                                <th scope="col" class="text-center">Đơn giá</th>
                                <th scope="col" class="text-center">Số lượng</th>
                                <th scope="col" class="text-end">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                                <tr class="border-bottom">
                                    <td class="py-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <div style="width: 54px; height: 54px; flex-shrink: 0;">
                                                @if($item->product && $item->product->thumbnail)
                                                    <img src="{{ asset('storage/' . $item->product->thumbnail) }}" alt="{{ $item->product_name }}" class="w-100 h-100 object-fit-contain rounded border">
                                                @else
                                                    <div class="w-100 h-100 bg-light rounded border d-flex align-items-center justify-content-center text-muted">
                                                        <i class="bi bi-box"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="fw-semibold text-dark small">{{ $item->product_name }}</div>
                                                <div class="text-muted" style="font-size: 0.75rem;">Mã SKU: {{ $item->product_sku }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center fw-semibold text-dark small">
                                        {{ number_format($item->unit_price, 0, ',', '.') }}₫
                                    </td>
                                    <td class="text-center small">
                                        x{{ $item->quantity }}
                                    </td>
                                    <td class="text-end fw-bold text-accent small" style="color:var(--ct-accent);">
                                        {{ number_format($item->unit_price * $item->quantity, 0, ',', '.') }}₫
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Tổng kết tiền --}}
                <div class="row justify-content-end mt-3">
                    <div class="col-md-5">
                        <div class="d-flex flex-column gap-2 small text-muted">
                            <div class="d-flex justify-content-between">
                                <span>Tạm tính hàng hóa:</span>
                                <span class="text-dark fw-semibold">{{ number_format($order->subtotal, 0, ',', '.') }}₫</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Phí vận chuyển:</span>
                                <span class="text-dark fw-semibold">{{ number_format($order->shipping_fee, 0, ',', '.') }}₫</span>
                            </div>
                            @if($order->discount_amount > 0)
                                <div class="d-flex justify-content-between text-success">
                                    <span>Mã giảm giá:</span>
                                    <span class="fw-semibold">-{{ number_format($order->discount_amount, 0, ',', '.') }}₫</span>
                                </div>
                            @endif
                            <hr class="my-1">
                            <div class="d-flex justify-content-between fs-6 fw-bold text-dark">
                                <span>Tổng cộng thanh toán:</span>
                                <span class="text-accent" style="color:var(--ct-accent);">{{ number_format($order->total_amount, 0, ',', '.') }}₫</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('account.orders.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i>Quay lại danh sách đơn hàng
                </a>

                @if($order->status === \App\Enums\OrderStatus::PENDING)
                    <form action="{{ route('account.orders.cancel', $order) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này không?');">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm">
                            <i class="bi bi-x-circle me-1"></i>Hủy đơn hàng này
                        </button>
                    </form>
                @endif
            </div>
        </main>
    </div>
</div>
@endsection
