<?php
// filepath: d:\FREELANCE\boomtale\app\Http\Controllers\ReviewController.php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use App\Models\UserProduct;
use App\Models\User;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show form to create review for purchased product
     */
    public function create(UserProduct $userProduct)
    {
        // Check if user owns this product
        if ($userProduct->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses untuk memberikan review produk ini.');
        }

        // Check if user already reviewed this product
        $existingReview = Review::where('user_id', auth()->id())
            ->where('product_id', $userProduct->product_id)
            ->first();

        if ($existingReview) {
            return redirect()->route('user.reviews.edit', $userProduct)
                ->with('info', 'Anda sudah memberikan review untuk produk ini. Anda dapat mengeditnya.');
        }

        $userProduct->load('product');

        return view('user.reviews.create', compact('userProduct'));
    }

    /**
     * Store review for purchased product
     */
    public function store(Request $request, UserProduct $userProduct)
    {
        // Check ownership
        if ($userProduct->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses untuk memberikan review produk ini.');
        }

        // Check if already reviewed
        $existingReview = Review::where('user_id', auth()->id())
            ->where('product_id', $userProduct->product_id)
            ->first();

        if ($existingReview) {
            return back()->with('error', 'Anda sudah memberikan review untuk produk ini.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000'
        ]);

        Review::create([
            'user_id' => auth()->id(),
            'product_id' => $userProduct->product_id,
            'rating' => $request->rating,
            'comment' => $request->comment
        ]);

        return redirect()->route('user.orders.index')
            ->with('success', 'Review berhasil ditambahkan. Terima kasih atas feedback Anda!');
    }

    /**
     * Show form to edit review
     */
    public function edit(UserProduct $userProduct)
    {
        // Check ownership
        if ($userProduct->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit review produk ini.');
        }

        $review = Review::where('user_id', auth()->id())
            ->where('product_id', $userProduct->product_id)
            ->firstOrFail();

        $userProduct->load('product');

        return view('user.reviews.edit', compact('userProduct', 'review'));
    }

    /**
     * Update review
     */
    public function update(Request $request, UserProduct $userProduct)
    {
        // Check ownership
        if ($userProduct->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit review produk ini.');
        }

        $review = Review::where('user_id', auth()->id())
            ->where('product_id', $userProduct->product_id)
            ->firstOrFail();

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000'
        ]);

        $review->update([
            'rating' => $request->rating,
            'comment' => $request->comment
        ]);

        return redirect()->route('user.orders.index')
            ->with('success', 'Review berhasil diperbarui.');
    }

    /**
     * Delete review
     */
    public function destroy(Review $review)
    {
        if ($review->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus review ini.');
        }

        $review->delete();

        return back()->with('success', 'Review berhasil dihapus.');
    }

    // ==================== ADMIN METHODS ====================

    /**
     * Display all reviews for admin
     */
    public function adminIndex(Request $request)
    {
        $reviews = Review::with(['product:id,name', 'user:id,name'])
            ->when($request->product_id, function ($query, $productId) {
                $query->where('product_id', $productId);
            })
            ->when($request->rating, function ($query, $rating) {
                $query->where('rating', $rating);
            })
            ->when($request->search, function ($query, $search) {
                $query->whereHas('product', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })->orWhereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })->orWhere('comment', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $products = Product::select('id', 'name')->get();

        $stats = [
            'total_reviews' => Review::count(),
            'average_rating' => round(Review::avg('rating') ?? 0, 1),
            'five_star' => Review::where('rating', 5)->count(),
            'one_star' => Review::where('rating', 1)->count(),
            'this_month' => Review::whereMonth('created_at', now()->month)->count(),
        ];

        return view('admin.reviews.index', compact('reviews', 'products', 'stats'));
    }

    /**
     * Show single review detail for admin
     */
    public function adminShow(Review $review)
    {
        $review->load(['product', 'user']);
        return view('admin.reviews.show', compact('review'));
    }

    /**
     * Delete review (admin only)
     */
    public function adminDestroy(Review $review)
    {
        $review->delete();
        return back()->with('success', 'Review berhasil dihapus.');
    }

    /**
     * Bulk action for reviews (admin only)
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete',
            'reviews' => 'required|array',
            'reviews.*' => 'exists:reviews,id'
        ]);

        $reviewIds = $request->reviews;

        switch ($request->action) {
            case 'delete':
                Review::whereIn('id', $reviewIds)->delete();
                $message = count($reviewIds) . ' review berhasil dihapus.';
                break;
        }

        return response()->json(['success' => true, 'message' => $message]);
    }
}
