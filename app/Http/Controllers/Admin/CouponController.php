<?php

namespace App\Http\Controllers\Admin;

use App\Enums\DiscountType;
use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CouponController extends Controller
{
    public function index(Request $request): View
    {
        $query = Coupon::orderByDesc('created_at');

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            });
        }

        $coupons = $query->paginate(15)->withQueryString();

        return view('admin.coupons.index', compact('coupons'));
    }

    public function create(): View
    {
        return view('admin.coupons.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code'             => ['required', 'string', 'max:50', 'unique:coupons,code'],
            'name'             => ['nullable', 'string', 'max:255'],
            'discount_type'    => ['required', 'in:percentage,fixed'],
            'discount_value'   => ['required', 'integer', 'min:1'],
            'min_order_amount' => ['nullable', 'integer', 'min:0'],
            'max_discount'     => ['nullable', 'integer', 'min:0'],
            'usage_limit'      => ['nullable', 'integer', 'min:1'],
            'starts_at'        => ['nullable', 'date'],
            'expires_at'       => ['nullable', 'date', 'after_or_equal:starts_at'],
            'is_active'        => ['nullable', 'boolean'],
        ], [
            'code.required' => 'Vui lòng nhập mã giảm giá.',
            'code.unique'   => 'Mã giảm giá này đã tồn tại.',
            'discount_value.required' => 'Vui lòng nhập giá trị giảm giá.',
        ]);

        Coupon::create([
            'code'             => strtoupper($validated['code']),
            'name'             => $validated['name'],
            'discount_type'    => DiscountType::from($validated['discount_type']),
            'discount_value'   => $validated['discount_value'],
            'min_order_amount' => $validated['min_order_amount'] ?: 0,
            'max_discount'     => $validated['max_discount'] ?: null,
            'usage_limit'      => $validated['usage_limit'] ?: null,
            'starts_at'        => $validated['starts_at'],
            'expires_at'       => $validated['expires_at'],
            'is_active'        => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.coupons.index')->with('success', 'Tạo mã giảm giá mới thành công!');
    }

    public function edit(Coupon $coupon): View
    {
        return view('admin.coupons.edit', compact('coupon'));
    }

    public function update(Request $request, Coupon $coupon): RedirectResponse
    {
        $validated = $request->validate([
            'code'             => ['required', 'string', 'max:50', 'unique:coupons,code,' . $coupon->id],
            'name'             => ['nullable', 'string', 'max:255'],
            'discount_type'    => ['required', 'in:percentage,fixed'],
            'discount_value'   => ['required', 'integer', 'min:1'],
            'min_order_amount' => ['nullable', 'integer', 'min:0'],
            'max_discount'     => ['nullable', 'integer', 'min:0'],
            'usage_limit'      => ['nullable', 'integer', 'min:1'],
            'starts_at'        => ['nullable', 'date'],
            'expires_at'       => ['nullable', 'date', 'after_or_equal:starts_at'],
            'is_active'        => ['nullable', 'boolean'],
        ]);

        $coupon->update([
            'code'             => strtoupper($validated['code']),
            'name'             => $validated['name'],
            'discount_type'    => DiscountType::from($validated['discount_type']),
            'discount_value'   => $validated['discount_value'],
            'min_order_amount' => $validated['min_order_amount'] ?: 0,
            'max_discount'     => $validated['max_discount'] ?: null,
            'usage_limit'      => $validated['usage_limit'] ?: null,
            'starts_at'        => $validated['starts_at'],
            'expires_at'       => $validated['expires_at'],
            'is_active'        => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.coupons.index')->with('success', 'Cập nhật mã giảm giá thành công!');
    }

    public function destroy(Coupon $coupon): RedirectResponse
    {
        $coupon->delete();
        return redirect()->route('admin.coupons.index')->with('success', 'Xóa mã giảm giá thành công!');
    }
}
