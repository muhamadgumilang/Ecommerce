<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('checkouts', function (Blueprint $table) {
            $table->id('checkout_id');
            $table->foreignId('order_id')->unique()->constrained('orders', 'order_id')->onDelete('cascade');
            $table->text('shipping_address');
            $table->string('courier', 50);
            $table->decimal('shipping_fee', 10, 2);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('checkouts');
    }
};