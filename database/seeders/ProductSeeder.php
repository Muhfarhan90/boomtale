<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ebookFiksiCategory = Category::where('slug', 'ebook-fiksi')->first();
        $videoCategory = Category::where('slug', 'video')->first();

        $products = [
            [
                'name' => 'PERSPECTIVE - Eric F Scott',
                'category_id' => $ebookFiksiCategory->id,
                'description' => 'Buku panduan lengkap tentang teknik menggambar perspektif oleh Eric F Scott.',
                'price' => 55000,
                'discount_price' => 40000,
                'type' => 'digital',
                'stock' => 1,
                'is_active' => true,
                'is_featured' => true,
            ],
            [
                'name' => 'Pengenalan Karakter - Nataga The Little Dragon',
                'category_id' => $videoCategory->id,
                'description' => 'Video pengenalan karakter untuk seri Nataga The Little Dragon.',
                'price' => 8500,
                'discount_price' => 6000,
                'type' => 'digital',
                'stock' => 1,
                'is_active' => true,
                'is_featured' => true,
            ],
            [
                'name' => 'Episode 1 - Nataga The Little Dragon',
                'category_id' => $videoCategory->id,
                'description' => 'Episode pertama dari seri video Nataga The Little Dragon.',
                'price' => 11000,
                'discount_price' => 7000,
                'type' => 'digital',
                'stock' => 0,
                'is_active' => true,
                'is_featured' => false,
            ],
            [
                'name' => 'Episode 2 - Nataga The Little Dragon',
                'category_id' => $videoCategory->id,
                'description' => 'Episode kedua dari seri video Nataga The Little Dragon.',
                'price' => 11500,
                'discount_price' => 7500,
                'type' => 'digital',
                'stock' => 0,
                'is_active' => true,
                'is_featured' => false,
            ],
            [
                'name' => 'Episode 3 - Nataga The Little Dragon',
                'category_id' => $videoCategory->id,
                'description' => 'Episode ketiga dari seri video Nataga The Little Dragon.',
                'price' => 15700,
                'discount_price' => 7500,
                'type' => 'digital',
                'stock' => 0,
                'is_active' => true,
                'is_featured' => false,
            ],
        ];

        foreach ($products as $productData) {
            $productData['slug'] = Str::slug($productData['name']);
            Product::create($productData);
        }
    }
}