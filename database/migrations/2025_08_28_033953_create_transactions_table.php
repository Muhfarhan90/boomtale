<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->string('external_order_id', 50)->unique(); // Order ID yang dikirim ke Midtrans
            $table->string('transaction_id', 100)->nullable();
            $table->text('snap_token')->nullable(); // Token untuk popup payment
            $table->decimal('gross_amount', 12, 2);
            $table->enum('status', ['pending', 'settlement', 'deny', 'cancel', 'expire', 'failure'])->default('pending');
            $table->string('payment_type')->nullable(); // credit_card, bank_transfer, dll
            $table->timestamps();

            $table->index(['external_order_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
