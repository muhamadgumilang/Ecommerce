<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\RajaOngkirClient;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ShippingCostEndpointTest extends TestCase
{
    public function test_location_fallbacks_work_for_province_regency_district_and_village(): void
    {
        config()->set('services.rajaongkir.key', null);
        $user = new User([
            'name' => 'Test Customer',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'role' => 'Customer',
        ]);

        foreach ([
            ['province', null, 'JAWA BARAT'],
            ['regency', '32', 'KABUPATEN BANDUNG'],
            ['district', '3204', 'DAYEUHKOLOT'],
            ['village', '320425', 'CIBEDUG'],
        ] as [$type, $parent, $expectedName]) {
            $response = $this->actingAs($user)->getJson(route('shipping.locations', array_filter([
                'type' => $type,
                'parent' => $parent,
            ])));

            $response->assertOk()->assertJsonFragment(['name' => $expectedName]);
        }
    }

    public function test_province_lookup_maps_rajaongkir_province_fields(): void
    {
        config()->set('services.rajaongkir.key', 'test-key');
        Http::fake([
            'https://api.rajaongkir.com/starter/province' => Http::response([
                'rajaongkir' => ['results' => [['province_id' => '32', 'province' => 'JAWA BARAT']]],
            ]),
        ]);

        $locations = app(RajaOngkirClient::class)->locations('province');

        $this->assertSame(['id' => '32', 'name' => 'JAWA BARAT', 'postal_code' => null], $locations[0]);
    }

    public function test_regency_lookup_sends_the_province_id_to_rajaongkir(): void
    {
        config()->set('services.rajaongkir.key', 'test-key');
        Http::fake([
            'https://api.rajaongkir.com/starter/city*' => Http::response([
                'rajaongkir' => ['results' => [['city_id' => '3273', 'city_name' => 'KOTA BANDUNG']]],
            ]),
        ]);

        $locations = app(RajaOngkirClient::class)->locations('regency', '32');

        $this->assertSame('3273', $locations[0]['id']);
        Http::assertSent(fn ($request) => str_contains($request->url(), 'province=32'));
    }

    public function test_cost_options_include_the_requested_courier_and_destination(): void
    {
        config()->set('services.rajaongkir.key', 'test-key');
        Http::fake([
            'https://api.rajaongkir.com/starter/cost' => Http::response([
                'rajaongkir' => ['results' => [[
                    'costs' => [['service' => 'REG', 'cost' => [['value' => 12000, 'etd' => '1-2']]]],
                ]]],
            ]),
        ]);

        $options = app(RajaOngkirClient::class)->cost('23', '3273', 1000, 'tiki');

        $this->assertSame('tiki', $options[0]['courier']);
        $this->assertSame('3273', $options[0]['destination']);
    }

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
