<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Mengubah kolom 'status' dari ENUM menjadi VARCHAR yang lebih fleksibel
     * dan menggunakan nilai yang lebih deskriptif.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Mengubah tipe kolom menjadi string dengan panjang 50
            // dan mengatur nilai default baru.
            $table->string('status', 50)->default('waiting_payment')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * Mengembalikan kolom 'status' ke definisi ENUM aslinya.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('status', ['pending', 'paid', 'failed', 'cancelled', 'expired'])
                ->default('pending')->change();
        });
    }
};
