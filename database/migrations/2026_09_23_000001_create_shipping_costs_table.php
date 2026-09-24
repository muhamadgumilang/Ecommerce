<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipping_costs', function (Blueprint $table) {
            $table->id('shipping_cost_id');
            $table->string('origin', 10)->comment('Kode asal (city_id)');
            $table->string('destination', 10)->comment('Kode tujuan (city_id)');
            $table->string('courier', 10)->comment('Jasa pengiriman: jne, tiki, pos)');
            $table->string('service', 30)->comment('Layanan: REG, YES, OKE, DST');
            $table->string('description', 100)->nullable();
            $table->integer('cost')->comment('Biaya pengiriman');
            $table->integer('etd')->comment('Estimasi hari pengiriman');
            $table->timestamps();
            $table->unique(['origin', 'destination', 'courier', 'service'], 'shipping_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipping_costs');
    }
};