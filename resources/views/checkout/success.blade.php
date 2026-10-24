@extends('layouts.app')

@section('title', 'Đặt hàng thành công - CT mart')

@section('content')
<div class="container-xl py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm p-4 p-md-5 text-center mb-4" style="border-radius: var(--ct-radius-lg);">
                <div class="mb-3">
                    <div class="rounded-circle bg-success text-white d-inline-flex align-items-center justify-content-center shadow" style="width: 80px; height: 80px; font-size: 2.5rem;">
                        <i class="bi bi-check-lg"></i>
                    </div>
                </div>

                <h1 class="h3 fw-bold mb-2" style="color:var(--ct-primary)">Cảm ơn bạn đã đặt hàng!</h1>
                <p class="text-muted mb-4">Đơn hàng của bạn đã được tiếp nhận và đang được xử lý.</p>

                <div class="p-3 bg-light rounded-3 d-inline-flex flex-wrap justify-content-center gap-4 text-start mb-4 mx-auto border" style="max-width: 600px; width: 100%;">
                    <div>
                        <div class="text-muted small">Mã đơn hàng:</div>
                        <div class="fw-bold text-dark fs-6">{{ $order->order_code }}</div>
                    </div>
                    <div>
                        <div class="text-muted small">Ngày đặt:</div>
                        <div class="fw-bold text-dark fs-6">{{ $order->created_at->format('d/m/Y H:i') }}</div>
                    </div>
                    <div>
                        <div class="text-muted small">Phương thức:</div>
                        <div class="fw-bold text-dark fs-6">
                            {{ $order->payment?->method?->label() ?? 'COD' }}
                        </div>
                    </div>
                    <div>
                        <div class="text-muted small">Thanh toán:</div>
                        <div>
                            <span class="badge {{ $order->payment?->status?->badgeClass() ?? 'bg-secondary' }}">
                                {{ $order->payment?->status?->label() ?? 'Chờ xử lý' }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Chi tiết giao nhận --}}
                <div class="card border p-3 text-start mb-4 bg-white" style="border-radius:var(--ct-radius);">
                    <h2 class="h6 fw-bold mb-3 border-bottom pb-2 text-dark">
                        <i class="bi bi-truck text-primary me-2"></i>Thông tin nhận hàng
                    </h2>
                    <div class="row g-2 small text-muted">
                        <div class="col-sm-4">Người nhận:</div>
                        <div class="col-sm-8 fw-semibold text-dark">{{ $order->receiver_name }}</div>

                        <div class="col-sm-4">Số điện thoại:</div>
                        <div class="col-sm-8 fw-semibold text-dark">{{ $order->receiver_phone }}</div>

                        <div class="col-sm-4">Địa chỉ giao hàng:</div>
                        <div class="col-sm-8 fw-semibold text-dark">{{ $order->shipping_address }}</div>

                        @if($order->note)
                            <div class="col-sm-4">Ghi chú:</div>
                            <div class="col-sm-8 fst-italic">{{ $order->note }}</div>
                        @endif
                    </div>
                </div>

                {{-- Danh sách sản phẩm --}}
                <div class="card border p-3 text-start mb-4 bg-white" style="border-radius:var(--ct-radius);">
                    <h2 class="h6 fw-bold mb-3 border-bottom pb-2 text-dark">
                        <i class="bi bi-box-seam text-warning me-2"></i>Chi tiết kiện hàng ({{ $order->items->count() }} sản phẩm)
                    </h2>
                    <div class="table-responsive">
                        <table class="table table-borderless table-sm align-middle mb-0">
                            <tbody>
                                @foreach($order->items as $item)
                                    <tr class="border-bottom">
                                        <td style="width: 50px;">
                                            @if($item->product && $item->product->thumbnail)
                                                <img src="{{ asset('storage/' . $item->product->thumbnail) }}" alt="{{ $item->product_name }}" class="rounded border" style="width: 44px; height: 44px; object-fit: contain;">
                                            @else
                                                <div class="rounded border bg-light d-flex align-items-center justify-content-center text-muted" style="width: 44px; height: 44px;">
                                                    <i class="bi bi-box"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="fw-semibold text-dark small">{{ $item->product_name }}</div>
                                            <div class="text-muted" style="font-size: 0.75rem;">Mã: {{ $item->product_sku }}</div>
                                        </td>
                                        <td class="text-center small text-muted">
                                            x{{ $item->quantity }}
                                        </td>
                                        <td class="text-end fw-semibold text-dark small">
                                            {{ number_format($item->unit_price * $item->quantity, 0, ',', '.') }}₫
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="pt-3 border-top mt-2 small">
                        <div class="d-flex justify-content-between text-muted mb-1">
                            <span>Tạm tính:</span>
                            <span class="text-dark">{{ number_format($order->subtotal, 0, ',', '.') }}₫</span>
                        </div>
                        <div class="d-flex justify-content-between text-muted mb-1">
                            <span>Phí vận chuyển:</span>
                            <span class="text-dark">{{ number_format($order->shipping_fee, 0, ',', '.') }}₫</span>
                        </div>
                        @if($order->discount_amount > 0)
                            <div class="d-flex justify-content-between text-success mb-1">
                                <span>Giảm giá:</span>
                                <span>-{{ number_format($order->discount_amount, 0, ',', '.') }}₫</span>
                            </div>
                        @endif
                        <div class="d-flex justify-content-between fw-bold fs-6 pt-2 border-top text-dark">
                            <span>Tổng cộng:</span>
                            <span class="text-accent" style="color:var(--ct-accent);">{{ number_format($order->total_amount, 0, ',', '.') }}₫</span>
                        </div>
                    </div>
                </div>

                {{-- Action buttons --}}
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    <a href="{{ route('account.orders.show', $order) }}" class="btn btn-outline-primary px-4">
                        <i class="bi bi-receipt me-1"></i>Xem chi tiết đơn hàng
                    </a>
                    <a href="{{ route('home') }}" class="btn btn-primary px-4">
                        <i class="bi bi-house-door me-1"></i>Tiếp tục mua hàng
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
