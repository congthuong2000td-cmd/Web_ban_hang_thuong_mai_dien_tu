@extends('layouts.app')

@section('title', 'Đơn hàng của tôi - CT mart')

@section('content')
<div class="container-xl py-4">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb mb-0" style="font-size:0.875rem;">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('account.profile') }}">Tài khoản</a></li>
            <li class="breadcrumb-item active" aria-current="page">Đơn hàng</li>
        </ol>
    </nav>

    <div class="row g-4">
        <aside class="col-lg-3">
            @include('account.partials.sidebar')
        </aside>

        <main class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="h5 fw-bold mb-0" style="color:var(--ct-primary)">
                    <i class="bi bi-bag-check-fill text-primary me-2"></i>Lịch sử đơn hàng
                </h1>
            </div>

            {{-- Filter Status Tabs --}}
            <div class="card border-0 shadow-sm p-2 mb-4" style="border-radius: var(--ct-radius);">
                <ul class="nav nav-pills nav-fill flex-nowrap overflow-auto" style="font-size: 0.88rem;">
                    <li class="nav-item">
                        <a class="nav-link {{ !request('status') ? 'active' : '' }}" href="{{ route('account.orders.index') }}">
                            Tất cả
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request('status') === 'pending' ? 'active' : '' }}" href="{{ route('account.orders.index', ['status' => 'pending']) }}">
                            Chờ xác nhận
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request('status') === 'shipping' ? 'active' : '' }}" href="{{ route('account.orders.index', ['status' => 'shipping']) }}">
                            Đang giao
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request('status') === 'completed' ? 'active' : '' }}" href="{{ route('account.orders.index', ['status' => 'completed']) }}">
                            Hoàn thành
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request('status') === 'cancelled' ? 'active' : '' }}" href="{{ route('account.orders.index', ['status' => 'cancelled']) }}">
                            Đã hủy
                        </a>
                    </li>
                </ul>
            </div>

            @if($orders->isEmpty())
                <div class="card border-0 shadow-sm p-5 text-center" style="border-radius: var(--ct-radius);">
                    <div class="mb-3 text-muted" style="font-size: 3.5rem;">
                        <i class="bi bi-box-seam"></i>
                    </div>
                    <h2 class="h6 fw-bold text-dark">Chưa có đơn hàng nào</h2>
                    <p class="text-muted small mb-3">Bạn chưa có đơn hàng nào ở trạng thái này.</p>
                    <div>
                        <a href="{{ route('products.index') }}" class="btn btn-primary btn-sm px-3">
                            <i class="bi bi-bag-plus me-1"></i>Bắt đầu mua sắm
                        </a>
                    </div>
                </div>
            @else
                <div class="d-flex flex-column gap-3">
                    @foreach($orders as $order)
                        <div class="card border-0 shadow-sm" style="border-radius: var(--ct-radius); overflow: hidden;">
                            {{-- Header Card --}}
                            <div class="card-header bg-light d-flex flex-wrap justify-content-between align-items-center py-2 px-3 border-0 gap-2">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="fw-bold text-dark">#{{ $order->order_code }}</span>
                                    <span class="text-muted small">| {{ $order->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge {{ $order->status->badgeClass() }}">
                                        {{ $order->status->label() }}
                                    </span>
                                    <span class="badge {{ $order->payment?->status?->badgeClass() ?? 'bg-secondary' }}">
                                        {{ $order->payment?->status?->label() ?? 'Chờ TT' }}
                                    </span>
                                </div>
                            </div>

                            {{-- Body: Items preview --}}
                            <div class="card-body p-3">
                                <div class="d-flex flex-column gap-2 mb-3">
                                    @foreach($order->items as $item)
                                        <div class="d-flex align-items-center gap-3">
                                            <div style="width: 48px; height: 48px; flex-shrink: 0;">
                                                @if($item->product && $item->product->thumbnail)
                                                    <img src="{{ asset('storage/' . $item->product->thumbnail) }}" alt="{{ $item->product_name }}" class="w-100 h-100 object-fit-contain rounded border">
                                                @else
                                                    <div class="w-100 h-100 bg-light rounded border d-flex align-items-center justify-content-center text-muted">
                                                        <i class="bi bi-box"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-grow-1 min-w-0">
                                                <div class="fw-semibold text-dark small text-truncate">{{ $item->product_name }}</div>
                                                <div class="text-muted" style="font-size: 0.75rem;">Số lượng: x{{ $item->quantity }}</div>
                                            </div>
                                            <div class="text-end small fw-semibold text-dark">
                                                {{ number_format($item->unit_price * $item->quantity, 0, ',', '.') }}₫
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="d-flex flex-wrap justify-content-between align-items-center pt-2 border-top">
                                    <div class="small text-muted">
                                        Giao đến: <strong class="text-dark">{{ $order->receiver_name }}</strong> ({{ $order->receiver_phone }})
                                    </div>
                                    <div class="d-flex align-items-baseline gap-2 mt-2 mt-sm-0">
                                        <span class="small text-muted">Tổng thanh toán:</span>
                                        <span class="fw-bold fs-5 text-accent" style="color:var(--ct-accent);">
                                            {{ number_format($order->total_amount, 0, ',', '.') }}₫
                                        </span>
                                    </div>
                                </div>
                            </div>

                            {{-- Footer Card --}}
                            <div class="card-footer bg-white border-top py-2 px-3 d-flex justify-content-end gap-2">
                                @if($order->status === \App\Enums\OrderStatus::PENDING)
                                    <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#cancelModal-{{ $order->id }}">
                                        Hủy đơn
                                    </button>

                                    {{-- Modal Hủy Đơn --}}
                                    <div class="modal fade" id="cancelModal-{{ $order->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <form action="{{ route('account.orders.cancel', $order) }}" method="POST">
                                                @csrf
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title h6 fw-bold text-danger">Xác nhận hủy đơn hàng #{{ $order->order_code }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p class="small text-muted mb-3">Bạn có chắc chắn muốn hủy đơn hàng này không? Số lượng sản phẩm sẽ được hoàn lại vào kho.</p>
                                                        <label for="reason-{{ $order->id }}" class="form-label small fw-semibold">Lý do hủy đơn (tùy chọn):</label>
                                                        <textarea name="cancel_reason" id="reason-{{ $order->id }}" class="form-control form-control-sm" rows="3" placeholder="Ví dụ: Đổi ý, muốn thay đổi địa chỉ..."></textarea>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Đóng</button>
                                                        <button type="submit" class="btn btn-danger btn-sm">Xác nhận hủy đơn</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @endif

                                <a href="{{ route('account.orders.show', $order) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-eye me-1"></i>Xem chi tiết
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Phân trang --}}
                <div class="mt-4 d-flex justify-content-center">
                    {{ $orders->links() }}
                </div>
            @endif
        </main>
    </div>
</div>
@endsection
