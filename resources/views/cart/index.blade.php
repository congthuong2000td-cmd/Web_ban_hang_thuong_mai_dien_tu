@extends('layouts.app')

@section('title', 'Giỏ hàng của bạn - CT mart')
@section('description', 'Xem và cập nhật giỏ hàng trực tuyến của bạn tại CT mart.')

@section('content')
<div class="container-xl py-4">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb mb-0" style="font-size:0.875rem;">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item active" aria-current="page">Giỏ hàng</li>
        </ol>
    </nav>

    <h1 class="h3 fw-bold mb-4" style="color:var(--ct-primary)">
        <i class="bi bi-cart3 me-2"></i>Giỏ hàng
        @if(!empty($items))
            <span class="fs-6 fw-normal text-muted">({{ count($items) }} sản phẩm)</span>
        @endif
    </h1>

    @if(empty($items))
        <div class="card border-0 shadow-sm p-5 text-center my-4" style="border-radius: var(--ct-radius-lg);">
            <div class="mb-3 text-muted" style="font-size: 4rem;">
                <i class="bi bi-cart-x"></i>
            </div>
            <h2 class="h4 fw-bold mb-2" style="color:var(--ct-primary)">Giỏ hàng của bạn đang trống</h2>
            <p class="text-muted mb-4">Hãy khám phá hàng ngàn sản phẩm tuyệt vời tại CT mart và thêm vào giỏ hàng ngay nhé!</p>
            <div>
                <a href="{{ route('products.index') }}" class="btn btn-primary px-4 py-2">
                    <i class="bi bi-bag-plus me-2"></i>Tiếp tục mua sắm
                </a>
            </div>
        </div>
    @else
        <div class="row g-4">
            {{-- Danh sách sản phẩm trong giỏ --}}
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-3" style="border-radius:var(--ct-radius);overflow:hidden;">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light text-muted small text-uppercase">
                                <tr>
                                    <th scope="col" class="ps-3 py-3" style="min-width:280px;">Sản phẩm</th>
                                    <th scope="col" class="py-3 text-center">Đơn giá</th>
                                    <th scope="col" class="py-3 text-center" style="width:140px;">Số lượng</th>
                                    <th scope="col" class="py-3 text-end">Thành tiền</th>
                                    <th scope="col" class="py-3 pe-3 text-center" style="width:50px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $item)
                                    @php
                                        $product = $item['product'];
                                        $qty = $item['quantity'];
                                        $lineTotal = $item['line_total'];
                                    @endphp
                                    <tr class="cart-item" data-product-id="{{ $product->id }}">
                                        {{-- Sản phẩm --}}
                                        <td class="ps-3 py-3">
                                            <div class="d-flex align-items-center gap-3">
                                                <a href="{{ route('products.show', $product->slug) }}" class="flex-shrink-0" style="width:64px;height:64px;">
                                                    @if($product->thumbnail)
                                                        <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="{{ $product->name }}" class="w-100 h-100 object-fit-contain rounded border">
                                                    @else
                                                        <div class="w-100 h-100 bg-light rounded border d-flex align-items-center justify-content-center text-muted">
                                                            <i class="bi bi-box"></i>
                                                        </div>
                                                    @endif
                                                </a>
                                                <div>
                                                    <span class="badge bg-light text-muted border mb-1" style="font-size:0.7rem;">{{ $product->category->name ?? '' }}</span>
                                                    <h6 class="mb-0 fw-semibold" style="font-size:0.92rem;">
                                                        <a href="{{ route('products.show', $product->slug) }}" class="text-dark text-decoration-none">
                                                            {{ $product->name }}
                                                        </a>
                                                    </h6>
                                                    @if($product->stock_quantity <= 5)
                                                        <span class="text-warning small" style="font-size:0.75rem;">
                                                            <i class="bi bi-exclamation-circle me-1"></i>Chỉ còn {{ $product->stock_quantity }} sản phẩm
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Đơn giá --}}
                                        <td class="text-center fw-semibold text-dark">
                                            {{ number_format($product->price, 0, ',', '.') }}₫
                                        </td>

                                        {{-- Số lượng --}}
                                        <td class="text-center">
                                            <div class="input-group input-group-sm mx-auto" style="max-width: 110px;">
                                                <button class="btn btn-outline-secondary btn-qty-change" type="button" data-action="decrease" data-product-id="{{ $product->id }}">-</button>
                                                <input type="number" class="form-control text-center fw-bold cart-qty-input"
                                                       data-product-id="{{ $product->id }}"
                                                       value="{{ $qty }}" min="1" max="{{ $product->stock_quantity }}">
                                                <button class="btn btn-outline-secondary btn-qty-change" type="button" data-action="increase" data-product-id="{{ $product->id }}">+</button>
                                            </div>
                                        </td>

                                        {{-- Thành tiền --}}
                                        <td class="text-end fw-bold text-accent cart-line-total" style="color:var(--ct-accent);">
                                            {{ number_format($lineTotal, 0, ',', '.') }}₫
                                        </td>

                                        {{-- Nút xóa --}}
                                        <td class="pe-3 text-center">
                                            <button type="button" class="btn btn-link text-danger p-1 btn-cart-remove" data-product-id="{{ $product->id }}" title="Xóa khỏi giỏ hàng">
                                                <i class="bi bi-trash fs-5"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Action bar --}}
                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i>Tiếp tục mua sắm
                    </a>
                    <form action="{{ route('cart.clear') }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa toàn bộ giỏ hàng?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm">
                            <i class="bi bi-trash3 me-1"></i>Xóa tất cả
                        </button>
                    </form>
                </div>
            </div>

            {{-- Tóm tắt đơn hàng (Sidebar) --}}
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm p-4 position-sticky" style="top: 90px; border-radius: var(--ct-radius);">
                    <h2 class="h5 fw-bold pb-3 mb-3 border-bottom" style="color:var(--ct-primary)">
                        Tóm tắt đơn hàng
                    </h2>

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Tạm tính:</span>
                        <strong class="text-dark" id="cart-subtotal">{{ number_format($subtotal, 0, ',', '.') }}₫</strong>
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Phí vận chuyển:</span>
                        <span class="text-muted fst-italic small">Tính khi thanh toán</span>
                    </div>

                    <hr class="my-3">

                    <div class="d-flex justify-content-between align-items-baseline mb-4">
                        <span class="fw-bold fs-5 text-dark">Tổng cộng:</span>
                        <span class="fw-bold fs-4 text-accent" id="cart-total" style="color:var(--ct-accent);">
                            {{ number_format($subtotal, 0, ',', '.') }}₫
                        </span>
                    </div>

                    <a href="{{ route('checkout.index') }}" class="btn btn-accent btn-lg w-100 mb-3 fw-bold">
                        <i class="bi bi-credit-card me-2"></i>Tiến hành thanh toán
                    </a>

                    <div class="p-3 bg-light rounded-3 small text-muted">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="bi bi-shield-lock-fill text-success fs-5"></i>
                            <span>Bảo mật thông tin thanh toán 100%</span>
                        </div>
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="bi bi-arrow-repeat text-primary fs-5"></i>
                            <span>Hỗ trợ đổi trả miễn phí trong 7 ngày</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-truck text-warning fs-5"></i>
                            <span>Freeship cho đơn hàng từ 300.000₫</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@push('head')
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Quantity + / - button triggers
    document.querySelectorAll('.btn-qty-change').forEach(btn => {
        btn.addEventListener('click', function () {
            const action = this.dataset.action;
            const productId = this.dataset.productId;
            const input = document.querySelector(`.cart-qty-input[data-product-id="${productId}"]`);
            if (!input) return;

            let val = parseInt(input.value) || 1;
            const max = parseInt(input.max) || 999;
            if (action === 'increase') {
                if (val < max) val++;
            } else if (action === 'decrease') {
                if (val > 1) val--;
            }
            input.value = val;
            input.dispatchEvent(new Event('change'));
        });
    });
});
</script>
@endpush
@endsection
