@extends('layouts.app')

@section('title', 'CT mart - Mua sắm thông minh, tiết kiệm thật sự')
@section('description', 'CT mart - Cửa hàng trực tuyến với điện thoại, thời trang, đồ gia dụng, sách và mỹ phẩm chính hãng, giá tốt, giao nhanh toàn quốc.')

@section('content')
{{-- ══ HERO BANNER ══ --}}
<section class="ct-hero">
    <div class="container-xl">
        <div class="row align-items-center g-4">
            <div class="col-lg-6">
                <div class="ct-hero-badge">🛍️ Khuyến mãi hôm nay</div>
                <h1 class="ct-hero-title">
                    Mua sắm thông minh,<br>
                    <span class="ct-hero-accent">Tiết kiệm thật sự</span>
                </h1>
                <p class="ct-hero-subtitle">
                    Hàng nghìn sản phẩm chính hãng từ điện thoại, thời trang, gia dụng đến sách và mỹ phẩm. Giao nhanh toàn quốc, đổi trả dễ dàng.
                </p>
                <div class="d-flex gap-3 flex-wrap mt-4">
                    <a href="{{ route('products.index') }}" class="btn ct-btn-primary btn-lg">
                        <i class="bi bi-bag-fill me-2"></i>Mua sắm ngay
                    </a>
                    <a href="{{ route('products.index') }}?sort=popular" class="btn ct-btn-outline btn-lg">
                        <i class="bi bi-fire me-2"></i>Xem hot deal
                    </a>
                </div>
                <div class="ct-trust-badges mt-4 d-flex gap-4 flex-wrap">
                    <span><i class="bi bi-shield-check text-success me-1"></i>Hàng chính hãng</span>
                    <span><i class="bi bi-truck text-primary me-1"></i>Giao toàn quốc</span>
                    <span><i class="bi bi-arrow-repeat text-warning me-1"></i>Đổi trả 7 ngày</span>
                </div>
            </div>
            <div class="col-lg-6 d-none d-lg-block">
                <div class="ct-hero-img-wrapper position-relative">
                    <div class="ct-floating-card card border-0 shadow-lg p-3">
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle bg-success d-flex align-items-center justify-content-center" style="width:36px;height:36px;min-width:36px">
                                <i class="bi bi-check-lg text-white"></i>
                            </div>
                            <div>
                                <div class="fw-semibold small">Đơn hàng #1247</div>
                                <div class="text-success" style="font-size:0.75rem">Đã giao thành công ✓</div>
                            </div>
                        </div>
                    </div>
                    <div class="ct-hero-visual">
                        <div class="ct-hero-circle"></div>
                        <div class="ct-hero-icon-grid">
                            @foreach(['bi-phone', 'bi-bag-heart', 'bi-house-heart', 'bi-book', 'bi-star'] as $icon)
                                <div class="ct-hero-icon-item"><i class="bi {{ $icon }}"></i></div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ══ DANH MỤC NỔI BẬT ══ --}}
@if($categories->isNotEmpty())
<section class="ct-section pt-5">
    <div class="container-xl">
        <div class="ct-section-header">
            <h2 class="ct-section-title">Danh mục sản phẩm</h2>
            <a href="{{ route('products.index') }}" class="ct-section-link">
                Xem tất cả <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
        <div class="row g-3">
            @foreach($categories as $cat)
                @php
                    $catIcons = [
                        'dien-thoai-phu-kien' => 'bi-phone',
                        'thoi-trang'          => 'bi-bag-heart',
                        'do-gia-dung'         => 'bi-house-heart',
                        'sach-van-phong-pham' => 'bi-book',
                        'my-pham-suc-khoe'   => 'bi-heart-pulse',
                    ];
                    $catColors = [
                        'dien-thoai-phu-kien' => '#3B82F6',
                        'thoi-trang'          => '#EC4899',
                        'do-gia-dung'         => '#F59E0B',
                        'sach-van-phong-pham' => '#10B981',
                        'my-pham-suc-khoe'   => '#8B5CF6',
                    ];
                    $icon = $catIcons[$cat->slug] ?? 'bi-grid';
                    $color = $catColors[$cat->slug] ?? '#0F2C59';
                @endphp
                <div class="col-6 col-md-4 col-lg-2-custom">
                    <a href="{{ route('categories.show', $cat->slug) }}" class="ct-category-card">
                        <div class="ct-category-icon" style="background: {{ $color }}15; color: {{ $color }}">
                            <i class="bi {{ $icon }}"></i>
                        </div>
                        <div class="ct-category-name">{{ $cat->name }}</div>
                        <div class="ct-category-count">{{ $cat->products_count }} sản phẩm</div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ══ SẢN PHẨM NỔI BẬT ══ --}}
@if($featuredProducts->isNotEmpty())
<section class="ct-section py-5">
    <div class="container-xl">
        <div class="ct-section-header">
            <h2 class="ct-section-title">
                <i class="bi bi-star-fill me-2" style="color:#F59E0B"></i>Sản phẩm nổi bật
            </h2>
            <a href="{{ route('products.index') }}?is_featured=1" class="ct-section-link">
                Xem thêm <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
        <div class="row g-3">
            @foreach($featuredProducts as $product)
                @include('products.partials.card', ['product' => $product])
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ══ BANNER QUẢNG CÁO ══ --}}
<section class="ct-promo-banner my-2">
    <div class="container-xl">
        <div class="row g-3">
            <div class="col-md-6">
                <div class="ct-promo-card" style="background: linear-gradient(135deg, #0F2C59 0%, #1E40AF 100%)">
                    <div>
                        <span class="badge mb-2" style="background:rgba(255,107,53,0.2);color:#FF6B35">Ưu đãi đặc biệt</span>
                        <h3 class="text-white fw-bold">Miễn phí vận chuyển</h3>
                        <p class="text-white opacity-75 mb-3">Cho đơn hàng từ 300.000₫ với mã <strong style="color:#FF6B35">FREESHIP</strong></p>
                        <a href="{{ route('products.index') }}" class="btn btn-sm" style="background:#FF6B35;color:#fff;border-radius:8px">Mua ngay</a>
                    </div>
                    <i class="bi bi-truck ct-promo-icon"></i>
                </div>
            </div>
            <div class="col-md-6">
                <div class="ct-promo-card" style="background: linear-gradient(135deg, #92400E 0%, #D97706 100%)">
                    <div>
                        <span class="badge mb-2" style="background:rgba(255,255,255,0.2);color:#fff">Mã giảm giá</span>
                        <h3 class="text-white fw-bold">Giảm 10% đơn đầu</h3>
                        <p class="text-white opacity-75 mb-3">Dùng mã <strong style="color:#fff">WELCOME10</strong> cho lần mua đầu tiên</p>
                        @guest
                            <a href="{{ route('register') }}" class="btn btn-sm" style="background:#fff;color:#92400E;border-radius:8px">Đăng ký nhận mã</a>
                        @else
                            <a href="{{ route('products.index') }}" class="btn btn-sm" style="background:#fff;color:#92400E;border-radius:8px">Áp dụng ngay</a>
                        @endguest
                    </div>
                    <i class="bi bi-ticket-perforated ct-promo-icon"></i>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ══ SẢN PHẨM MỚI NHẤT ══ --}}
@if($newProducts->isNotEmpty())
<section class="ct-section py-5">
    <div class="container-xl">
        <div class="ct-section-header">
            <h2 class="ct-section-title">
                <i class="bi bi-lightning-fill me-2" style="color:#3B82F6"></i>Mới nhất
            </h2>
            <a href="{{ route('products.index') }}" class="ct-section-link">
                Xem tất cả <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
        <div class="row g-3">
            @foreach($newProducts->take(10) as $product)
                @include('products.partials.card', ['product' => $product])
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection
