<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing 'User' role to 'Seller' if any
        DB::table('users')->where('role', 'User')->update(['role' => 'Seller']);

        // Alter table column enum to include Admin, Customer, Seller
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('Admin', 'Customer', 'Seller') NOT NULL DEFAULT 'Customer'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('Admin', 'Customer', 'User') NOT NULL DEFAULT 'User'");
    }
};
