<div class="card border-0 shadow-sm p-3 mb-4" style="border-radius: var(--ct-radius);">
    <div class="d-flex align-items-center gap-3 p-2 mb-3 border-bottom">
        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold fs-4" style="width: 52px; height: 52px;">
            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
        </div>
        <div class="min-w-0">
            <h2 class="h6 fw-bold mb-0 text-truncate" style="color:var(--ct-primary)">{{ Auth::user()->name }}</h2>
            <div class="text-muted small text-truncate">{{ Auth::user()->email }}</div>
        </div>
    </div>

    <div class="list-group list-group-flush">
        <a href="{{ route('account.profile') }}"
           class="list-group-item list-group-item-action border-0 px-3 py-2 rounded-2 mb-1 d-flex align-items-center gap-2 {{ request()->routeIs('account.profile') ? 'active fw-bold' : '' }}">
            <i class="bi bi-person-circle fs-5"></i>
            <span>Thông tin tài khoản</span>
        </a>

        <a href="{{ route('account.orders.index') }}"
           class="list-group-item list-group-item-action border-0 px-3 py-2 rounded-2 mb-1 d-flex align-items-center gap-2 {{ request()->routeIs('account.orders.*') ? 'active fw-bold' : '' }}">
            <i class="bi bi-bag-check fs-5"></i>
            <span>Đơn hàng của tôi</span>
        </a>

        <a href="{{ route('account.addresses.index') }}"
           class="list-group-item list-group-item-action border-0 px-3 py-2 rounded-2 mb-1 d-flex align-items-center gap-2 {{ request()->routeIs('account.addresses.*') ? 'active fw-bold' : '' }}">
            <i class="bi bi-geo-alt fs-5"></i>
            <span>Sổ địa chỉ nhận hàng</span>
        </a>

        @if(Auth::user()->isAdmin())
            <a href="{{ route('admin.dashboard') }}"
               class="list-group-item list-group-item-action border-0 px-3 py-2 rounded-2 mb-1 d-flex align-items-center gap-2 text-danger fw-semibold">
                <i class="bi bi-shield-lock fs-5"></i>
                <span>Trang quản trị (Admin)</span>
            </a>
        @endif

        <hr class="my-2">

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="list-group-item list-group-item-action border-0 px-3 py-2 rounded-2 text-danger d-flex align-items-center gap-2 w-100 bg-transparent">
                <i class="bi bi-box-arrow-right fs-5"></i>
                <span>Đăng xuất</span>
            </button>
        </form>
    </div>
</div>
