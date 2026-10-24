<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        // Lấy sản phẩm nổi bật (featured)
        $featuredProducts = Product::with(['category', 'images'])
            ->active()
            ->featured()
            ->orderByDesc('created_at')
            ->take(8)
            ->get();

        // Sản phẩm mới nhất
        $newProducts = Product::with(['category', 'images'])
            ->active()
            ->orderByDesc('created_at')
            ->take(10)
            ->get();

        // Danh mục với số sản phẩm
        $categories = Category::withCount(['products' => fn($q) => $q->active()])
            ->where('is_active', true)
            ->orderByDesc('products_count')
            ->take(6)
            ->get();

        // Banner / thống kê nhanh cho logged user
        $recentOrder = null;
        if (Auth::check()) {
            $recentOrder = Order::where('user_id', Auth::id())
                ->latest()
                ->first();
        }

        return view('home', compact('featuredProducts', 'newProducts', 'categories', 'recentOrder'));
    }
}
