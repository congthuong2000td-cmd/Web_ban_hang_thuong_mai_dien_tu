<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'CT mart - Mua sắm thông minh')</title>
    <meta name="description" content="@yield('description', 'CT mart - Cửa hàng trực tuyến đa dạng sản phẩm điện thoại, thời trang, đồ gia dụng, sách, mỹ phẩm với giá tốt nhất.')">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body>

{{-- Flash messages (dạng data attribute, xử lý bằng JS toast) --}}
@if(session('success'))
    <span data-flash-type="success" data-flash-message="{{ session('success') }}" class="d-none"></span>
@endif
@if(session('error'))
    <span data-flash-type="danger" data-flash-message="{{ session('error') }}" class="d-none"></span>
@endif
@if(session('warning'))
    <span data-flash-type="warning" data-flash-message="{{ session('warning') }}" class="d-none"></span>
@endif
@if(session('info'))
    <span data-flash-type="info" data-flash-message="{{ session('info') }}" class="d-none"></span>
@endif

{{-- HEADER --}}
<header>
    <nav class="ct-navbar navbar navbar-expand-lg">
        <div class="container-xl">
            {{-- Logo --}}
            <a class="navbar-brand d-flex align-items-center gap-2 fw-black" href="{{ route('home') }}">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 44 44" width="36" height="36" fill="none" aria-hidden="true">
                    <rect x="2" y="2" width="40" height="40" rx="10" fill="#0F2C59"/>
                    <path d="M14 18C14 14 17 12 22 12H30" stroke="#FF6B35" stroke-width="3.5" stroke-linecap="round"/>
                    <path d="M30 12L27 32H17L13 18H33" stroke="#FFFFFF" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <circle cx="19" cy="37" r="2.5" fill="#FF6B35"/>
                    <circle cx="27" cy="37" r="2.5" fill="#FF6B35"/>
                </svg>
                <span class="fs-5" style="color:#0F2C59;font-weight:900;letter-spacing:-0.03em;">CT<span style="color:#FF6B35;font-weight:500;">mart</span></span>
            </a>

            {{-- Thanh tìm kiếm --}}
            <div class="ct-nav-search mx-lg-4 flex-grow-1 d-none d-lg-block">
                <form action="{{ route('products.index') }}" method="GET" role="search">
                    <div class="input-group">
                        <input type="search" class="form-control" id="search-input-nav" name="q"
                               value="{{ request('q') }}"
                               placeholder="Tìm sản phẩm, thương hiệu..."
                               aria-label="Tìm kiếm sản phẩm">
                        <button class="btn" type="submit" aria-label="Tìm kiếm">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>
            </div>

            {{-- Icons bên phải --}}
            <div class="d-flex align-items-center gap-2 ms-auto ms-lg-0">
                {{-- Nút tìm kiếm mobile --}}
                <button class="btn btn-link d-lg-none p-2" id="btn-search-mobile" aria-label="Tìm kiếm" data-bs-toggle="collapse" data-bs-target="#mobile-search-bar">
                    <i class="bi bi-search fs-5" style="color:var(--ct-dark)"></i>
                </button>

                {{-- Tài khoản --}}
                @auth
                    <div class="dropdown">
                        <button class="btn btn-link p-2 d-flex align-items-center gap-1" type="button"
                                id="accountDropdown" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Tài khoản của tôi">
                            <i class="bi bi-person-circle fs-5" style="color:var(--ct-dark)"></i>
                            <span class="d-none d-md-inline text-dark fw-semibold" style="font-size:0.85rem;max-width:80px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ Auth::user()->name }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0" style="border-radius:var(--ct-radius);min-width:200px;" aria-labelledby="accountDropdown">
                            <li class="px-3 py-2 border-bottom">
                                <div class="fw-semibold" style="color:var(--ct-primary)">{{ Auth::user()->name }}</div>
                                <div class="text-muted" style="font-size:0.8rem">{{ Auth::user()->email }}</div>
                            </li>
                            <li><a class="dropdown-item py-2" href="{{ route('account.profile') }}"><i class="bi bi-person me-2"></i>Hồ sơ của tôi</a></li>
                            <li><a class="dropdown-item py-2" href="{{ route('account.orders.index') }}"><i class="bi bi-bag me-2"></i>Đơn hàng</a></li>
                            <li><a class="dropdown-item py-2" href="{{ route('account.addresses.index') }}"><i class="bi bi-geo-alt me-2"></i>Sổ địa chỉ</a></li>
                            @if(Auth::user()->isAdmin())
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item py-2 fw-semibold" href="{{ route('admin.dashboard') }}" style="color:var(--ct-primary)"><i class="bi bi-shield-lock me-2"></i>Quản trị viên</a></li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item py-2 text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i>Đăng xuất
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn btn-link p-2 d-flex align-items-center gap-1 text-dark" aria-label="Đăng nhập">
                        <i class="bi bi-person fs-5"></i>
                        <span class="d-none d-md-inline fw-semibold" style="font-size:0.85rem">Đăng nhập</span>
                    </a>
                @endauth

                {{-- Giỏ hàng --}}
                <a href="{{ route('cart.index') }}" class="btn btn-link p-2 position-relative" aria-label="Giỏ hàng">
                    <i class="bi bi-cart3 fs-5" style="color:var(--ct-dark)"></i>
                    @php $cartCount = count(session('cart', [])); @endphp
                    <span id="cart-badge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill"
                          style="background-color:var(--ct-accent);font-size:0.65rem;{{ $cartCount === 0 ? 'display:none' : '' }}">
                        {{ $cartCount }}
                    </span>
                </a>
            </div>
        </div>
    </nav>

    {{-- Thanh tìm kiếm mobile (collapse) --}}
    <div class="collapse bg-white border-bottom px-3 py-2" id="mobile-search-bar">
        <form action="{{ route('products.index') }}" method="GET" role="search">
            <div class="input-group">
                <input type="search" class="form-control" name="q" value="{{ request('q') }}"
                       placeholder="Tìm sản phẩm..." aria-label="Tìm kiếm sản phẩm">
                <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
            </div>
        </form>
    </div>

    {{-- Category Bar --}}
    <div class="ct-category-bar d-none d-md-block">
        <div class="container-xl d-flex align-items-center gap-1">
            <a href="{{ route('products.index') }}"
               class="ct-category-link {{ request()->routeIs('home') || (!request()->route('slug') && request()->routeIs('products.index') && !request('category')) ? 'active' : '' }}">
                <i class="bi bi-grid-3x3-gap"></i> Tất cả
            </a>
            @foreach(\App\Models\Category::where('is_active', true)->orderBy('name')->get() as $cat)
                <a href="{{ route('categories.show', $cat->slug) }}"
                   class="ct-category-link {{ request()->route('slug') === $cat->slug ? 'active' : '' }}">
                    {{ $cat->name }}
                </a>
            @endforeach
        </div>
    </div>
</header>

{{-- BREADCRUMB --}}
@hasSection('breadcrumb')
<div class="bg-white border-bottom py-2">
    <div class="container-xl">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                @yield('breadcrumb')
            </ol>
        </nav>
    </div>
</div>
@endif

{{-- MAIN CONTENT --}}
<main>
    @yield('content')
</main>

{{-- FOOTER --}}
<footer class="ct-footer">
    <div class="container-xl">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 44 44" width="32" height="32" fill="none">
                        <rect x="2" y="2" width="40" height="40" rx="10" fill="#1E40AF"/>
                        <path d="M14 18C14 14 17 12 22 12H30" stroke="#FF6B35" stroke-width="3.5" stroke-linecap="round"/>
                        <path d="M30 12L27 32H17L13 18H33" stroke="#FFFFFF" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <circle cx="19" cy="37" r="2.5" fill="#FF6B35"/>
                        <circle cx="27" cy="37" r="2.5" fill="#FF6B35"/>
                    </svg>
                    <span class="fs-5 fw-black" style="color:#ffffff;letter-spacing:-0.03em;">CT<span style="color:#FF6B35;font-weight:400;">mart</span></span>
                </div>
                <p class="text-sm mb-3" style="color:#94A3B8;font-size:0.9rem;line-height:1.7">
                    Cửa hàng trực tuyến đa ngành hàng với sản phẩm chất lượng, giá cả minh bạch và dịch vụ tận tâm.
                </p>
                <div class="d-flex gap-2">
                    <a href="#" class="btn btn-sm rounded-circle" style="width:36px;height:36px;background:rgba(255,255,255,0.1);color:#fff;display:flex;align-items:center;justify-content:center;" aria-label="Facebook">
                        <i class="bi bi-facebook"></i>
                    </a>
                    <a href="#" class="btn btn-sm rounded-circle" style="width:36px;height:36px;background:rgba(255,255,255,0.1);color:#fff;display:flex;align-items:center;justify-content:center;" aria-label="Zalo">
                        <i class="bi bi-chat-square-fill"></i>
                    </a>
                </div>
            </div>
            <div class="col-md-2 col-6">
                <h6 class="fw-700 mb-3" style="color:#ffffff;font-weight:700">Danh mục</h6>
                <ul class="list-unstyled" style="font-size:0.9rem">
                    @foreach(\App\Models\Category::where('is_active', true)->orderBy('name')->get() as $cat)
                        <li class="mb-1"><a href="{{ route('categories.show', $cat->slug) }}">{{ $cat->name }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div class="col-md-2 col-6">
                <h6 class="fw-700 mb-3" style="color:#ffffff;font-weight:700">Hỗ trợ</h6>
                <ul class="list-unstyled" style="font-size:0.9rem">
                    <li class="mb-1"><a href="#">Chính sách giao hàng</a></li>
                    <li class="mb-1"><a href="#">Chính sách thanh toán</a></li>
                    <li class="mb-1"><a href="#">Liên hệ</a></li>
                </ul>
            </div>
            <div class="col-md-4">
                <h6 class="fw-700 mb-3" style="color:#ffffff;font-weight:700">Liên hệ</h6>
                <ul class="list-unstyled" style="font-size:0.9rem;color:#94A3B8">
                    <li class="mb-2 d-flex gap-2"><i class="bi bi-geo-alt-fill" style="color:var(--ct-accent)"></i> <span>Địa chỉ cửa hàng CT mart</span></li>
                    <li class="mb-2 d-flex gap-2"><i class="bi bi-telephone-fill" style="color:var(--ct-accent)"></i> <span>Hotline: </span></li>
                    <li class="mb-2 d-flex gap-2"><i class="bi bi-envelope-fill" style="color:var(--ct-accent)"></i> <span>no-reply@ctmart.vn</span></li>
                    <li class="mb-2 d-flex gap-2"><i class="bi bi-clock-fill" style="color:var(--ct-accent)"></i> <span>8:00 – 22:00 hàng ngày</span></li>
                </ul>
            </div>
        </div>
        <div class="ct-footer-bottom d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
            <span>© {{ date('Y') }} CT mart. Bảo lưu mọi quyền.</span>
            <div class="d-flex align-items-center gap-3">
                <img src="https://sandbox.vnpayment.vn/paymentv2/Assets/Images/logoVNPAY.png" alt="VNPay" height="20" loading="lazy">
                <span style="font-size:0.75rem">Thanh toán an toàn với VNPay</span>
            </div>
        </div>
    </div>
</footer>

@stack('scripts')
</body>
</html>
