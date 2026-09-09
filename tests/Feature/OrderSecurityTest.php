<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_cannot_access_another_users_payment_form(): void
    {
        $owner = User::factory()->create([
            'role' => 'Customer',
        ]);
        $otherCustomer = User::factory()->create([
            'role' => 'Customer',
        ]);

        $order = Order::create([
            'customer_id' => $owner->user_id,
            'order_date' => now(),
            'total_amount' => 150000,
            'order_status' => 'Pending Payment',
        ]);

        $response = $this->actingAs($otherCustomer)->get("/orders/{$order->order_id}/payment");

        $response->assertForbidden();
    }

    public function test_customer_cannot_submit_payment_for_another_users_order(): void
    {
        $owner = User::factory()->create([
            'role' => 'Customer',
        ]);
        $otherCustomer = User::factory()->create([
            'role' => 'Customer',
        ]);

        $order = Order::create([
            'customer_id' => $owner->user_id,
            'order_date' => now(),
            'total_amount' => 150000,
            'order_status' => 'Pending Payment',
        ]);

        $response = $this->actingAs($otherCustomer)->post("/orders/{$order->order_id}/payment", [
            'payment_method' => 'Bank Transfer',
        ]);

        $response->assertForbidden();
    }
}
