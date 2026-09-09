<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id('payment_id');
            $table->foreignId('order_id')->unique()->constrained('orders', 'order_id')->onDelete('cascade');
            $table->foreignId('admin_id')->nullable()->constrained('users', 'user_id')->onDelete('set null');
            $table->string('payment_method', 50);
            $table->enum('payment_status', ['Pending', 'Verified', 'Failed'])->default('Pending');
            $table->timestamp('payment_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};