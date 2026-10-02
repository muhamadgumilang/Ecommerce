<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardRoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_default_dashboard_redirects_to_the_storefront(): void
    {
        $customer = User::factory()->create(['role' => 'Customer']);

        $this->actingAs($customer)
            ->get(route('dashboard'))
            ->assertRedirect(route('home'));

        $this->actingAs($customer)->get('/customer/dashboard')->assertNotFound();
    }

    public function test_customer_cannot_open_a_different_role_dashboard(): void
    {
        $customer = User::factory()->create(['role' => 'Customer']);

        $this->actingAs($customer)
            ->get(route('seller.dashboard'))
            ->assertForbidden();
    }
}