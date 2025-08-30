<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Tipe produk: digital atau fisik
            $table->enum('type', ['digital', 'physical'])->default('digital')->after('category_id');

            // Gambar Utama (Featured Image)
            $table->string('featured_image')->nullable()->after('type');

            // Galeri Gambar (Multiple Images)
            $table->json('gallery_images')->nullable()->after('featured_image');

            // Path ke file digital yang aman
            $table->string('digital_file_path')->nullable()->after('gallery_images');

            // Stok untuk produk fisik
            $table->integer('stock')->nullable()->after('digital_file_path');

            // Flag untuk produk unggulan
            $table->boolean('is_featured')->default(false)->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'type',
                'featured_image',
                'gallery_images',
                'digital_file_path',
                'stock',
                'is_featured'
            ]);
        });
    }
};
