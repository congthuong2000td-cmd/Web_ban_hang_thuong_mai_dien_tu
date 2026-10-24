<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    /**
     * Lấy dữ liệu giỏ hàng từ session kèm thông tin sản phẩm.
     */
    private function getCartWithProducts(): array
    {
        $cart = session('cart', []);
        $items = [];
        $subtotal = 0;

        foreach ($cart as $productId => $item) {
            $product = Product::with('images')->find($productId);
            if (! $product || ! $product->is_active) {
                // Xóa sản phẩm không còn tồn tại/active
                unset($cart[$productId]);
                session(['cart' => $cart]);
                continue;
            }

            // Kiểm tra tồn kho
            $qty = min((int) $item['quantity'], $product->stock_quantity);
            if ($qty < 1) {
                unset($cart[$productId]);
                session(['cart' => $cart]);
                continue;
            }

            $lineTotal = $product->price * $qty;
            $subtotal += $lineTotal;

            $items[] = [
                'product'    => $product,
                'quantity'   => $qty,
                'line_total' => $lineTotal,
            ];
        }

        return ['items' => $items, 'subtotal' => $subtotal];
    }

    public function index(): View
    {
        $data = $this->getCartWithProducts();
        return view('cart.index', [
            'items'    => $data['items'],
            'subtotal' => $data['subtotal'],
        ]);
    }

    public function add(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity'   => ['required', 'integer', 'min:1', 'max:100'],
        ]);

        $product = Product::findOrFail($request->product_id);

        if (! $product->is_active) {
            return response()->json(['success' => false, 'message' => 'Sản phẩm không còn kinh doanh.'], 422);
        }

        $cart = session('cart', []);
        $currentQty = $cart[$product->id]['quantity'] ?? 0;
        $newQty = $currentQty + (int) $request->quantity;

        if ($newQty > $product->stock_quantity) {
            return response()->json([
                'success' => false,
                'message' => "Chỉ còn {$product->stock_quantity} sản phẩm trong kho.",
            ], 422);
        }

        $cart[$product->id] = ['quantity' => $newQty];
        session(['cart' => $cart]);

        return response()->json([
            'success'    => true,
            'message'    => "Đã thêm \"{$product->name}\" vào giỏ hàng.",
            'cart_count' => count($cart),
        ]);
    }

    public function update(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity'   => ['required', 'integer', 'min:1', 'max:100'],
        ]);

        $product = Product::findOrFail($request->product_id);
        $qty = (int) $request->quantity;

        if ($qty > $product->stock_quantity) {
            return response()->json([
                'success' => false,
                'message' => "Chỉ còn {$product->stock_quantity} sản phẩm trong kho.",
            ], 422);
        }

        $cart = session('cart', []);
        $cart[$product->id] = ['quantity' => $qty];
        session(['cart' => $cart]);

        $data = $this->getCartWithProducts();
        $lineTotal = 0;
        foreach ($data['items'] as $item) {
            if ($item['product']->id === $product->id) {
                $lineTotal = $item['line_total'];
                break;
            }
        }

        return response()->json([
            'success'             => true,
            'cart_count'          => count($cart),
            'subtotal'            => $data['subtotal'],
            'subtotal_formatted'  => number_format($data['subtotal'], 0, ',', '.') . ' ₫',
            'line_total'          => $lineTotal,
            'line_total_formatted'=> number_format($lineTotal, 0, ',', '.') . ' ₫',
        ]);
    }

    public function remove(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => ['required', 'integer'],
        ]);

        $cart = session('cart', []);
        unset($cart[$request->product_id]);
        session(['cart' => $cart]);

        $data = $this->getCartWithProducts();

        return response()->json([
            'success'            => true,
            'cart_count'         => count($cart),
            'subtotal'           => $data['subtotal'],
            'subtotal_formatted' => number_format($data['subtotal'], 0, ',', '.') . ' ₫',
        ]);
    }

    public function clear(Request $request): RedirectResponse
    {
        $request->session()->forget('cart');
        return redirect()->route('cart.index')->with('success', 'Đã xóa toàn bộ giỏ hàng.');
    }
}
