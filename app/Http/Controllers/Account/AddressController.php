<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\ShippingRate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AddressController extends Controller
{
    public function index(): View
    {
        $addresses = Auth::user()->addresses()->orderByDesc('is_default')->latest()->get();
        return view('account.addresses.index', compact('addresses'));
    }

    public function create(): View
    {
        $shippingRates = ShippingRate::orderBy('province_name')->get();
        return view('account.addresses.create', compact('shippingRates'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'receiver_name'  => ['required', 'string', 'max:255'],
            'receiver_phone' => ['required', 'string', 'max:20'],
            'province'       => ['required', 'string', 'max:100'],
            'ward'           => ['required', 'string', 'max:100'],
            'street_address' => ['required', 'string', 'max:255'],
            'is_default'     => ['nullable', 'boolean'],
        ], [
            'receiver_name.required'  => 'Vui lòng nhập họ tên người nhận.',
            'receiver_phone.required' => 'Vui lòng nhập số điện thoại.',
            'province.required'       => 'Vui lòng chọn tỉnh/thành phố.',
            'ward.required'           => 'Vui lòng nhập quận/huyện/phường.',
            'street_address.required' => 'Vui lòng nhập địa chỉ chi tiết.',
        ]);

        $user = Auth::user();
        $isDefault = $request->boolean('is_default') || $user->addresses()->count() === 0;

        if ($isDefault) {
            $user->addresses()->update(['is_default' => false]);
        }

        $user->addresses()->create([
            'receiver_name'  => $validated['receiver_name'],
            'receiver_phone' => $validated['receiver_phone'],
            'province'       => $validated['province'],
            'ward'           => $validated['ward'],
            'street_address' => $validated['street_address'],
            'is_default'     => $isDefault,
        ]);

        return redirect()->route('account.addresses.index')->with('success', 'Thêm địa chỉ nhận hàng thành công!');
    }

    public function edit(Address $dia_chi): View
    {
        if ($dia_chi->user_id !== Auth::id()) {
            abort(403);
        }

        $address = $dia_chi;
        $shippingRates = ShippingRate::orderBy('province_name')->get();
        return view('account.addresses.edit', compact('address', 'shippingRates'));
    }

    public function update(Request $request, Address $dia_chi): RedirectResponse
    {
        if ($dia_chi->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'receiver_name'  => ['required', 'string', 'max:255'],
            'receiver_phone' => ['required', 'string', 'max:20'],
            'province'       => ['required', 'string', 'max:100'],
            'ward'           => ['required', 'string', 'max:100'],
            'street_address' => ['required', 'string', 'max:255'],
            'is_default'     => ['nullable', 'boolean'],
        ]);

        $isDefault = $request->boolean('is_default');

        if ($isDefault) {
            Auth::user()->addresses()->where('id', '!=', $dia_chi->id)->update(['is_default' => false]);
        }

        $dia_chi->update([
            'receiver_name'  => $validated['receiver_name'],
            'receiver_phone' => $validated['receiver_phone'],
            'province'       => $validated['province'],
            'ward'           => $validated['ward'],
            'street_address' => $validated['street_address'],
            'is_default'     => $isDefault || $dia_chi->is_default,
        ]);

        return redirect()->route('account.addresses.index')->with('success', 'Cập nhật địa chỉ thành công!');
    }

    public function destroy(Address $dia_chi): RedirectResponse
    {
        if ($dia_chi->user_id !== Auth::id()) {
            abort(403);
        }

        $wasDefault = $dia_chi->is_default;
        $dia_chi->delete();

        if ($wasDefault) {
            $next = Auth::user()->addresses()->first();
            $next?->update(['is_default' => true]);
        }

        return redirect()->route('account.addresses.index')->with('success', 'Xóa địa chỉ thành công!');
    }

    public function setDefault(Address $address): RedirectResponse
    {
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        Auth::user()->addresses()->update(['is_default' => false]);
        $address->update(['is_default' => true]);

        return redirect()->route('account.addresses.index')->with('success', 'Đã đặt làm địa chỉ mặc định!');
    }
}
