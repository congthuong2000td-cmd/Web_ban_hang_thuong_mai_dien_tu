<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function index(Request $request): View
    {
        $query = Review::with(['user', 'product'])->latest();

        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        if ($request->filled('status')) {
            $query->where('is_visible', $request->status === 'visible');
        }

        $reviews = $query->paginate(15)->withQueryString();

        return view('admin.reviews.index', compact('reviews'));
    }

    public function approve(Review $review): RedirectResponse
    {
        $review->update(['is_visible' => ! $review->is_visible]);

        // Tính lại rating trung bình cho sản phẩm
        $product = $review->product;
        if ($product) {
            $avg = $product->reviews()->where('is_visible', true)->avg('rating') ?? 5.0;
            $count = $product->reviews()->where('is_visible', true)->count();
            $product->update([
                'rating_avg'   => round($avg, 1),
                'review_count' => $count,
            ]);
        }

        $msg = $review->is_visible ? 'Đã duyệt hiển thị đánh giá!' : 'Đã ẩn đánh giá!';

        return back()->with('success', $msg);
    }

    public function destroy(Review $review): RedirectResponse
    {
        $product = $review->product;
        $review->delete();

        if ($product) {
            $avg = $product->reviews()->where('is_visible', true)->avg('rating') ?? 5.0;
            $count = $product->reviews()->where('is_visible', true)->count();
            $product->update([
                'rating_avg'   => round($avg, 1),
                'review_count' => $count,
            ]);
        }

        return back()->with('success', 'Đã xóa đánh giá thành công!');
    }
}
