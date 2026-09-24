<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->unsignedBigInteger('owner_id')->nullable()->after('category_id');
            $table->foreign('owner_id')->references('user_id')->on('users')->cascadeOnDelete();
            $table->index('owner_id');
        });

        $adminId = DB::table('users')->where('role', 'Admin')->value('user_id');
        if ($adminId) {
            DB::table('categories')->whereNull('owner_id')->update(['owner_id' => $adminId]);
        }
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropForeign(['owner_id']);
            $table->dropIndex(['owner_id']);
            $table->dropColumn('owner_id');
        });
    }
};
