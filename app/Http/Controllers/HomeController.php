<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the application home page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Get latest 8 products
        $latestProducts = Product::with(['category'])
            ->where('is_active', true)
            ->latest()
            ->limit(8)
            ->get();

        // Get all categories with product count
        $categories = Category::withCount('products')
            ->where('is_active', true)
            ->get();

        return view('home', compact('latestProducts', 'categories'));
    }

    /**
     * Show the user home page (same as index but with different route name)
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function userHome()
    {
        // Get latest 8 products
        $latestProducts = Product::with(['category'])
            ->where('is_active', true)
            ->latest()
            ->limit(8)
            ->get();

        // Get all categories with product count
        $categories = Category::withCount('products')
            ->where('is_active', true)
            ->get();

        // Get featured/popular products (you can modify this logic)
        $featuredProducts = Product::with(['category'])
            ->where('is_active', true)
            ->where('is_featured', true) // assuming you have this field
            ->limit(4)
            ->get();

        // Get statistics for dashboard
        $stats = [
            'total_products' => Product::where('is_active', true)->count(),
            'total_categories' => Category::where('is_active', true)->count(),
            'latest_products_count' => $latestProducts->count(),
        ];

        return view('user.home', compact('latestProducts', 'categories', 'featuredProducts', 'stats'));
    }

    /**
     * Search products from home page
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function search(Request $request)
    {
        $search = $request->get('search');

        return redirect()->route('user.products.index', ['search' => $search]);
    }
}
