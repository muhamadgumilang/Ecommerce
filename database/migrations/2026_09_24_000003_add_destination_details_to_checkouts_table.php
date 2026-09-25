<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('checkouts', function (Blueprint $table) {
            $table->string('destination_regency', 100)->nullable()->after('shipping_address');
            $table->string('destination_district', 100)->nullable()->after('destination_regency');
            $table->string('destination_village', 100)->nullable()->after('destination_district');
            $table->string('postal_code', 10)->nullable()->after('destination_village');
        });
    }

    public function down(): void
    {
        Schema::table('checkouts', function (Blueprint $table) {
            $table->dropColumn([
                'destination_regency',
                'destination_district',
                'destination_village',
                'postal_code',
            ]);
        });
    }
};
