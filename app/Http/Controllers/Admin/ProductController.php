<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::with(['category', 'images'])->orderByDesc('created_at');

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('stock_status')) {
            if ($request->stock_status === 'out') {
                $query->where('stock_quantity', '<=', 0);
            } elseif ($request->stock_status === 'low') {
                $query->where('stock_quantity', '>', 0)->where('stock_quantity', '<=', 5);
            } elseif ($request->stock_status === 'in') {
                $query->where('stock_quantity', '>', 5);
            }
        }

        $products = $query->paginate(15)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create(): View
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'slug'           => ['nullable', 'string', 'max:255', 'unique:products,slug'],
            'category_id'    => ['required', 'exists:categories,id'],
            'sku'            => ['nullable', 'string', 'max:100', 'unique:products,sku'],
            'price'          => ['required', 'integer', 'min:0'],
            'original_price' => ['nullable', 'integer', 'min:0'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'description'    => ['nullable', 'string'],
            'is_active'      => ['nullable', 'boolean'],
            'is_featured'    => ['nullable', 'boolean'],
            'thumbnail'      => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:4096'],
            'gallery.*'      => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:4096'],
        ], [
            'name.required'        => 'Vui lòng nhập tên sản phẩm.',
            'category_id.required' => 'Vui lòng chọn danh mục.',
            'price.required'       => 'Vui lòng nhập giá bán.',
            'stock_quantity.required' => 'Vui lòng nhập số lượng tồn kho.',
            'sku.unique'           => 'Mã SKU đã tồn tại.',
        ]);

        $slug = $validated['slug'] ? Str::slug($validated['slug']) : Str::slug($validated['name']);
        if (Product::where('slug', $slug)->exists()) {
            $slug .= '-' . Str::random(4);
        }

        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('products', 'public');
        }

        $product = Product::create([
            'category_id'    => $validated['category_id'],
            'name'           => $validated['name'],
            'slug'           => $slug,
            'sku'            => $validated['sku'] ?: 'SKU-' . strtoupper(Str::random(6)),
            'price'          => $validated['price'],
            'original_price' => $validated['original_price'] ?: null,
            'stock_quantity' => $validated['stock_quantity'],
            'description'    => $validated['description'],
            'thumbnail'      => $thumbnailPath,
            'is_active'      => $request->boolean('is_active', true),
            'is_featured'    => $request->boolean('is_featured', false),
        ]);

        // Upload gallery images
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $index => $file) {
                $path = $file->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'sort_order' => $index,
                ]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Thêm sản phẩm mới thành công!');
    }

    public function edit(Product $product): View
    {
        $product->load('images');
        $categories = Category::orderBy('name')->get();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'slug'           => ['nullable', 'string', 'max:255', 'unique:products,slug,' . $product->id],
            'category_id'    => ['required', 'exists:categories,id'],
            'sku'            => ['nullable', 'string', 'max:100', 'unique:products,sku,' . $product->id],
            'price'          => ['required', 'integer', 'min:0'],
            'original_price' => ['nullable', 'integer', 'min:0'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'description'    => ['nullable', 'string'],
            'is_active'      => ['nullable', 'boolean'],
            'is_featured'    => ['nullable', 'boolean'],
            'thumbnail'      => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:4096'],
            'gallery.*'      => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:4096'],
        ]);

        $slug = $validated['slug'] ? Str::slug($validated['slug']) : Str::slug($validated['name']);

        $thumbnailPath = $product->thumbnail;
        if ($request->hasFile('thumbnail')) {
            if ($product->thumbnail && Storage::disk('public')->exists($product->thumbnail)) {
                Storage::disk('public')->delete($product->thumbnail);
            }
            $thumbnailPath = $request->file('thumbnail')->store('products', 'public');
        }

        $product->update([
            'category_id'    => $validated['category_id'],
            'name'           => $validated['name'],
            'slug'           => $slug,
            'sku'            => $validated['sku'] ?: $product->sku,
            'price'          => $validated['price'],
            'original_price' => $validated['original_price'] ?: null,
            'stock_quantity' => $validated['stock_quantity'],
            'description'    => $validated['description'],
            'thumbnail'      => $thumbnailPath,
            'is_active'      => $request->boolean('is_active', true),
            'is_featured'    => $request->boolean('is_featured', false),
        ]);

        // Upload new gallery images
        if ($request->hasFile('gallery')) {
            $currentMaxOrder = $product->images()->max('sort_order') ?? 0;
            foreach ($request->file('gallery') as $index => $file) {
                $path = $file->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'sort_order' => $currentMaxOrder + $index + 1,
                ]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Cập nhật sản phẩm thành công!');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Đã chuyển sản phẩm vào thùng rác!');
    }

    public function destroyImage(Product $product, ProductImage $image): RedirectResponse
    {
        if ($image->product_id !== $product->id) {
            abort(403);
        }

        if (Storage::disk('public')->exists($image->image_path)) {
            Storage::disk('public')->delete($image->image_path);
        }

        $image->delete();

        return back()->with('success', 'Xóa ảnh thành công!');
    }
}
