<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingRate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShippingRateController extends Controller
{
    public function index(Request $request): View
    {
        $query = ShippingRate::orderBy('province_name');

        if ($request->filled('q')) {
            $query->where('province_name', 'like', '%' . $request->q . '%');
        }

        $shippingRates = $query->paginate(20)->withQueryString();

        return view('admin.shipping-rates.index', compact('shippingRates'));
    }

    public function create(): View
    {
        return view('admin.shipping-rates.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'province_name'  => ['required', 'string', 'max:100', 'unique:shipping_rates,province_name'],
            'fee'            => ['required', 'integer', 'min:0'],
            'estimated_days' => ['required', 'integer', 'min:1', 'max:30'],
        ], [
            'province_name.required' => 'Vui lòng nhập tên tỉnh/thành phố.',
            'province_name.unique'   => 'Tỉnh/thành phố này đã được cấu hình phí ship.',
            'fee.required'           => 'Vui lòng nhập phí vận chuyển.',
        ]);

        ShippingRate::create($validated);

        return redirect()->route('admin.shipping-rates.index')->with('success', 'Thêm biểu phí vận chuyển thành công!');
    }

    public function edit(ShippingRate $shippingRate): View
    {
        return view('admin.shipping-rates.edit', compact('shippingRate'));
    }

    public function update(Request $request, ShippingRate $shippingRate): RedirectResponse
    {
        $validated = $request->validate([
            'province_name'  => ['required', 'string', 'max:100', 'unique:shipping_rates,province_name,' . $shippingRate->id],
            'fee'            => ['required', 'integer', 'min:0'],
            'estimated_days' => ['required', 'integer', 'min:1', 'max:30'],
        ]);

        $shippingRate->update($validated);

        return redirect()->route('admin.shipping-rates.index')->with('success', 'Cập nhật phí vận chuyển thành công!');
    }

    public function destroy(ShippingRate $shippingRate): RedirectResponse
    {
        $shippingRate->delete();
        return redirect()->route('admin.shipping-rates.index')->with('success', 'Xóa biểu phí vận chuyển thành công!');
    }
}
