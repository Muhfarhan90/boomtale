<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;

class OrderController extends Controller
{
    /**
     * Display a listing of orders for the authenticated user
     */
    public function index(Request $request)
    {
        $query = Order::with(['orderItems.product', 'transaction'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search by invoice number
        if ($request->filled('search')) {
            $query->where('invoice_number', 'like', '%' . $request->search . '%');
        }

        $orders = $query->paginate(10);

        return view('orders.index', compact('orders'));
    }

    /**
     * Show the order details
     */
    public function show(Order $order)
    {
        // Check if order belongs to authenticated user
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this order.');
        }

        $order->load(['orderItems.product', 'transaction']);

        return view('orders.show', compact('order'));
    }

    /**
     * Create order from cart
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // Get cart items for authenticated user
            $cartItems = Cart::with('product')
                ->where('user_id', Auth::id())
                ->get();

            if ($cartItems->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Keranjang belanja kosong'
                ], 400);
            }

            // Validate cart items
            foreach ($cartItems as $cartItem) {
                if (!$cartItem->product->is_active) {
                    return response()->json([
                        'success' => false,
                        'message' => "Produk {$cartItem->product->name} tidak tersedia"
                    ], 400);
                }

                // Check if digital product already owned
                if (
                    $cartItem->product->type === 'digital' &&
                    Auth::user()->hasPurchasedProduct($cartItem->product->id)
                ) {
                    return response()->json([
                        'success' => false,
                        'message' => "Anda sudah memiliki produk {$cartItem->product->name}"
                    ], 400);
                }
            }

            // Calculate total amount
            $totalAmount = $cartItems->sum(function ($item) {
                return $item->product->price * $item->quantity;
            });

            // Create order
            $order = Order::create([
                'invoice_number' => $this->generateInvoiceNumber(),
                'user_id' => Auth::id(),
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'expired_at' => Carbon::now()->addHours(24), // 24 hours to complete payment
            ]);

            // Create order items
            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product->id,
                    'product_name' => $cartItem->product->name,
                    'price' => $cartItem->product->price,
                    'quantity' => $cartItem->quantity,
                ]);
            }

            // Clear cart after creating order
            Cart::where('user_id', Auth::id())->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order berhasil dibuat',
                'order_id' => $order->id,
                'redirect_url' => route('orders.show', $order)
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating order: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat order. Silakan coba lagi.'
            ], 500);
        }
    }

    /**
     * Cancel order
     */
    public function cancel(Order $order)
    {
        // Check if order belongs to authenticated user
        if ($order->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }

        // Check if order can be cancelled
        if (!in_array($order->status, ['pending', 'waiting_payment'])) {
            return response()->json([
                'success' => false,
                'message' => 'Order tidak dapat dibatalkan'
            ], 400);
        }

        try {
            DB::beginTransaction();

            // Update order status
            $order->update(['status' => 'cancelled']);

            // Update transaction status if exists
            if ($order->transaction) {
                $order->transaction->update(['status' => 'cancelled']);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order berhasil dibatalkan'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error cancelling order: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal membatalkan order'
            ], 500);
        }
    }

    /**
     * Reorder - add order items back to cart
     */
    public function reorder(Order $order)
    {
        // Check if order belongs to authenticated user
        if ($order->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }

        try {
            DB::beginTransaction();

            $addedItems = 0;
            $skippedItems = [];

            foreach ($order->orderItems as $orderItem) {
                $product = $orderItem->product;

                // Check if product is still available
                if (!$product || !$product->is_active) {
                    $skippedItems[] = $orderItem->product_name . ' (tidak tersedia)';
                    continue;
                }

                // Check if digital product already owned
                if (
                    $product->type === 'digital' &&
                    Auth::user()->hasPurchasedProduct($product->id)
                ) {
                    $skippedItems[] = $orderItem->product_name . ' (sudah dimiliki)';
                    continue;
                }

                // Check if already in cart
                $existingCart = Cart::where('user_id', Auth::id())
                    ->where('product_id', $product->id)
                    ->first();

                if ($existingCart) {
                    $skippedItems[] = $orderItem->product_name . ' (sudah di keranjang)';
                    continue;
                }

                // Add to cart
                Cart::create([
                    'user_id' => Auth::id(),
                    'product_id' => $product->id,
                    'quantity' => $orderItem->quantity,
                ]);

                $addedItems++;
            }

            DB::commit();

            $message = $addedItems > 0
                ? "Berhasil menambahkan $addedItems produk ke keranjang"
                : 'Tidak ada produk yang dapat ditambahkan ke keranjang';

            if (!empty($skippedItems)) {
                $message .= '. Produk yang dilewati: ' . implode(', ', $skippedItems);
            }

            return response()->json([
                'success' => $addedItems > 0,
                'message' => $message,
                'added_items' => $addedItems,
                'skipped_items' => $skippedItems
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error reordering: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan ke keranjang'
            ], 500);
        }
    }

    /**
     * Download digital product
     */
    public function download(Order $order, OrderItem $orderItem)
    {
        // Check if order belongs to authenticated user
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }

        // Check if order item belongs to the order
        if ($orderItem->order_id !== $order->id) {
            abort(403, 'Invalid order item');
        }

        // Check if order is completed
        if ($order->status !== 'completed') {
            abort(403, 'Order belum selesai. Download tidak tersedia.');
        }

        // Check if product is digital
        if ($orderItem->product->type !== 'digital') {
            abort(403, 'Produk ini bukan produk digital');
        }

        // Check if product has download file
        if (!$orderItem->product->download_file) {
            abort(404, 'File download tidak tersedia');
        }

        // Log download activity
        Log::info('Digital product downloaded', [
            'user_id' => Auth::id(),
            'order_id' => $order->id,
            'product_id' => $orderItem->product->id,
            'product_name' => $orderItem->product->name
        ]);

        // Return download response
        $filePath = storage_path('app/' . $orderItem->product->download_file);

        if (!file_exists($filePath)) {
            abort(404, 'File tidak ditemukan');
        }

        return response()->download($filePath, $orderItem->product->name . '.' . pathinfo($filePath, PATHINFO_EXTENSION));
    }

    /**
     * Get order statistics for user dashboard
     */
    public function getStatistics()
    {
        $userId = Auth::id();

        $stats = [
            'total_orders' => Order::where('user_id', $userId)->count(),
            'pending_orders' => Order::where('user_id', $userId)->where('status', 'pending')->count(),
            'completed_orders' => Order::where('user_id', $userId)->where('status', 'completed')->count(),
            'total_spent' => Order::where('user_id', $userId)
                ->where('status', 'completed')
                ->sum('total_amount'),
        ];

        return response()->json($stats);
    }

    /**
     * Admin: Display all orders
     */
    public function adminIndex(Request $request)
    {
        $this->authorize('admin');

        $query = Order::with(['user', 'orderItems.product', 'transaction'])
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search by invoice number or user email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', '%' . $search . '%')
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('email', 'like', '%' . $search . '%')
                            ->orWhere('name', 'like', '%' . $search . '%');
                    });
            });
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Admin: Show order details
     */
    public function adminShow(Order $order)
    {
        $this->authorize('admin');

        $order->load(['user', 'orderItems.product', 'transaction']);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Admin: Update order status
     */
    public function updateStatus(Request $request, Order $order)
    {
        $this->authorize('admin');

        $request->validate([
            'status' => 'required|in:pending,waiting_payment,processing,shipped,completed,cancelled'
        ]);

        try {
            DB::beginTransaction();

            $oldStatus = $order->status;
            $newStatus = $request->status;

            // Update order status
            $order->update(['status' => $newStatus]);

            // If order is completed, handle digital product access
            if ($newStatus === 'completed' && $oldStatus !== 'completed') {
                // Add digital products to user's library (if you have this feature)
                foreach ($order->orderItems as $orderItem) {
                    if ($orderItem->product->type === 'digital') {
                        // You can implement user library logic here
                        // For example: UserProduct::create(['user_id' => $order->user_id, 'product_id' => $orderItem->product_id]);
                    }
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Status order berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating order status: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui status order'
            ], 500);
        }
    }

    /**
     * Generate unique invoice number
     */
    private function generateInvoiceNumber()
    {
        $date = Carbon::now()->format('Ymd');
        $randomString = strtoupper(Str::random(6));

        do {
            $invoiceNumber = "INV-{$date}-{$randomString}";
            $exists = Order::where('invoice_number', $invoiceNumber)->exists();

            if ($exists) {
                $randomString = strtoupper(Str::random(6));
            }
        } while ($exists);

        return $invoiceNumber;
    }
}
