<?php

namespace App\Http\Controllers\Account;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $query = Order::with(['items.product', 'payment'])
            ->where('user_id', Auth::id())
            ->orderByDesc('created_at');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(10)->withQueryString();

        return view('account.orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền xem đơn hàng này.');
        }

        $order->load(['items.product', 'payment', 'statusHistories.changer']);

        return view('account.orders.show', compact('order'));
    }

    public function cancel(Request $request, Order $order): RedirectResponse
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if ($order->status !== OrderStatus::PENDING) {
            return back()->with('error', 'Chỉ có thể hủy đơn hàng đang ở trạng thái chờ xác nhận.');
        }

        $request->validate([
            'cancel_reason' => ['nullable', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($order, $request) {
            // Hoàn tồn kho
            foreach ($order->items as $item) {
                $item->product?->increment('stock_quantity', $item->quantity);
            }

            $reason = $request->cancel_reason ?: 'Khách hàng chủ động hủy đơn.';

            $order->update([
                'status'        => OrderStatus::CANCELLED,
                'cancel_reason' => $reason,
                'cancelled_at'  => now(),
            ]);

            OrderStatusHistory::create([
                'order_id'    => $order->id,
                'from_status' => OrderStatus::PENDING,
                'to_status'   => OrderStatus::CANCELLED,
                'changed_by'  => Auth::id(),
                'note'        => 'Khách hàng hủy đơn: ' . $reason,
                'created_at'  => now(),
            ]);

            if ($order->payment && $order->payment->status === PaymentStatus::PENDING) {
                $order->payment->update(['status' => PaymentStatus::FAILED]);
            }
        });

        return back()->with('success', 'Đơn hàng #' . $order->order_code . ' đã được hủy thành công.');
    }
}
