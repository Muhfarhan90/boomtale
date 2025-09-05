<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistics dengan data dummy/default jika tabel belum ada
        try {
            $totalUsers = User::where('role', 'user')->count();
        } catch (\Exception $e) {
            $totalUsers = 0;
        }

        try {
            $totalProducts = \DB::table('products')->where('is_active', true)->count();
        } catch (\Exception $e) {
            $totalProducts = 0;
        }

        try {
            $totalOrders = \DB::table('orders')->count();
        } catch (\Exception $e) {
            $totalOrders = 0;
        }

        try {
            $totalRevenue = \DB::table('orders')->where('status', 'completed')->sum('total_amount');
        } catch (\Exception $e) {
            $totalRevenue = 0;
        }

        // Recent orders (dummy data jika tabel belum ada)
        try {
            $recentOrders = \DB::table('orders')
                ->join('users', 'orders.user_id', '=', 'users.id')
                ->select('orders.*', 'users.name as user_name')
                ->orderBy('orders.created_at', 'desc')
                ->limit(5)
                ->get();
        } catch (\Exception $e) {
            $recentOrders = collect([
                (object)[
                    'invoice_number' => 'INV-001',
                    'user_name' => 'Demo User',
                    'total_amount' => 50000,
                    'status' => 'pending',
                    'created_at' => now()
                ],
                (object)[
                    'invoice_number' => 'INV-002',
                    'user_name' => 'Test User',
                    'total_amount' => 75000,
                    'status' => 'paid',
                    'created_at' => now()->subHour()
                ]
            ]);
        }

        // Popular products (dummy data jika tabel belum ada)
        try {
            $popularProducts = \DB::table('products')
                ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
                ->select('products.*', \DB::raw('COUNT(order_items.id) as order_items_count'))
                ->groupBy('products.id')
                ->orderBy('order_items_count', 'desc')
                ->limit(5)
                ->get();
        } catch (\Exception $e) {
            $popularProducts = collect([
                (object)[
                    'name' => 'Laravel eBook Premium',
                    'thumbnail' => null,
                    'order_items_count' => 25
                ],
                (object)[
                    'name' => 'React Video Tutorial',
                    'thumbnail' => null,
                    'order_items_count' => 18
                ]
            ]);
        }

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalProducts',
            'totalOrders',
            'totalRevenue',
            'recentOrders',
            'popularProducts'
        ));
    }

    public function profile()
    {
        return view('admin.profile');
    }
}
