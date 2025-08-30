<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::with('product')
            ->where('user_id', auth()->id())
            ->get();

        $total = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        return view('user.cart.index', compact('cartItems', 'total'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $product = Product::findOrFail($request->product_id);

        // Check if product is already owned
        if (auth()->user()->ownedProducts()->where('product_id', $product->id)->exists()) {
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
            return response()->json([
                'success' => false,
                'message' => 'Produk sudah ada di keranjang'
            ]);
        }

        Cart::create([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
            'quantity' => 1
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil ditambahkan ke keranjang'
        ]);
    }

    public function remove($id)
    {
        Cart::where('user_id', auth()->id())
            ->where('id', $id)
            ->delete();

        return redirect()->back()->with('success', 'Produk dihapus dari keranjang');
    }

    public function count()
    {
        $count = Cart::where('user_id', auth()->id())->count();
        return response()->json(['count' => $count]);
    }
}
