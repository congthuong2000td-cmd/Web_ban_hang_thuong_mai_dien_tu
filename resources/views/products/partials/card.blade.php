{{-- resources/views/products/partials/card.blade.php --}}
<div class="col-6 col-md-4 col-lg-3">
    <article class="ct-product-card h-100">
        <a href="{{ route('products.show', $product->slug) }}" class="ct-product-img-link" tabindex="-1" aria-hidden="true">
            @if($product->thumbnail)
                <img src="{{ asset('storage/' . $product->thumbnail) }}"
                     alt="{{ $product->name }}"
                     class="ct-product-img"
                     loading="lazy">
            @else
                <div class="ct-product-img-placeholder">
                    <i class="bi bi-box-seam"></i>
                </div>
            @endif

            @if($product->original_price && $product->original_price > $product->price)
                @php $pct = round((($product->original_price - $product->price) / $product->original_price) * 100); @endphp
                <span class="ct-badge-discount">-{{ $pct }}%</span>
            @endif

            @if($product->is_featured)
                <span class="ct-badge-featured">Nổi bật</span>
            @endif
        </a>

        <div class="ct-product-body">
            <div class="ct-product-category">{{ $product->category->name ?? '' }}</div>
            <h3 class="ct-product-name">
                <a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a>
            </h3>

            <div class="ct-product-price-row">
                <span class="ct-product-price">{{ number_format($product->price, 0, ',', '.') }}₫</span>
                @if($product->original_price && $product->original_price > $product->price)
                    <span class="ct-product-original-price">{{ number_format($product->original_price, 0, ',', '.') }}₫</span>
                @endif
            </div>

            @if($product->stock_quantity > 0)
                <button type="button" class="btn ct-btn-add-cart btn-add-to-cart w-100"
                        data-product-id="{{ $product->id }}"
                        aria-label="Thêm {{ $product->name }} vào giỏ hàng">
                    <i class="bi bi-cart-plus me-1"></i>Thêm vào giỏ
                </button>
            @else
                <button type="button" class="btn ct-btn-out-stock w-100" disabled aria-disabled="true">
                    <i class="bi bi-x-circle me-1"></i>Hết hàng
                </button>
            @endif
        </div>
    </article>
</div>
