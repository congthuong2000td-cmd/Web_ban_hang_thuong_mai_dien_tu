<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function index(Request $request): View
    {
        $query = Payment::with(['order.user'])->latest();

        if ($request->filled('method')) {
            $query->where('method', $request->method);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('txn_ref', 'like', "%{$search}%")
                  ->orWhere('gateway_txn_no', 'like', "%{$search}%")
                  ->orWhereHas('order', fn($oq) => $oq->where('order_code', 'like', "%{$search}%"));
            });
        }

        $payments = $query->paginate(15)->withQueryString();

        return view('admin.payments.index', compact('payments'));
    }

    public function show(Payment $payment): View
    {
        $payment->load(['order.user', 'order.items.product']);
        return view('admin.payments.show', compact('payment'));
    }
}
