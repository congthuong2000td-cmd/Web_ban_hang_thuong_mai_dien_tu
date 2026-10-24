<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Quản trị') | CT mart Admin</title>
    @vite(['resources/css/admin.css', 'resources/js/admin.js'])
    @stack('head')
</head>
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">

<div class="app-wrapper">
    {{-- Sidebar --}}
    <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
        <div class="sidebar-brand">
            <a href="{{ route('admin.dashboard') }}" class="brand-link d-flex align-items-center gap-2 text-decoration-none px-3 py-3">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 44 44" width="32" height="32" fill="none">
                    <rect x="2" y="2" width="40" height="40" rx="10" fill="#1E40AF"/>
                    <path d="M14 18C14 14 17 12 22 12H30" stroke="#FF6B35" stroke-width="3.5" stroke-linecap="round"/>
                    <path d="M30 12L27 32H17L13 18H33" stroke="#FFFFFF" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <circle cx="19" cy="37" r="2.5" fill="#FF6B35"/>
                    <circle cx="27" cy="37" r="2.5" fill="#FF6B35"/>
                </svg>
                <span class="brand-text fw-bold fs-5" style="color:#fff;letter-spacing:-0.03em;">
                    CT<span style="color:#FF6B35;font-weight:400;">mart</span>
                    <small class="d-block" style="font-size:0.65rem;color:#94A3B8;font-weight:400;letter-spacing:0">Quản trị viên</small>
                </span>
            </a>
        </div>

        <div class="sidebar-wrapper">
            <nav class="mt-2">
                <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu">
                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-speedometer2"></i>
                            <p>Tổng quan</p>
                        </a>
                    </li>
                    <li class="nav-header text-uppercase" style="font-size:0.7rem;letter-spacing:.08em;color:#94A3B8">Danh mục & Sản phẩm</li>
                    <li class="nav-item">
                        <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-grid-3x3-gap"></i>
                            <p>Danh mục</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-box-seam"></i>
                            <p>Sản phẩm</p>
                        </a>
                    </li>
                    <li class="nav-header text-uppercase" style="font-size:0.7rem;letter-spacing:.08em;color:#94A3B8">Bán hàng</li>
                    <li class="nav-item">
                        <a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-receipt"></i>
                            <p>Đơn hàng</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.payments.index') }}" class="nav-link {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-credit-card"></i>
                            <p>Thanh toán</p>
                        </a>
                    </li>
                    <li class="nav-header text-uppercase" style="font-size:0.7rem;letter-spacing:.08em;color:#94A3B8">Khách & Khuyến mãi</li>
                    <li class="nav-item">
                        <a href="{{ route('admin.customers.index') }}" class="nav-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-people"></i>
                            <p>Khách hàng</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.coupons.index') }}" class="nav-link {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-ticket-perforated"></i>
                            <p>Mã giảm giá</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.shipping-rates.index') }}" class="nav-link {{ request()->routeIs('admin.shipping-rates.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-truck"></i>
                            <p>Phí vận chuyển</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.reviews.index') }}" class="nav-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-star-half"></i>
                            <p>Đánh giá</p>
                        </a>
                    </li>
                    <li class="nav-header text-uppercase" style="font-size:0.7rem;letter-spacing:.08em;color:#94A3B8">Hệ thống</li>
                    <li class="nav-item">
                        <a href="{{ route('home') }}" class="nav-link" target="_blank">
                            <i class="nav-icon bi bi-arrow-up-right-square"></i>
                            <p>Xem trang web</p>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

    {{-- Header --}}
    <nav class="app-header navbar navbar-expand" style="background-color:#0F2C59;">
        <div class="container-fluid">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                        <i class="bi bi-list text-white fs-5"></i>
                    </a>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle text-white fs-5"></i>
                        <span class="text-white fw-semibold" style="font-size:0.9rem">{{ Auth::user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i>Đăng xuất
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>

    {{-- Main Content --}}
    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h1 class="mb-0" style="font-size:1.4rem">@yield('page-title', 'Trang quản trị')</h1>
                    </div>
                    <div class="col-sm-6">
                        <nav aria-label="breadcrumb" class="float-end">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Trang chủ</a></li>
                                @yield('breadcrumb')
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <div class="app-content">
            <div class="container-fluid">
                {{-- Thông báo --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                        <i class="bi bi-x-circle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if(isset($errors) && $errors->any())
                    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </main>

    <footer class="app-footer">
        <strong>© {{ date('Y') }} CT mart</strong> – Trang quản trị. Mọi quyền được bảo lưu.
    </footer>
</div>

@stack('scripts')
</body>
</html>
