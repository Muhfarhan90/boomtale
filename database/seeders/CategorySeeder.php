<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Ebook Property',
                'is_active' => true,
            ],
            [
                'name' => 'Ebook Fiksi',
                'is_active' => true,
            ],
            [
                'name' => 'Video',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $categoryData) {
            $categoryData['slug'] = Str::slug($categoryData['name']);
            Category::create($categoryData);
        }
    }
}
