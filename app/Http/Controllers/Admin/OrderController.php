<?php

namespace App\Http\Controllers\Admin;

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
        $query = Order::with(['user', 'payment', 'items'])->orderByDesc('created_at');

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('order_code', 'like', "%{$search}%")
                  ->orWhere('receiver_name', 'like', "%{$search}%")
                  ->orWhere('receiver_phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->whereHas('payment', fn($q) => $q->where('status', $request->payment_status));
        }

        $orders = $query->paginate(15)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        $order->load(['user', 'payment', 'items.product', 'statusHistories.changer']);

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $request->validate([
            'status' => ['required', 'in:pending,shipping,completed,cancelled'],
            'note'   => ['nullable', 'string', 'max:255'],
        ]);

        $newStatus = OrderStatus::from($request->status);
        $oldStatus = $order->status;

        if ($newStatus === $oldStatus) {
            return back()->with('info', 'Trạng thái đơn hàng không thay đổi.');
        }

        DB::transaction(function () use ($order, $newStatus, $oldStatus, $request) {
            // Nếu hủy đơn: hoàn lại tồn kho
            if ($newStatus === OrderStatus::CANCELLED && $oldStatus !== OrderStatus::CANCELLED) {
                foreach ($order->items as $item) {
                    $item->product?->increment('stock_quantity', $item->quantity);
                }
                $order->update([
                    'status'        => $newStatus,
                    'cancel_reason' => $request->note ?: 'Quản trị viên hủy đơn',
                    'cancelled_at'  => now(),
                ]);
            } elseif ($newStatus === OrderStatus::COMPLETED) {
                $order->update([
                    'status'       => $newStatus,
                    'completed_at' => now(),
                ]);
                // Nếu COD thì cập nhật thanh toán thành công
                if ($order->payment && $order->payment->status === PaymentStatus::PENDING) {
                    $order->payment->update([
                        'status'  => PaymentStatus::PAID,
                        'paid_at' => now(),
                    ]);
                }
            } else {
                $order->update(['status' => $newStatus]);
            }

            // Ghi nhật ký trạng thái
            OrderStatusHistory::create([
                'order_id'    => $order->id,
                'from_status' => $oldStatus,
                'to_status'   => $newStatus,
                'changed_by'  => Auth::id(),
                'note'        => $request->note ?: ('Quản trị viên chuyển trạng thái sang ' . $newStatus->label()),
                'created_at'  => now(),
            ]);
        });

        return back()->with('success', 'Cập nhật trạng thái đơn hàng #' . $order->order_code . ' thành công!');
    }
}
