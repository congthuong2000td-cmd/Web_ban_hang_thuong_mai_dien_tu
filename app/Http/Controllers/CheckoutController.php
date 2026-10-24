<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Models\Address;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ShippingRate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function index(): View|RedirectResponse
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('warning', 'Giỏ hàng của bạn đang trống.');
        }

        // Dựng danh sách items với kiểm tra tồn kho
        $items = [];
        $subtotal = 0;

        foreach ($cart as $productId => $item) {
            $product = Product::with('images')->find($productId);
            if (! $product || ! $product->is_active || $product->stock_quantity < 1) {
                continue;
            }
            $qty = min((int) $item['quantity'], $product->stock_quantity);
            $lineTotal = $product->price * $qty;
            $subtotal += $lineTotal;
            $items[] = compact('product', 'qty', 'lineTotal');
        }

        if (empty($items)) {
            return redirect()->route('cart.index')->with('error', 'Sản phẩm trong giỏ hàng đã hết hàng hoặc không còn kinh doanh.');
        }

        // Địa chỉ của user
        $addresses = Auth::user()->addresses()->orderByDesc('is_default')->get();
        $defaultAddress = $addresses->firstWhere('is_default', true) ?? $addresses->first();

        // Tỉnh thành cho phí ship
        $shippingRates = ShippingRate::orderBy('province_name')->get();

        return view('checkout.index', compact('items', 'subtotal', 'addresses', 'defaultAddress', 'shippingRates'));
    }

    public function store(Request $request): RedirectResponse
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index');
        }

        $request->validate([
            'recipient_name'    => ['required', 'string', 'max:255'],
            'recipient_phone'   => ['required', 'string', 'max:20'],
            'province'          => ['required', 'string', 'max:100'],
            'district'          => ['required', 'string', 'max:100'],
            'address_detail'    => ['required', 'string', 'max:500'],
            'payment_method'    => ['required', 'in:cod,vnpay'],
            'coupon_code'       => ['nullable', 'string', 'max:50'],
        ], [
            'recipient_name.required'  => 'Vui lòng nhập họ tên người nhận.',
            'recipient_phone.required' => 'Vui lòng nhập số điện thoại người nhận.',
            'province.required'        => 'Vui lòng chọn tỉnh/thành phố.',
            'district.required'        => 'Vui lòng nhập quận/huyện.',
            'address_detail.required'  => 'Vui lòng nhập địa chỉ chi tiết.',
            'payment_method.required'  => 'Vui lòng chọn phương thức thanh toán.',
        ]);

        // Tính toán đơn hàng
        $subtotal = 0;
        $orderItems = [];

        foreach ($cart as $productId => $item) {
            $product = Product::find($productId);
            if (! $product || ! $product->is_active) continue;

            $qty = min((int) $item['quantity'], $product->stock_quantity);
            if ($qty < 1) continue;

            $unitPrice = $product->price;
            $lineTotal = $unitPrice * $qty;
            $subtotal += $lineTotal;
            $orderItems[] = ['product' => $product, 'qty' => $qty, 'unit_price' => $unitPrice, 'line_total' => $lineTotal];
        }

        if (empty($orderItems)) {
            return redirect()->route('cart.index')->with('error', 'Tất cả sản phẩm trong giỏ hàng đã hết hàng.');
        }

        // Phí vận chuyển
        $shippingRate = ShippingRate::where('province_name', $request->province)->first();
        $shippingFee = $shippingRate?->fee ?? 30000; // 30,000₫ mặc định

        // Coupon
        $discountAmount = 0;
        $coupon = null;

        if ($request->filled('coupon_code')) {
            $coupon = Coupon::where('code', strtoupper($request->coupon_code))->first();
            if ($coupon && $coupon->isValid($subtotal)) {
                $discountAmount = $coupon->calculateDiscount($subtotal);
            }
        }

        $totalAmount = max(0, $subtotal + $shippingFee - $discountAmount);

        // Lưu vào DB
        DB::beginTransaction();
        try {
            $shippingAddress = implode(', ', array_filter([
                $request->address_detail,
                $request->district,
                $request->province,
            ]));

            $orderCode = 'CTM' . date('ymd') . strtoupper(substr(uniqid(), -4));

            $order = Order::create([
                'order_code'       => $orderCode,
                'user_id'          => Auth::id(),
                'coupon_id'        => $coupon?->id,
                'receiver_name'    => $request->recipient_name,
                'receiver_phone'   => $request->recipient_phone,
                'shipping_address' => $shippingAddress,
                'subtotal'         => $subtotal,
                'shipping_fee'     => $shippingFee,
                'discount_amount'  => $discountAmount,
                'total_amount'     => $totalAmount,
                'status'           => OrderStatus::PENDING,
                'note'             => $request->note,
            ]);

            // Order items + trừ tồn kho
            foreach ($orderItems as $oi) {
                OrderItem::create([
                    'order_id'     => $order->id,
                    'product_id'   => $oi['product']->id,
                    'product_name' => $oi['product']->name,
                    'product_sku'  => $oi['product']->sku ?? ('SKU-' . $oi['product']->id),
                    'quantity'     => $oi['qty'],
                    'unit_price'   => $oi['unit_price'],
                ]);

                $oi['product']->decrement('stock_quantity', $oi['qty']);
            }

            // Lịch sử trạng thái
            OrderStatusHistory::create([
                'order_id'    => $order->id,
                'from_status' => null,
                'to_status'   => OrderStatus::PENDING,
                'changed_by'  => Auth::id(),
                'note'        => 'Đơn hàng được đặt thành công.',
                'created_at'  => now(),
            ]);

            // Tăng usage coupon
            if ($coupon) {
                $coupon->increment('used_count');
            }

            // Tạo payment record
            $txnRef = 'TXN' . time() . rand(100, 999);
            $payment = Payment::create([
                'order_id' => $order->id,
                'method'   => PaymentMethod::from($request->payment_method),
                'amount'   => $totalAmount,
                'status'   => PaymentStatus::PENDING,
                'txn_ref'  => $txnRef,
            ]);

            DB::commit();

            // Xóa giỏ hàng
            $request->session()->forget('cart');

            // VNPay: redirect sang trang thanh toán
            if ($request->payment_method === 'vnpay') {
                return $this->redirectToVnpay($order, $payment, $request);
            }

            return redirect()->route('checkout.success', $order)->with('success', 'Đặt hàng thành công! Chúng tôi sẽ liên hệ với bạn sớm nhất.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Checkout failed: ' . $e->getMessage());
            return back()->with('error', 'Có lỗi xảy ra khi đặt hàng: ' . $e->getMessage())->withInput();
        }
    }

    public function success(Order $order): View|RedirectResponse
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load(['items.product', 'payment']);

        return view('checkout.success', compact('order'));
    }

    // ── VNPay ─────────────────────────────────────────────────────────────

    private function redirectToVnpay(Order $order, Payment $payment, Request $request)
    {
        $vnpUrl        = config('vnpay.url');
        $vnpTmnCode    = config('vnpay.tmn_code');
        $vnpHashSecret = config('vnpay.hash_secret');
        $vnpReturnUrl  = route('checkout.vnpay.return');

        $vnpTxnRef = $order->id . '_' . time();

        $vnpParams = [
            'vnp_Version'   => '2.1.0',
            'vnp_Command'   => 'pay',
            'vnp_TmnCode'   => $vnpTmnCode,
            'vnp_Amount'    => $order->total_amount * 100,
            'vnp_CurrCode'  => 'VND',
            'vnp_TxnRef'    => $vnpTxnRef,
            'vnp_OrderInfo' => 'Thanh toan don hang #' . $order->order_code,
            'vnp_OrderType' => 'other',
            'vnp_Locale'    => 'vn',
            'vnp_ReturnUrl' => $vnpReturnUrl,
            'vnp_IpAddr'    => $request->ip(),
            'vnp_CreateDate'=> now()->format('YmdHis'),
        ];

        ksort($vnpParams);
        $query = http_build_query($vnpParams);
        $vnpSecureHash = hash_hmac('sha512', $query, $vnpHashSecret);
        $paymentUrl = $vnpUrl . '?' . $query . '&vnp_SecureHash=' . $vnpSecureHash;

        // Lưu txn ref
        $payment->update(['txn_ref' => $vnpTxnRef]);

        return redirect()->away($paymentUrl);
    }

    public function vnpayReturn(Request $request): View|RedirectResponse
    {
        $vnpHashSecret = config('vnpay.hash_secret');
        $inputData = $request->except('vnp_SecureHash');
        ksort($inputData);
        $hashData = http_build_query($inputData);
        $secureHash = hash_hmac('sha512', $hashData, $vnpHashSecret);

        if ($secureHash !== $request->vnp_SecureHash) {
            return redirect()->route('home')->with('error', 'Chữ ký thanh toán không hợp lệ.');
        }

        $txnRef = $request->vnp_TxnRef;
        $orderId = explode('_', $txnRef)[0];
        $order = Order::with(['items.product', 'payment'])->find($orderId);

        if (! $order || $order->user_id !== Auth::id()) {
            abort(404);
        }

        $payment = $order->payment;

        if ($request->vnp_ResponseCode === '00') {
            // Thành công
            DB::transaction(function () use ($order, $payment, $request, $txnRef) {
                $payment->update([
                    'status'         => PaymentStatus::PAID,
                    'txn_ref'        => $txnRef,
                    'gateway_txn_no' => $request->vnp_TransactionNo,
                    'bank_code'      => $request->vnp_BankCode,
                    'response_code'  => $request->vnp_ResponseCode,
                    'paid_at'        => now(),
                ]);
                
                OrderStatusHistory::create([
                    'order_id'    => $order->id,
                    'from_status' => OrderStatus::PENDING,
                    'to_status'   => OrderStatus::PENDING,
                    'changed_by'  => Auth::id(),
                    'note'        => 'Thanh toán VNPay thành công. Mã GD: ' . $request->vnp_TransactionNo,
                    'created_at'  => now(),
                ]);
            });

            return view('checkout.success', compact('order'))->with('success', 'Thanh toán VNPay thành công!');
        }

        // Thất bại: hoàn tồn kho
        foreach ($order->items as $item) {
            $item->product?->increment('stock_quantity', $item->quantity);
        }
        $order->update(['status' => OrderStatus::CANCELLED]);
        $payment->update([
            'status'        => PaymentStatus::FAILED,
            'response_code' => $request->vnp_ResponseCode,
        ]);

        OrderStatusHistory::create([
            'order_id'    => $order->id,
            'from_status' => OrderStatus::PENDING,
            'to_status'   => OrderStatus::CANCELLED,
            'changed_by'  => Auth::id(),
            'note'        => 'Thanh toán VNPay thất bại hoặc bị hủy bởi khách hàng.',
            'created_at'  => now(),
        ]);

        return redirect()->route('account.orders.show', $order)->with('error', 'Thanh toán VNPay thất bại (mã phản hồi: ' . $request->vnp_ResponseCode . '). Đơn hàng đã được ghi nhận hủy.');
    }

    public function vnpayIpn(Request $request)
    {
        // IPN endpoint (server-to-server)
        $vnpHashSecret = config('vnpay.hash_secret');
        $inputData = $request->except('vnp_SecureHash');
        ksort($inputData);
        $secureHash = hash_hmac('sha512', http_build_query($inputData), $vnpHashSecret);

        if ($secureHash !== $request->vnp_SecureHash) {
            return response()->json(['RspCode' => '97', 'Message' => 'Invalid signature']);
        }

        $txnRef = $request->vnp_TxnRef;
        $orderId = explode('_', $txnRef)[0];
        $order = Order::with('payment')->find($orderId);

        if (! $order) {
            return response()->json(['RspCode' => '01', 'Message' => 'Order not found']);
        }

        if ($request->vnp_ResponseCode === '00') {
            $order->payment?->update([
                'status'         => PaymentStatus::PAID,
                'gateway_txn_no' => $request->vnp_TransactionNo,
                'bank_code'      => $request->vnp_BankCode,
                'response_code'  => $request->vnp_ResponseCode,
                'paid_at'        => now(),
            ]);
        } else {
            $order->payment?->update([
                'status'        => PaymentStatus::FAILED,
                'response_code' => $request->vnp_ResponseCode,
            ]);
        }

        return response()->json(['RspCode' => '00', 'Message' => 'Confirm Success']);
    }
}
