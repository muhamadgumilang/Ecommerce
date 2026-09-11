<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_verify_payment_and_order_becomes_processing(): void
    {
        $admin = User::factory()->create(['role' => 'Admin']);
        $customer = User::factory()->create(['role' => 'Customer']);
        $order = Order::create([
            'customer_id' => $customer->user_id,
            'order_date' => now(),
            'total_amount' => 150000,
            'order_status' => 'Pending Payment',
        ]);
        $payment = Payment::create([
            'order_id' => $order->order_id,
            'payment_method' => 'Midtrans Snap',
            'payment_status' => 'Pending',
        ]);

        $response = $this->actingAs($admin)->patch(route('admin.payments.update', $payment), [
            'payment_status' => 'Verified',
        ]);

        $response->assertRedirect(route('admin.payments.index'));
        $this->assertDatabaseHas('payments', [
            'payment_id' => $payment->payment_id,
            'payment_status' => 'Verified',
            'admin_id' => $admin->user_id,
        ]);
        $this->assertDatabaseHas('orders', [
            'order_id' => $order->order_id,
            'order_status' => 'Processing',
        ]);
    }

    public function test_admin_can_mark_payment_failed_and_order_returns_to_pending_payment(): void
    {
        $admin = User::factory()->create(['role' => 'Admin']);
        $customer = User::factory()->create(['role' => 'Customer']);
        $order = Order::create([
            'customer_id' => $customer->user_id,
            'order_date' => now(),
            'total_amount' => 150000,
            'order_status' => 'Processing',
        ]);
        $payment = Payment::create([
            'order_id' => $order->order_id,
            'payment_method' => 'Midtrans Snap',
            'payment_status' => 'Verified',
        ]);

        $this->actingAs($admin)->patch(route('admin.payments.update', $payment), [
            'payment_status' => 'Failed',
        ]);

        $this->assertDatabaseHas('payments', [
            'payment_id' => $payment->payment_id,
            'payment_status' => 'Failed',
        ]);
        $this->assertDatabaseHas('orders', [
            'order_id' => $order->order_id,
            'order_status' => 'Pending Payment',
        ]);
    }

    public function test_customer_cannot_update_payment_from_admin_route(): void
    {
        $customer = User::factory()->create(['role' => 'Customer']);
        $order = Order::create([
            'customer_id' => $customer->user_id,
            'order_date' => now(),
            'total_amount' => 150000,
            'order_status' => 'Pending Payment',
        ]);
        $payment = Payment::create([
            'order_id' => $order->order_id,
            'payment_method' => 'Midtrans Snap',
            'payment_status' => 'Pending',
        ]);

        $response = $this->actingAs($customer)->patch(route('admin.payments.update', $payment), [
            'payment_status' => 'Verified',
        ]);

        $response->assertForbidden();
    }
}
