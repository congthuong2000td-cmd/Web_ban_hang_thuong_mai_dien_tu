@extends('layouts.app')

@section('title', 'Thanh toán đơn hàng - CT mart')
@section('description', 'Hoàn tất đơn hàng của bạn tại CT mart nhanh chóng và an toàn.')

@section('content')
<div class="container-xl py-4">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb mb-0" style="font-size:0.875rem;">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('cart.index') }}">Giỏ hàng</a></li>
            <li class="breadcrumb-item active" aria-current="page">Thanh toán</li>
        </ol>
    </nav>

    <h1 class="h3 fw-bold mb-4" style="color:var(--ct-primary)">
        <i class="bi bi-shield-check me-2"></i>Thanh toán đơn hàng
    </h1>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <h6 class="fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-1"></i>Vui lòng kiểm tra lại thông tin:</h6>
            <ul class="mb-0 ps-3 small">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form">
        @csrf
        <div class="row g-4">
            {{-- THÔNG TIN GIAO HÀNG & PHƯƠNG THỨC THANH TOÁN --}}
            <div class="col-lg-7">
                {{-- 1. Địa chỉ giao hàng --}}
                <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: var(--ct-radius);">
                    <h2 class="h5 fw-bold pb-2 mb-3 border-bottom" style="color:var(--ct-primary)">
                        <i class="bi bi-geo-alt-fill text-danger me-2"></i>1. Thông tin giao hàng
                    </h2>

                    {{-- Chọn địa chỉ đã lưu --}}
                    @if($addresses->isNotEmpty())
                        <div class="mb-4">
                            <label class="form-label small fw-semibold text-muted text-uppercase mb-2">Chọn từ sổ địa chỉ đã lưu:</label>
                            <div class="d-flex flex-column gap-2">
                                @foreach($addresses as $addr)
                                    <div class="form-check p-3 border rounded-3 address-selector-item {{ ($defaultAddress && $defaultAddress->id === $addr->id) ? 'border-primary bg-light' : '' }}" style="cursor: pointer;">
                                        <input class="form-check-input address-radio" type="radio" name="saved_address_id"
                                               id="addr-{{ $addr->id }}"
                                               value="{{ $addr->id }}"
                                               data-name="{{ $addr->recipient_name }}"
                                               data-phone="{{ $addr->recipient_phone }}"
                                               data-province="{{ $addr->province }}"
                                               data-district="{{ $addr->district }}"
                                               data-detail="{{ $addr->address_detail }}"
                                               {{ ($defaultAddress && $defaultAddress->id === $addr->id) ? 'checked' : '' }}>
                                        <label class="form-check-label w-100 ps-2" for="addr-{{ $addr->id }}" style="cursor: pointer;">
                                            <div class="d-flex justify-content-between">
                                                <strong class="text-dark">{{ $addr->recipient_name }}</strong>
                                                <span class="text-muted small">{{ $addr->recipient_phone }}</span>
                                            </div>
                                            <div class="small text-muted mt-1">
                                                {{ $addr->address_detail }}, {{ $addr->district }}, {{ $addr->province }}
                                            </div>
                                            @if($addr->is_default)
                                                <span class="badge bg-primary-subtle text-primary mt-1" style="font-size: 0.65rem;">Mặc định</span>
                                            @endif
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="text-muted small text-center mb-3">--- Hoặc nhập địa chỉ bên dưới ---</div>
                    @endif

                    {{-- Chi tiết địa chỉ --}}
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="recipient_name" class="form-label small fw-semibold text-dark">Họ và tên người nhận <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('recipient_name') is-invalid @enderror"
                                   id="recipient_name" name="recipient_name"
                                   value="{{ old('recipient_name', $defaultAddress?->recipient_name ?? Auth::user()->name) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label for="recipient_phone" class="form-label small fw-semibold text-dark">Số điện thoại <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control @error('recipient_phone') is-invalid @enderror"
                                   id="recipient_phone" name="recipient_phone"
                                   value="{{ old('recipient_phone', $defaultAddress?->recipient_phone ?? Auth::user()->phone) }}"
                                   placeholder="0912345678" required>
                        </div>

                        <div class="col-md-6">
                            <label for="province" class="form-label small fw-semibold text-dark">Tỉnh / Thành phố <span class="text-danger">*</span></label>
                            <select class="form-select @error('province') is-invalid @enderror" id="province" name="province" required>
                                <option value="">-- Chọn Tỉnh / Thành phố --</option>
                                @foreach($shippingRates as $rate)
                                    <option value="{{ $rate->province_name }}"
                                            data-fee="{{ $rate->fee }}"
                                            data-days="{{ $rate->estimated_days ?? 3 }}"
                                            {{ old('province', $defaultAddress?->province) === $rate->province_name ? 'selected' : '' }}>
                                        {{ $rate->province_name }} ({{ number_format($rate->fee, 0, ',', '.') }}₫)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="district" class="form-label small fw-semibold text-dark">Quận / Huyện <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('district') is-invalid @enderror"
                                   id="district" name="district"
                                   value="{{ old('district', $defaultAddress?->district) }}"
                                   placeholder="Ví dụ: Quận 1, Quận Cầu Giấy..." required>
                        </div>

                        <div class="col-12">
                            <label for="address_detail" class="form-label small fw-semibold text-dark">Địa chỉ chi tiết (Số nhà, ngõ, tên đường) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('address_detail') is-invalid @enderror"
                                   id="address_detail" name="address_detail"
                                   value="{{ old('address_detail', $defaultAddress?->address_detail) }}"
                                   placeholder="Ví dụ: 123 Đường Nguyễn Huệ, Phường Bến Nghé" required>
                        </div>

                        <div class="col-12">
                            <label for="note" class="form-label small fw-semibold text-dark">Ghi chú đơn hàng (Tùy chọn)</label>
                            <textarea class="form-control" id="note" name="note" rows="2" placeholder="Ví dụ: Giao hàng vào giờ hành chính, gọi trước khi giao...">{{ old('note') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- 2. Phương thức thanh toán --}}
                <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: var(--ct-radius);">
                    <h2 class="h5 fw-bold pb-2 mb-3 border-bottom" style="color:var(--ct-primary)">
                        <i class="bi bi-wallet2 text-success me-2"></i>2. Phương thức thanh toán
                    </h2>

                    <div class="d-flex flex-column gap-3">
                        {{-- COD --}}
                        <div class="form-check p-3 border rounded-3 payment-method-option active" style="cursor:pointer;">
                            <input class="form-check-input" type="radio" name="payment_method" id="payment_cod" value="cod" {{ old('payment_method', 'cod') === 'cod' ? 'checked' : '' }}>
                            <label class="form-check-label w-100 ps-2" for="payment_cod" style="cursor:pointer;">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <strong class="text-dark"><i class="bi bi-cash-stack text-success me-2"></i>Thanh toán khi nhận hàng (COD)</strong>
                                        <p class="small text-muted mb-0 mt-1">Quý khách thanh toán bằng tiền mặt cho nhân viên giao hàng khi nhận được kiện hàng.</p>
                                    </div>
                                    <span class="badge bg-light text-dark border">Tiện lợi</span>
                                </div>
                            </label>
                        </div>

                        {{-- VNPay --}}
                        <div class="form-check p-3 border rounded-3 payment-method-option" style="cursor:pointer;">
                            <input class="form-check-input" type="radio" name="payment_method" id="payment_vnpay" value="vnpay" {{ old('payment_method') === 'vnpay' ? 'checked' : '' }}>
                            <label class="form-check-label w-100 ps-2" for="payment_vnpay" style="cursor:pointer;">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <strong class="text-dark">
                                            <i class="bi bi-qr-code text-primary me-2"></i>Thanh toán trực tuyến qua VNPay
                                        </strong>
                                        <p class="small text-muted mb-0 mt-1">Hỗ trợ quét mã VNPAY-QR, thẻ ATM nội địa (40+ ngân hàng) và thẻ quốc tế Visa / Mastercard.</p>
                                    </div>
                                    <span class="badge bg-primary text-white">Khuyên dùng</span>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ĐƠN HÀNG CỦA BẠN (Sidebar) --}}
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm p-4 position-sticky" style="top: 90px; border-radius: var(--ct-radius);">
                    <h2 class="h5 fw-bold pb-2 mb-3 border-bottom" style="color:var(--ct-primary)">
                        Đơn hàng của bạn ({{ count($items) }})
                    </h2>

                    {{-- Danh sách rút gọn --}}
                    <div class="mb-3 overflow-auto" style="max-height: 260px;">
                        @foreach($items as $item)
                            <div class="d-flex align-items-center gap-3 py-2 border-bottom">
                                <div class="position-relative flex-shrink-0" style="width: 50px; height: 50px;">
                                    @if($item['product']->thumbnail)
                                        <img src="{{ asset('storage/' . $item['product']->thumbnail) }}" alt="{{ $item['product']->name }}" class="w-100 h-100 object-fit-contain rounded border">
                                    @else
                                        <div class="w-100 h-100 bg-light rounded border d-flex align-items-center justify-content-center text-muted">
                                            <i class="bi bi-box"></i>
                                        </div>
                                    @endif
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-secondary" style="font-size:0.65rem;">
                                        {{ $item['qty'] }}
                                    </span>
                                </div>
                                <div class="flex-grow-1 min-w-0">
                                    <div class="small fw-semibold text-truncate text-dark">{{ $item['product']->name }}</div>
                                    <div class="small text-muted">{{ number_format($item['product']->price, 0, ',', '.') }}₫ x {{ $item['qty'] }}</div>
                                </div>
                                <div class="small fw-bold text-end text-dark">
                                    {{ number_format($item['lineTotal'], 0, ',', '.') }}₫
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Mã giảm giá --}}
                    <div class="mb-4">
                        <label class="form-label small fw-semibold text-dark mb-1">Mã giảm giá (Coupon):</label>
                        <div class="input-group">
                            <input type="text" class="form-control form-control-sm text-uppercase"
                                   id="coupon_code" name="coupon_code"
                                   placeholder="Nhập mã (VD: FREESHIP, WELCOME10)"
                                   value="{{ old('coupon_code') }}">
                            <button class="btn btn-outline-primary btn-sm" type="button" id="btn-apply-coupon">
                                Áp dụng
                            </button>
                        </div>
                        <div id="coupon-message" class="small mt-1 d-none"></div>
                    </div>

                    {{-- Chi tiết tiền --}}
                    <div class="d-flex flex-column gap-2 mb-3">
                        <div class="d-flex justify-content-between text-muted small">
                            <span>Tạm tính hàng hóa:</span>
                            <span class="text-dark fw-semibold" id="summary-subtotal" data-subtotal="{{ $subtotal }}">
                                {{ number_format($subtotal, 0, ',', '.') }}₫
                            </span>
                        </div>
                        <div class="d-flex justify-content-between text-muted small">
                            <span>Phí vận chuyển:</span>
                            <span class="text-dark fw-semibold" id="summary-shipping">
                                30.000₫
                            </span>
                        </div>
                        <div class="d-flex justify-content-between text-muted small d-none" id="summary-discount-row">
                            <span class="text-success">Giảm giá:</span>
                            <span class="text-success fw-semibold" id="summary-discount">-0₫</span>
                        </div>
                    </div>

                    <hr class="my-2">

                    <div class="d-flex justify-content-between align-items-baseline mb-4">
                        <span class="fw-bold fs-5 text-dark">Tổng thanh toán:</span>
                        <span class="fw-bold fs-3 text-accent" id="summary-total" style="color:var(--ct-accent);">
                            {{ number_format($subtotal + 30000, 0, ',', '.') }}₫
                        </span>
                    </div>

                    <button type="submit" class="btn btn-accent btn-lg w-100 fw-bold py-3 shadow-sm" id="btn-submit-order">
                        <i class="bi bi-check2-circle me-2"></i>Xác nhận đặt hàng
                    </button>

                    <div class="text-center mt-3">
                        <a href="{{ route('cart.index') }}" class="small text-muted text-decoration-none">
                            <i class="bi bi-arrow-left me-1"></i>Quay lại giỏ hàng
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('head')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const provinceSelect = document.getElementById('province');
    const shippingEl = document.getElementById('summary-shipping');
    const subtotalEl = document.getElementById('summary-subtotal');
    const totalEl = document.getElementById('summary-total');
    const subtotal = parseInt(subtotalEl.dataset.subtotal) || 0;

    function recalculateTotal() {
        const selected = provinceSelect.options[provinceSelect.selectedIndex];
        const fee = selected && selected.dataset.fee ? parseInt(selected.dataset.fee) : 30000;
        shippingEl.textContent = new Intl.NumberFormat('vi-VN').format(fee) + '₫';
        
        const total = subtotal + fee;
        totalEl.textContent = new Intl.NumberFormat('vi-VN').format(total) + '₫';
    }

    provinceSelect?.addEventListener('change', recalculateTotal);
    recalculateTotal();

    // Auto-fill address when picking saved address radio
    document.querySelectorAll('.address-radio').forEach(radio => {
        radio.addEventListener('change', function() {
            document.getElementById('recipient_name').value = this.dataset.name || '';
            document.getElementById('recipient_phone').value = this.dataset.phone || '';
            document.getElementById('district').value = this.dataset.district || '';
            document.getElementById('address_detail').value = this.dataset.detail || '';
            
            const prov = this.dataset.province;
            if (prov && provinceSelect) {
                for (let i = 0; i < provinceSelect.options.length; i++) {
                    if (provinceSelect.options[i].value === prov) {
                        provinceSelect.selectedIndex = i;
                        break;
                    }
                }
                recalculateTotal();
            }

            document.querySelectorAll('.address-selector-item').forEach(el => el.classList.remove('border-primary', 'bg-light'));
            this.closest('.address-selector-item')?.classList.add('border-primary', 'bg-light');
        });
    });

    // Style payment method selection
    document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
        radio.addEventListener('change', function() {
            document.querySelectorAll('.payment-method-option').forEach(el => el.classList.remove('border-primary', 'bg-light'));
            this.closest('.payment-method-option')?.classList.add('border-primary', 'bg-light');
        });
    });
});
</script>
@endpush
@endsection
