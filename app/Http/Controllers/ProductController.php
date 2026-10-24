<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::with(['category', 'images'])->active();

        // Lọc theo danh mục
        if ($request->filled('category')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }

        // Tìm kiếm
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        // Lọc theo giá
        if ($request->filled('min_price')) {
            $query->where('price', '>=', (int) $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', (int) $request->max_price);
        }

        // Lọc hàng còn
        if ($request->boolean('in_stock')) {
            $query->where('stock_quantity', '>', 0);
        }

        // Sắp xếp
        match ($request->get('sort', 'newest')) {
            'price_asc'  => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            'name_asc'   => $query->orderBy('name'),
            'popular'    => $query->orderByDesc('review_count'),
            default      => $query->orderByDesc('created_at'),
        };

        $products = $query->paginate(20)->withQueryString();
        $categories = Category::where('is_active', true)->orderBy('name')->get();

        if ($request->ajax()) {
            return view('products.partials.grid', compact('products', 'categories'));
        }

        return view('products.index', compact('products', 'categories'));
    }

    public function show(string $slug): View
    {
        $product = Product::with(['category', 'images', 'reviews.user'])
            ->where('slug', $slug)
            ->active()
            ->firstOrFail();

        // Sản phẩm liên quan
        $relatedProducts = Product::with(['category', 'images'])
            ->active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->inRandomOrder()
            ->take(6)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }
}
