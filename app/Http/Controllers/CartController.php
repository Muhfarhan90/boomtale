<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::with('product')
            ->where('user_id', auth()->id())
            ->get();

        $total = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity ?? 1;
        });
        $count = $cartItems->count();
        return view('cart.index', compact('cartItems', 'total', 'count'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'nullable|integer|min:1|max:10'
        ]);

        $product = Product::findOrFail($request->product_id);

        // Check if product is active
        if (!$product->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak tersedia'
            ]);
        }

        // Check if product is already owned
        if (auth()->user()->hasPurchasedProduct($product->id)) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah memiliki produk ini'
            ]);
        }

        // Check if already in cart
        $existingCart = Cart::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->first();

        if ($existingCart) {
            // Update quantity if already in cart
            $newQuantity = $existingCart->quantity + ($request->quantity ?? 1);
            $maxQuantity = $product->type === 'digital' ? 1 : 10;

            if ($newQuantity > $maxQuantity) {
                return response()->json([
                    'success' => false,
                    'message' => $product->type === 'digital'
                        ? 'Produk digital hanya bisa dibeli 1 kali'
                        : "Maksimal $maxQuantity item per produk"
                ]);
            }

            $existingCart->update(['quantity' => $newQuantity]);

            return response()->json([
                'success' => true,
                'message' => 'Kuantitas produk di keranjang berhasil diperbarui'
            ]);
        }

        // For digital products, quantity is always 1
        $quantity = $product->type === 'digital' ? 1 : ($request->quantity ?? 1);

        Cart::create([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
            'quantity' => $quantity,
            'price' => $product->price
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil ditambahkan ke keranjang'
        ]);
    }

    public function update(Request $request, Cart $cart)
    {
        // Verify cart belongs to current user
        if ($cart->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }

        $request->validate([
            'quantity' => 'required|integer|min:1|max:10'
        ]);

        // Digital products can't have quantity more than 1
        if ($cart->product->type === 'digital' && $request->quantity > 1) {
            return response()->json([
                'success' => false,
                'message' => 'Produk digital hanya bisa dibeli 1 kali'
            ]);
        }

        $cart->update([
            'quantity' => $request->quantity
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kuantitas berhasil diperbarui'
        ]);
    }

    public function remove(Cart $cart)
    {
        // Verify cart belongs to current user
        if ($cart->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }

        $cart->delete();

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil dihapus dari keranjang'
        ]);
    }

    public function clear()
    {
        Cart::where('user_id', auth()->id())->delete();

        return redirect()->back()->with('success', 'Keranjang berhasil dikosongkan');
    }

    public function count()
    {
        $count = Cart::where('user_id', auth()->id())->count();
        return response()->json(['count' => $count]);
    }
}
