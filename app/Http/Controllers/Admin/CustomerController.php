<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::where('role', UserRole::CUSTOMER)
            ->withCount('orders')
            ->orderByDesc('created_at');

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $customers = $query->paginate(15)->withQueryString();

        return view('admin.customers.index', compact('customers'));
    }

    public function show(User $customer): View
    {
        if ($customer->role !== UserRole::CUSTOMER) {
            abort(404);
        }

        $customer->load(['addresses', 'orders.payment', 'orders.items']);
        $totalSpent = $customer->orders()
            ->whereHas('payment', fn($q) => $q->where('status', 'paid'))
            ->sum('total_amount');

        return view('admin.customers.show', compact('customer', 'totalSpent'));
    }

    public function toggleActive(User $customer): RedirectResponse
    {
        if ($customer->isAdmin()) {
            return back()->with('error', 'Không thể khóa tài khoản quản trị viên.');
        }

        $customer->update([
            'is_active' => ! $customer->is_active,
        ]);

        $statusStr = $customer->is_active ? 'mở khóa' : 'khóa';

        return back()->with('success', "Đã {$statusStr} tài khoản khách hàng {$customer->name}!");
    }
}
