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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->integer('total_amount');
            $table->enum('payment_status', ['pending', 'failed', 'completed'])->default('pending');
            $table->enum('order_status', ['processing', 'shipped', 'delivered'])->default('processing');
            $table->string('transaction_id');
            $table->bigInteger('phone');
            $table->string('address');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
