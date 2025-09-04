<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\UserProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Midtrans\Notification;

class TransactionController extends Controller
{
    // =================================================================
    // USER-FACING METHODS
    // =================================================================

    /**
     * Display the checkout page with cart items and totals.
     */
    public function checkout()
    {
        $cartItems = Cart::with('product')->where('user_id', Auth::id())->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('user.cart.index')->with('error', 'Keranjang belanja Anda kosong.');
        }

        $subtotal = $cartItems->sum(fn($item) => $item->price * $item->quantity);
        // $tax = floor($subtotal * 0.11);

        $tax = 0; // No tax
        $total = $subtotal + $tax;

        return view('checkout.index', compact('cartItems', 'subtotal', 'tax', 'total'));
    }

    /**
     * Process the checkout, create order, and get Midtrans Snap token.
     */
    public function processCheckout(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
        ]);

        $user = Auth::user();
        $cartItems = Cart::with('product')->where('user_id', $user->id)->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['error' => 'Keranjang belanja kosong.'], 400);
        }

        try {
            return DB::transaction(function () use ($user, $cartItems, $request) {
                $currency = $request->currency ?? 'IDR'; // Default to IDR
                $country = $request->country ?? 'ID'; // Default to Indonesia
                // 1. Kalkulasi Total
                $subtotal = $cartItems->sum(fn($item) => $item->price * $item->quantity);
                // if ($country === 'ID') {
                //     $tax = floor($subtotal * 0.11); // PPN 11% only for Indonesia
                // }
                $tax = 0;
                $totalAmount = $subtotal + $tax;

                // 2. Buat Order utama
                $order = Order::create([
                    'user_id' => $user->id,
                    'invoice_number' => 'INV-' . date('Ymd') . '-' . strtoupper(Str::random(6)),
                    'total_amount' => $totalAmount,
                    'status' => 'waiting_payment',
                    'expired_at' => Carbon::now()->addDay(),
                ]);

                // 3. Buat Order Items
                $itemDetails = [];
                foreach ($cartItems as $cartItem) {
                    $order->orderItems()->create([
                        'product_id' => $cartItem->product_id,
                        'product_name' => $cartItem->product->name,
                        'quantity' => $cartItem->quantity,
                        'price' => $cartItem->price,
                    ]);
                    $itemDetails[] = [
                        'id' => $cartItem->product_id,
                        'price' => (int) $cartItem->price,
                        'quantity' => $cartItem->quantity,
                        'name' => Str::limit($cartItem->product->name, 50),
                    ];
                }
                // // COMMENT: Add tax as item detail (only for Indonesia)
                // if ($tax > 0) {
                //     $itemDetails[] = ['id' => 'TAX', 'price' => (int) $tax, 'quantity' => 1, 'name' => 'PPN 11%'];
                // }


                // 4. Konfigurasi Midtrans
                $this->configureMidtrans();

                // 5. Buat payload untuk Midtrans
                $params = [
                    'transaction_details' => [
                        'order_id' => $order->invoice_number,
                        'gross_amount' => (int) $totalAmount,
                    ],
                    'customer_details' => [
                        'first_name' => $request->name,
                        'email' => $request->email,
                        'phone' => $request->phone,
                    ],
                    'item_details' => $itemDetails,
                    // INTERNATIONAL: Enable international cards
                    'credit_card' => [
                        'secure' => true,
                        'channel' => 'migs', // Support international cards
                        'bank' => 'bni', // Can be changed based on your Midtrans setup
                    ],
                ];

                // 6. Dapatkan Snap Token
                $snapToken = \Midtrans\Snap::getSnapToken($params);

                // 7. Buat record Transaction
                $order->transaction()->create([
                    'external_order_id' => $order->invoice_number,
                    'snap_token' => $snapToken,
                    'gross_amount' => $totalAmount,
                    'status' => 'pending',
                    'payment_type' => 'midtrans',
                ]);

                // 8. Hapus keranjang
                Cart::where('user_id', $user->id)->delete();

                return response()->json([
                    'success' => true,
                    'snap_token' => $snapToken,
                    'order_invoice' => $order->invoice_number,
                    'currency' => $currency, // Return currency info
                ]);
            });
        } catch (\Exception $e) {
            Log::error('Checkout Process Error: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display user's order history.
     */
    public function userOrders()
    {
        $orders = Order::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('user.orders.index', compact('orders'));
    }

    /**
     * Show a specific order detail for the user.
     */
    public function showOrder($invoiceNumber)
    {
        $order = Order::with('orderItems.product', 'transaction')
            ->where('invoice_number', $invoiceNumber)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('user.orders.show', compact('order'));
    }

    // =================================================================
    // MIDTRANS WEBHOOK
    // =================================================================

    /**
     * Handle incoming notifications from Midtrans.
     */
    public function handleCallback(Request $request)
    {
        // 1. Konfigurasi Midtrans
        $this->configureMidtrans();

        try {
            // 2. Buat instance notifikasi dari Midtrans
            $notification = new Notification();
            Log::info('Midtrans Notification Received', ['payload' => $notification]);
            // 3. Ambil status dan ID pesanan
            $transactionStatus = $notification->transaction_status;
            $fraudStatus = $notification->fraud_status;
            $invoiceNumber = $notification->order_id;

            // 4. Cari Order berdasarkan invoice number
            $order = Order::with('orderItems')->where('invoice_number', $invoiceNumber)->first();

            if (!$order) {
                Log::warning('Midtrans notification for non-existent order.', ['order_id' => $invoiceNumber]);
                return response()->json(['message' => 'Order not found'], 404);
            }

            // 5. Update status berdasarkan notifikasi (logic ini tetap sama)
            $orderStatus = $order->status;

            if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
                if ($fraudStatus == 'accept' || $fraudStatus == null) {
                    $orderStatus = 'completed';
                }
            } elseif ($transactionStatus == 'pending') {
                $orderStatus = 'waiting_payment';
            } elseif (in_array($transactionStatus, ['deny', 'cancel', 'expire'])) {
                $orderStatus = 'cancelled';
            }

            // 6. Update database dalam satu transaksi
            DB::transaction(function () use ($order, $orderStatus, $notification, $transactionStatus) {
                $order->update(['status' => $orderStatus]);

                if ($order->transaction) {
                    $order->transaction->update([
                        'status' => $transactionStatus,
                        'transaction_id' => $notification->transaction_id,
                        'payment_type' => $notification->payment_type,
                    ]);
                }

                if ($orderStatus === 'completed') {
                    foreach ($order->orderItems as $item) {
                        // Cek untuk menghindari duplikasi jika notifikasi terkirim lebih dari sekali
                        UserProduct::firstOrCreate(
                            [
                                'user_id' => $order->user_id,
                                'product_id' => $item->product_id,
                                'order_id' => $order->id
                            ],
                            [
                                'order_item_id' => $item->id,
                                'purchase_price' => $item->price,
                            ]
                        );
                    }
                }
            });

            return response()->json(['message' => 'Notification processed successfully']);
        } catch (\Exception $e) {
            Log::error('Midtrans Notification Error: ' . $e->getMessage());
            return response()->json(['message' => 'Error processing notification'], 500);
        }
    }

    // =================================================================
    // PRIVATE HELPER METHODS
    // =================================================================

    /**
     * Build the payload for Midtrans Snap API.
     */
    private function configureMidtrans(): void
    {
        \Midtrans\Config::$serverKey = config('midtrans.serverKey');
        \Midtrans\Config::$isProduction = config('midtrans.isProduction');
        \Midtrans\Config::$isSanitized = config('midtrans.isSanitized');
        \Midtrans\Config::$is3ds = config('midtrans.is3ds');
    }
}
