<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // 1. Chỉ số tổng quan
        $totalRevenue = Payment::where('status', PaymentStatus::PAID)->sum('amount');
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', OrderStatus::PENDING)->count();
        $totalCustomers = User::where('role', UserRole::CUSTOMER)->count();
        $totalProducts = Product::count();
        $lowStockProducts = Product::where('stock_quantity', '<=', 5)->count();

        // 2. Đơn hàng gần đây
        $recentOrders = Order::with(['user', 'payment', 'items'])
            ->latest()
            ->take(8)
            ->get();

        // 3. Đánh giá mới nhất
        $recentReviews = Review::with(['user', 'product'])
            ->latest()
            ->take(5)
            ->get();

        // 4. Doanh thu 6 tháng gần nhất (cho biểu đồ)
        $monthlyRevenue = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthLabel = 'Tháng ' . $month->format('m/Y');
            $rev = Payment::where('status', PaymentStatus::PAID)
                ->whereYear('paid_at', $month->year)
                ->whereMonth('paid_at', $month->month)
                ->sum('amount');
            $monthlyRevenue['labels'][] = $monthLabel;
            $monthlyRevenue['data'][] = (int) $rev;
        }

        // 5. Thống kê theo trạng thái đơn hàng
        $statusCounts = [
            'pending'   => Order::where('status', OrderStatus::PENDING)->count(),
            'shipping'  => Order::where('status', OrderStatus::SHIPPING)->count(),
            'completed' => Order::where('status', OrderStatus::COMPLETED)->count(),
            'cancelled' => Order::where('status', OrderStatus::CANCELLED)->count(),
        ];

        return view('admin.dashboard', compact(
            'totalRevenue',
            'totalOrders',
            'pendingOrders',
            'totalCustomers',
            'totalProducts',
            'lowStockProducts',
            'recentOrders',
            'recentReviews',
            'monthlyRevenue',
            'statusCounts'
        ));
    }
}
