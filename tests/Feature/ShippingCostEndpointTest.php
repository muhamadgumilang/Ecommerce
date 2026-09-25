<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ShippingCostEndpointTest extends TestCase
{
    public function test_it_returns_shipping_costs_with_fallback_when_rajaongkir_is_unavailable(): void
    {
        config()->set('services.rajaongkir.key', 'test-key');

        Http::fake([
            'https://api.rajaongkir.com/starter/*' => Http::response([
                'rajaongkir' => ['results' => []],
            ], 503),
        ]);

        $user = new User([
            'name' => 'Test Customer',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
        ]);

        $response = $this->actingAs($user)->getJson(route('shipping.cost', [
            'origin' => '501',
            'destination' => '531',
            'weight' => 1000,
            'courier' => 'jne',
        ]));

        $response->assertOk();
        $this->assertNotEmpty($response->json());
        $this->assertSame('jne', strtolower($response->json()[0]['courier']));
        $this->assertArrayHasKey('cost', $response->json()[0]);
    }
}
