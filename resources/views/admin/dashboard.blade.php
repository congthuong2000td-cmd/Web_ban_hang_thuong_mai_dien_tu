@extends('layouts.admin')

@section('title', 'Bảng điều khiển quản trị - CT mart')

@section('content')
<div class="content-wrapper p-3 p-md-4">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1" style="color:var(--ct-primary)">
                Tổng quan hệ thống
            </h1>
            <p class="text-muted small mb-0">Theo dõi doanh thu, tình hình đơn hàng và hoạt động kinh doanh của CT mart.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg me-1"></i>Thêm sản phẩm
            </a>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-receipt me-1"></i>Xem đơn hàng
            </a>
        </div>
    </div>

    {{-- 4 Thẻ KPI chính --}}
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm p-3 h-100" style="border-left: 4px solid #10B981 !important;">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-muted small text-uppercase fw-semibold mb-1">Doanh thu thực tế</div>
                        <div class="h4 fw-bold mb-0 text-dark">{{ number_format($totalRevenue, 0, ',', '.') }}₫</div>
                    </div>
                    <div class="rounded-circle bg-success-subtle text-success p-2 fs-4 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                </div>
                <div class="text-muted small mt-2">
                    <span class="text-success fw-semibold"><i class="bi bi-check-circle"></i> Đã thanh toán</span>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm p-3 h-100" style="border-left: 4px solid #3B82F6 !important;">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-muted small text-uppercase fw-semibold mb-1">Tổng đơn hàng</div>
                        <div class="h4 fw-bold mb-0 text-dark">{{ number_format($totalOrders) }}</div>
                    </div>
                    <div class="rounded-circle bg-primary-subtle text-primary p-2 fs-4 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                        <i class="bi bi-bag-check"></i>
                    </div>
                </div>
                <div class="small mt-2">
                    @if($pendingOrders > 0)
                        <span class="text-warning fw-semibold"><i class="bi bi-clock-history"></i> {{ $pendingOrders }} đơn chờ xử lý</span>
                    @else
                        <span class="text-muted"><i class="bi bi-check2-all"></i> Đã xử lý hết</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm p-3 h-100" style="border-left: 4px solid #F59E0B !important;">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-muted small text-uppercase fw-semibold mb-1">Khách hàng</div>
                        <div class="h4 fw-bold mb-0 text-dark">{{ number_format($totalCustomers) }}</div>
                    </div>
                    <div class="rounded-circle bg-warning-subtle text-warning p-2 fs-4 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                        <i class="bi bi-people"></i>
                    </div>
                </div>
                <div class="text-muted small mt-2">
                    <span class="text-dark"><i class="bi bi-person-check"></i> Tài khoản mua sắm</span>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm p-3 h-100" style="border-left: 4px solid #8B5CF6 !important;">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-muted small text-uppercase fw-semibold mb-1">Sản phẩm đang bán</div>
                        <div class="h4 fw-bold mb-0 text-dark">{{ number_format($totalProducts) }}</div>
                    </div>
                    <div class="rounded-circle bg-purple-subtle text-purple p-2 fs-4 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; background: #f3e8ff; color: #8b5cf6;">
                        <i class="bi bi-box-seam"></i>
                    </div>
                </div>
                <div class="small mt-2">
                    @if($lowStockProducts > 0)
                        <span class="text-danger fw-semibold"><i class="bi bi-exclamation-triangle"></i> {{ $lowStockProducts }} sản phẩm sắp hết hàng</span>
                    @else
                        <span class="text-success"><i class="bi bi-check-all"></i> Kho hàng dồi dào</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Biểu đồ & Tình trạng đơn --}}
    <div class="row g-4 mb-4">
        {{-- Biểu đồ doanh thu --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="h6 fw-bold mb-0 text-dark">
                        <i class="bi bi-bar-chart-line-fill text-primary me-2"></i>Doanh thu 6 tháng gần nhất
                    </h2>
                    <span class="badge bg-light text-muted border">Đơn vị: VNĐ</span>
                </div>
                <div style="height: 280px; position: relative;">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Trạng thái đơn hàng --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm p-4 h-100">
                <h2 class="h6 fw-bold mb-3 text-dark">
                    <i class="bi bi-pie-chart-fill text-warning me-2"></i>Phân bổ trạng thái đơn
                </h2>
                <div class="d-flex flex-column gap-3 justify-content-center h-100">
                    <div class="p-3 rounded border d-flex justify-content-between align-items-center" style="background: #fffbeb;">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-hourglass-split text-warning fs-5"></i>
                            <span class="fw-semibold text-dark">Chờ xác nhận</span>
                        </div>
                        <span class="badge bg-warning text-dark fs-6">{{ $statusCounts['pending'] }}</span>
                    </div>

                    <div class="p-3 rounded border d-flex justify-content-between align-items-center" style="background: #eff6ff;">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-truck text-primary fs-5"></i>
                            <span class="fw-semibold text-dark">Đang giao hàng</span>
                        </div>
                        <span class="badge bg-primary fs-6">{{ $statusCounts['shipping'] }}</span>
                    </div>

                    <div class="p-3 rounded border d-flex justify-content-between align-items-center" style="background: #f0fdf4;">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-check-circle-fill text-success fs-5"></i>
                            <span class="fw-semibold text-dark">Hoàn thành</span>
                        </div>
                        <span class="badge bg-success fs-6">{{ $statusCounts['completed'] }}</span>
                    </div>

                    <div class="p-3 rounded border d-flex justify-content-between align-items-center" style="background: #fef2f2;">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-x-circle-fill text-danger fs-5"></i>
                            <span class="fw-semibold text-dark">Đã hủy</span>
                        </div>
                        <span class="badge bg-danger fs-6">{{ $statusCounts['cancelled'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Bảng đơn hàng mới nhất & Đánh giá --}}
    <div class="row g-4">
        {{-- Đơn hàng mới nhất --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="h6 fw-bold mb-0 text-dark">
                        <i class="bi bi-receipt-cutoff text-primary me-2"></i>Đơn hàng mới nhất
                    </h2>
                    <a href="{{ route('admin.orders.index') }}" class="small text-primary text-decoration-none fw-semibold">
                        Xem tất cả <i class="bi bi-arrow-right"></i>
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light small text-muted text-uppercase">
                            <tr>
                                <th>Mã đơn</th>
                                <th>Khách hàng</th>
                                <th>Tổng tiền</th>
                                <th>Thanh toán</th>
                                <th>Trạng thái</th>
                                <th class="text-end">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.orders.show', $order) }}" class="fw-bold text-dark text-decoration-none">
                                            #{{ $order->order_code }}
                                        </a>
                                        <div class="text-muted" style="font-size: 0.72rem;">{{ $order->created_at->format('d/m H:i') }}</div>
                                    </td>
                                    <td>
                                        <div class="small fw-semibold text-dark">{{ $order->receiver_name }}</div>
                                        <div class="text-muted" style="font-size: 0.72rem;">{{ $order->receiver_phone }}</div>
                                    </td>
                                    <td class="fw-bold small text-accent" style="color:var(--ct-accent);">
                                        {{ number_format($order->total_amount, 0, ',', '.') }}₫
                                    </td>
                                    <td>
                                        <span class="badge {{ $order->payment?->status?->badgeClass() ?? 'bg-secondary' }}" style="font-size:0.7rem;">
                                            {{ $order->payment?->status?->label() ?? 'Chờ' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $order->status->badgeClass() }}" style="font-size:0.7rem;">
                                            {{ $order->status->label() }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-outline-secondary btn-sm py-0 px-2" style="font-size: 0.78rem;">
                                            Xem
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted small">Chưa có đơn hàng nào.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Đánh giá gần đây --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="h6 fw-bold mb-0 text-dark">
                        <i class="bi bi-star-fill text-warning me-2"></i>Đánh giá gần đây
                    </h2>
                    <a href="{{ route('admin.reviews.index') }}" class="small text-primary text-decoration-none fw-semibold">
                        Quản lý <i class="bi bi-arrow-right"></i>
                    </a>
                </div>

                <div class="d-flex flex-column gap-3">
                    @forelse($recentReviews as $rev)
                        <div class="p-2 border rounded bg-light">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <strong class="small text-dark">{{ $rev->user->name ?? 'Khách' }}</strong>
                                <div class="text-warning small" style="font-size: 0.75rem;">
                                    @for($i=1;$i<=5;$i++)
                                        <i class="bi {{ $i <= $rev->rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                                    @endfor
                                </div>
                            </div>
                            <div class="small text-truncate text-muted mb-1">{{ $rev->product->name ?? 'Sản phẩm' }}</div>
                            <p class="small text-dark mb-0 fst-italic" style="font-size: 0.8rem;">"{{ Str::limit($rev->comment, 80) }}"</p>
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted small">Chưa có đánh giá nào.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

@push('head')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const ctx = document.getElementById('revenueChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($monthlyRevenue['labels']) !!},
                datasets: [{
                    label: 'Doanh thu (VNĐ)',
                    data: {!! json_encode($monthlyRevenue['data']) !!},
                    borderColor: '#10B981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.35,
                    pointBackgroundColor: '#10B981',
                    pointRadius: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return new Intl.NumberFormat('vi-VN').format(value) + '₫';
                            }
                        }
                    }
                }
            }
        });
    }
});
</script>
@endpush
@endsection
