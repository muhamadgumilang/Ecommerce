<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id('order_id');
            $table->foreignId('customer_id')->constrained('users', 'user_id')->onDelete('cascade');
            $table->timestamp('order_date')->useCurrent();
            $table->decimal('total_amount', 12, 2);
            $table->enum('order_status', ['Pending Payment', 'Processing', 'Shipped', 'Completed', 'Cancelled'])->default('Pending Payment');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};