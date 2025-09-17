<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use App\Models\UserProduct;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Faker\Factory as Faker;

class UserProductSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        
        $users = User::where('role', 'user')->get();
        if ($users->count() < 200) {
            User::factory()->count(200 - $users->count())->create();
            $users = User::where('role', 'user')->get();
        }

        $salesData = [
            'pengenalan-karakter-nataga-the-little-dragon' => ['sold' => 70],
            'episode-1-nataga-the-little-dragon' => ['sold' => 188],
            'episode-2-nataga-the-little-dragon' => ['sold' => 175],
            'episode-3-nataga-the-little-dragon' => ['sold' => 167],
            'perspective-eric-f-scott' => ['sold' => 37],
        ];

        foreach ($salesData as $productSlug => $data) {
            $product = Product::where('slug', $productSlug)->first();
            if (!$product) {
                $this->command->warn("⚠️ Product with slug '{$productSlug}' not found. Skipping.");
                continue;
            }

            $soldCount = 0;
            $maxAttempts = $data['sold'] * 3;
            $attempts = 0;
            
            while ($soldCount < $data['sold'] && $attempts < $maxAttempts) {
                $attempts++;
                $randomUser = $users->random();
                
                if (UserProduct::where('user_id', $randomUser->id)->where('product_id', $product->id)->exists()) {
                    continue;
                }

                $purchaseDate = Carbon::now()->subDays(rand(1, 365));
                $price = $product->discount_price ?? $product->price;

                $order = Order::create([
                    'user_id' => $randomUser->id,
                    'invoice_number' => 'BT-' . $purchaseDate->format('ymd') . '-' . strtoupper(Str::random(4)),
                    'total_amount' => $price,
                    'status' => 'completed',
                    'created_at' => $purchaseDate,
                    'updated_at' => $purchaseDate,
                ]);

                $orderItem = OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'quantity' => 1,
                    'price' => $price,
                ]);

                UserProduct::create([
                    'user_id' => $randomUser->id,
                    'product_id' => $product->id,
                    'order_id' => $order->id,
                    'order_item_id' => $orderItem->id,
                    'purchase_price' => $price,
                    'purchased_at' => $purchaseDate,
                ]);

                $soldCount++;
            }
            $this->command->info("✅ Generated {$soldCount} sales for: {$product->name}");
        }
    }
}