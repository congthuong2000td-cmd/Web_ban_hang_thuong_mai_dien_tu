<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function show(string $slug): View
    {
        $category = Category::where('slug', $slug)->where('is_active', true)->firstOrFail();

        $products = Product::with(['category', 'images'])
            ->active()
            ->where('category_id', $category->id)
            ->orderByDesc('created_at')
            ->paginate(20);

        $categories = Category::where('is_active', true)->orderBy('name')->get();

        return view('categories.show', compact('category', 'products', 'categories'));
    }
}
