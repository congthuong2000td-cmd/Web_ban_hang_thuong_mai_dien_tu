<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(Request $request): View
    {
        $query = Category::withCount('products')->orderByDesc('created_at');

        if ($request->filled('q')) {
            $query->where('name', 'like', '%' . $request->q . '%');
        }

        $categories = $query->paginate(15)->withQueryString();

        return view('admin.categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('admin.categories.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'slug'        => ['nullable', 'string', 'max:255', 'unique:categories,slug'],
            'description' => ['nullable', 'string'],
            'is_active'   => ['nullable', 'boolean'],
        ], [
            'name.required' => 'Vui lòng nhập tên danh mục.',
            'slug.unique'   => 'Đường dẫn (slug) này đã tồn tại.',
        ]);

        $slug = $validated['slug'] ? Str::slug($validated['slug']) : Str::slug($validated['name']);

        Category::create([
            'name'        => $validated['name'],
            'slug'        => $slug,
            'description' => $validated['description'],
            'is_active'   => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Thêm danh mục mới thành công!');
    }

    public function edit(Category $category): View
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'slug'        => ['nullable', 'string', 'max:255', 'unique:categories,slug,' . $category->id],
            'description' => ['nullable', 'string'],
            'is_active'   => ['nullable', 'boolean'],
        ], [
            'name.required' => 'Vui lòng nhập tên danh mục.',
            'slug.unique'   => 'Đường dẫn (slug) này đã tồn tại.',
        ]);

        $slug = $validated['slug'] ? Str::slug($validated['slug']) : Str::slug($validated['name']);

        $category->update([
            'name'        => $validated['name'],
            'slug'        => $slug,
            'description' => $validated['description'],
            'is_active'   => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Cập nhật danh mục thành công!');
    }

    public function destroy(Category $category): RedirectResponse
    {
        if ($category->products()->count() > 0) {
            return back()->with('error', 'Không thể xóa danh mục đang có sản phẩm gắn liền. Hãy chuyển sản phẩm sang danh mục khác trước.');
        }

        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Xóa danh mục thành công!');
    }
}
