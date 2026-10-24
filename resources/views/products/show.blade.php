@extends('layouts.app')

@section('title', $product->name . ' - CT mart')
@section('description', \Illuminate\Support\Str::limit(strip_tags($product->description), 150))

@section('content')
<div class="container-xl py-4">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb mb-0" style="font-size:0.875rem;">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Sản phẩm</a></li>
            @if($product->category)
                <li class="breadcrumb-item">
                    <a href="{{ route('categories.show', $product->category->slug) }}">{{ $product->category->name }}</a>
                </li>
            @endif
            <li class="breadcrumb-item active text-truncate" style="max-width:300px;" aria-current="page">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row g-4 mb-5">
        {{-- HÌNH ẢNH SẢN PHẨM --}}
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm p-3 position-sticky" style="top:90px;border-radius:var(--ct-radius);">
                <div class="ct-product-gallery-main position-relative mb-3 bg-white d-flex align-items-center justify-content-center" style="height:380px;border-radius:var(--ct-radius);overflow:hidden;">
                    @if($product->thumbnail)
                        <img id="main-product-image" src="{{ asset('storage/' . $product->thumbnail) }}" alt="{{ $product->name }}" class="img-fluid" style="max-height:100%;object-fit:contain;">
                    @else
                        <div class="text-muted text-center">
                            <i class="bi bi-box-seam display-1 text-black-50"></i>
                        </div>
                    @endif

                    @if($product->original_price && $product->original_price > $product->price)
                        @php $pct = round((($product->original_price - $product->price) / $product->original_price) * 100); @endphp
                        <span class="ct-badge-discount position-absolute top-0 start-0 m-3">-{{ $pct }}%</span>
                    @endif

                    @if($product->is_featured)
                        <span class="ct-badge-featured position-absolute top-0 end-0 m-3">Nổi bật</span>
                    @endif
                </div>

                {{-- Thumbnails --}}
                @if($product->images && $product->images->count() > 0)
                    <div class="d-flex gap-2 overflow-auto py-2">
                        @if($product->thumbnail)
                            <button type="button" class="btn p-1 border rounded ct-gallery-thumb active" onclick="changeImage('{{ asset('storage/' . $product->thumbnail) }}', this)" style="width:64px;height:64px;flex-shrink:0;">
                                <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="{{ $product->name }}" class="w-100 h-100 object-fit-contain">
                            </button>
                        @endif
                        @foreach($product->images as $img)
                            <button type="button" class="btn p-1 border rounded ct-gallery-thumb" onclick="changeImage('{{ asset('storage/' . $img->image_path) }}', this)" style="width:64px;height:64px;flex-shrink:0;">
                                <img src="{{ asset('storage/' . $img->image_path) }}" alt="{{ $product->name }}" class="w-100 h-100 object-fit-contain">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- THÔNG TIN CHI TIẾT SẢN PHẨM --}}
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm p-4 h-100" style="border-radius:var(--ct-radius);">
                <div class="mb-2">
                    <span class="badge bg-light text-primary border me-2">{{ $product->category->name ?? 'Sản phẩm' }}</span>
                    @if($product->sku)
                        <span class="text-muted small">Mã SKU: <strong class="text-dark">{{ $product->sku }}</strong></span>
                    @endif
                </div>

                <h1 class="h3 fw-bold mb-3" style="color:var(--ct-primary);line-height:1.3;">
                    {{ $product->name }}
                </h1>

                {{-- Đánh giá & tồn kho --}}
                <div class="d-flex flex-wrap align-items-center gap-3 pb-3 mb-3 border-bottom">
                    <div class="d-flex align-items-center gap-1 star-rating">
                        @php $rating = round($product->rating_avg ?? 5); @endphp
                        @for($i = 1; $i <= 5; $i++)
                            <i class="bi {{ $i <= $rating ? 'bi-star-fill text-warning' : 'bi-star text-muted' }}"></i>
                        @endfor
                        <span class="ms-1 fw-bold text-dark">{{ number_format($product->rating_avg ?? 5, 1) }}</span>
                    </div>
                    <span class="text-muted">|</span>
                    <span class="text-muted small"><strong>{{ $product->review_count ?? 0 }}</strong> đánh giá</span>
                    <span class="text-muted">|</span>
                    <div>
                        @if($product->stock_quantity > 0)
                            <span class="badge bg-success-subtle text-success"><i class="bi bi-check-circle me-1"></i>Còn hàng ({{ $product->stock_quantity }})</span>
                        @else
                            <span class="badge bg-danger-subtle text-danger"><i class="bi bi-x-circle me-1"></i>Tạm hết hàng</span>
                        @endif
                    </div>
                </div>

                {{-- Giá bán --}}
                <div class="p-3 mb-4 rounded-3" style="background-color: var(--ct-surface);">
                    <div class="d-flex align-items-baseline gap-3">
                        <span class="display-6 fw-bold text-accent" style="color:var(--ct-accent);">
                            {{ number_format($product->price, 0, ',', '.') }}₫
                        </span>
                        @if($product->original_price && $product->original_price > $product->price)
                            <span class="text-muted text-decoration-line-through fs-5">
                                {{ number_format($product->original_price, 0, ',', '.') }}₫
                            </span>
                            <span class="badge bg-danger">Tiết kiệm {{ number_format($product->original_price - $product->price, 0, ',', '.') }}₫</span>
                        @endif
                    </div>
                </div>

                {{-- Ưu đãi đi kèm --}}
                <div class="border rounded-3 p-3 mb-4" style="background:#fff7f2;border-color:#fed7aa !important;">
                    <h6 class="fw-bold mb-2 text-dark"><i class="bi bi-gift text-danger me-2"></i>Chính sách mua sắm tại CT mart:</h6>
                    <ul class="mb-0 ps-3 small text-muted d-flex flex-column gap-1">
                        <li>Cam kết chính hãng 100%, đền bù gấp đôi nếu phát hiện hàng giả</li>
                        <li>Giao hàng nhanh toàn quốc, kiểm tra hàng trước khi thanh toán</li>
                        <li>Đổi mới trong 7 ngày nếu có lỗi do nhà sản xuất</li>
                        <li>Hỗ trợ thanh toán tiện lợi qua VNPay (ATM / QR Code) hoặc COD</li>
                    </ul>
                </div>

                {{-- Form Mua hàng --}}
                @if($product->stock_quantity > 0)
                    <div class="mb-4">
                        <label class="form-label fw-bold text-dark small mb-2">Số lượng:</label>
                        <div class="d-flex align-items-center gap-3">
                            <div class="input-group" style="width: 140px;">
                                <button class="btn btn-outline-secondary" type="button" id="btn-qty-minus">-</button>
                                <input type="number" id="qty-input" class="form-control text-center fw-bold" value="1" min="1" max="{{ $product->stock_quantity }}">
                                <button class="btn btn-outline-secondary" type="button" id="btn-qty-plus">+</button>
                            </div>
                            <span class="text-muted small">Tối đa {{ $product->stock_quantity }} sản phẩm</span>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap gap-3">
                        <button type="button" class="btn btn-primary btn-lg flex-grow-1 btn-add-to-cart" data-product-id="{{ $product->id }}">
                            <i class="bi bi-cart-plus me-2"></i>Thêm vào giỏ hàng
                        </button>
                        <button type="button" class="btn btn-accent btn-lg px-4" id="btn-buy-now" data-product-id="{{ $product->id }}">
                            <i class="bi bi-lightning-charge me-1"></i>Mua ngay
                        </button>
                    </div>
                @else
                    <div class="alert alert-warning d-flex align-items-center gap-2 mb-4" role="alert">
                        <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                        <div>
                            Sản phẩm hiện đang tạm hết hàng. Quý khách vui lòng quay lại sau hoặc tham khảo các sản phẩm tương tự bên dưới.
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- MÔ TẢ CHI TIẾT & ĐÁNH GIÁ (Tabs) --}}
    <div class="card border-0 shadow-sm p-4 mb-5" style="border-radius:var(--ct-radius);">
        <ul class="nav nav-tabs border-bottom mb-4" id="productTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active fw-bold px-4 py-2" id="desc-tab" data-bs-toggle="tab" data-bs-target="#desc-pane" type="button" role="tab" aria-selected="true">
                    <i class="bi bi-file-text me-2"></i>Mô tả sản phẩm
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold px-4 py-2" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews-pane" type="button" role="tab" aria-selected="false">
                    <i class="bi bi-chat-left-dots me-2"></i>Đánh giá ({{ $product->reviews->count() }})
                </button>
            </li>
        </ul>

        <div class="tab-content" id="productTabContent">
            {{-- Tab Mô tả --}}
            <div class="tab-pane fade show active" id="desc-pane" role="tabpanel" tabindex="0">
                <div class="ct-product-content" style="line-height:1.8;color:#334155;">
                    @if($product->description)
                        {!! nl2br(e($product->description)) !!}
                    @else
                        <p class="text-muted">Đang cập nhật thông tin mô tả chi tiết cho sản phẩm này.</p>
                    @endif
                </div>
            </div>

            {{-- Tab Đánh giá --}}
            <div class="tab-pane fade" id="reviews-pane" role="tabpanel" tabindex="0">
                @if($product->reviews->isEmpty())
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-chat-square-heart display-4 mb-3 d-block text-black-50"></i>
                        <p class="mb-0">Chưa có đánh giá nào cho sản phẩm này. Hãy là người đầu tiên trải nghiệm và chia sẻ nhận xét!</p>
                    </div>
                @else
                    <div class="d-flex flex-column gap-3">
                        @foreach($product->reviews as $review)
                            <div class="p-3 border rounded-3 bg-light">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <strong class="text-dark">{{ $review->user->name ?? 'Khách hàng' }}</strong>
                                    <span class="text-muted small">{{ $review->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                <div class="star-rating mb-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="bi {{ $i <= $review->rating ? 'bi-star-fill text-warning' : 'bi-star text-muted' }}"></i>
                                    @endfor
                                </div>
                                <p class="mb-0 small text-dark">{{ $review->comment }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- SẢN PHẨM TƯƠNG TỰ --}}
    @if(isset($relatedProducts) && $relatedProducts->isNotEmpty())
        <div class="mb-5">
            <div class="ct-section-header mb-3">
                <h2 class="ct-section-title h4">
                    <i class="bi bi-grid-fill me-2" style="color:var(--ct-accent);"></i>Sản phẩm liên quan
                </h2>
                @if($product->category)
                    <a href="{{ route('categories.show', $product->category->slug) }}" class="ct-section-link">
                        Xem danh mục <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                @endif
            </div>
            <div class="row g-3">
                @foreach($relatedProducts as $relProduct)
                    @include('products.partials.card', ['product' => $relProduct])
                @endforeach
            </div>
        </div>
    @endif
</div>

@push('head')
<script>
function changeImage(src, btn) {
    document.getElementById('main-product-image').src = src;
    document.querySelectorAll('.ct-gallery-thumb').forEach(el => el.classList.remove('active', 'border-primary'));
    btn.classList.add('active', 'border-primary');
}

document.addEventListener('DOMContentLoaded', () => {
    // Buy Now button handler
    document.querySelector('#btn-buy-now')?.addEventListener('click', async function () {
        const productId = this.dataset.productId;
        const qty = parseInt(document.querySelector('#qty-input')?.value || 1);
        this.disabled = true;

        try {
            const res = await fetch('/gio-hang/them', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ product_id: productId, quantity: qty }),
            });

            const data = await res.json();
            if (res.ok && data.success) {
                window.location.href = "{{ route('checkout.index') }}";
            } else {
                alert(data.message || 'Không thể tiến hành mua ngay.');
                this.disabled = false;
            }
        } catch (e) {
            window.location.href = "{{ route('cart.index') }}";
        }
    });
});
</script>
@endpush
@endsection
