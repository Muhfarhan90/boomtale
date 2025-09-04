<?php
// filepath: d:\FREELANCE\boomtale\app\Http\Controllers\ReviewController.php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use App\Models\Review;
use App\Models\Product;
use App\Models\UserProduct;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Show form to create review for purchased product
     */
    public function create(OrderItem $orderItem)
    {
        // PERBAIKI LOGIKA: Cek kepemilikan melalui relasi order
        if ($orderItem->order->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk memberikan review produk ini.');
        }

        // Cek apakah item ini sudah pernah direview
        if ($orderItem->review) {
            return redirect()->route('user.reviews.edit', $orderItem)
                ->with('info', 'Anda sudah memberikan review untuk produk ini. Anda dapat mengeditnya.');
        }

        $orderItem->load('product');

        return view('reviews.create', compact('orderItem'));
    }

    /**
     * Store review for purchased product
     */
    public function store(Request $request, OrderItem $orderItem)
    {
        // PERBAIKI LOGIKA: Cek kepemilikan melalui relasi order
        if ($orderItem->order->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk memberikan review produk ini.');
        }

        if ($orderItem->review) {
            return back()->with('error', 'Anda sudah memberikan review untuk produk ini.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000'
        ]);

        // Simpan review dengan relasi yang benar
        $orderItem->review()->create([
            'user_id' => Auth::id(),
            'product_id' => $orderItem->product_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->route('user.orders.index')
            ->with('success', 'Review berhasil ditambahkan. Terima kasih atas feedback Anda!');
    }

    /**
     * Show form to edit review
     */
    public function edit(OrderItem $orderItem)
    {
        if ($orderItem->order->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit review ini.');
        }

        // Ambil review melalui relasi, atau gagal jika tidak ada
        $review = $orderItem->review()->firstOrFail();
        $orderItem->load('product');

        return view('reviews.edit', compact('orderItem', 'review'));
    }

    /**
     * Update review
     */
    public function update(Request $request, OrderItem $orderItem)
    {
        if ($orderItem->order->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit review ini.');
        }

        $review = $orderItem->review()->firstOrFail();

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000'
        ]);

        $review->update($request->only('rating', 'comment'));

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
