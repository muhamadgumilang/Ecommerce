<?php

namespace Database\Seeders;

use App\Models\ShippingCost;
use Illuminate\Database\Seeder;

class ShippingCostSeeder extends Seeder
{
    public function run(): void
    {
        // Data contoh ongkir untuk berbagai kota di Indonesia
        // Kode city_id mengikuti format RajaOngkir (contoh: 1 = Surabaya, 153 = Jakarta Pusat)
        $shippingCosts = [
            // JNE
            ['origin' => '1', 'destination' => '531', 'courier' => 'jne', 'service' => 'REG', 'description' => 'JNE Reguler', 'cost' => 25000, 'etd' => 2],
            ['origin' => '1', 'destination' => '531', 'courier' => 'jne', 'service' => 'YES', 'description' => 'JNE Yes (Same Day)', 'cost' => 45000, 'etd' => 1],
            ['origin' => '1', 'destination' => '531', 'courier' => 'tiki', 'service' => 'REG', 'description' => 'TIKI Reguler', 'cost' => 22000, 'etd' => 3],
            ['origin' => '1', 'destination' => '531', 'courier' => 'tiki', 'service' => 'YES', 'description' => 'TIKI Yes (Same Day)', 'cost' => 40000, 'etd' => 1],
            ['origin' => '1', 'destination' => '531', 'courier' => 'pos', 'service' => 'REG', 'description' => 'POS Reguler', 'cost' => 18000, 'etd' => 4],
            ['origin' => '1', 'destination' => '531', 'courier' => 'pos', 'service' => 'YES', 'description' => 'POS Yes (Same Day)', 'cost' => 35000, 'etd' => 1],

            // TIKI
            ['origin' => '1', 'destination' => '113', 'courier' => 'jne', 'service' => 'REG', 'description' => 'JNE Reguler', 'cost' => 30000, 'etd' => 3],
            ['origin' => '1', 'destination' => '113', 'courier' => 'tiki', 'service' => 'REG', 'description' => 'TIKI Reguler', 'cost' => 27000, 'etd' => 3],
            ['origin' => '1', 'destination' => '113', 'courier' => 'pos', 'service' => 'REG', 'description' => 'POS Reguler', 'cost' => 22000, 'etd' => 4],

            // POS
            ['origin' => '1', 'destination' => '153', 'courier' => 'jne', 'service' => 'REG', 'description' => 'JNE Reguler', 'cost' => 35000, 'etd' => 3],
            ['origin' => '1', 'destination' => '153', 'courier' => 'tiki', 'service' => 'REG', 'description' => 'TIKI Reguler', 'cost' => 32000, 'etd' => 3],
            ['origin' => '1', 'destination' => '153', 'courier' => 'pos', 'service' => 'REG', 'description' => 'POS Reguler', 'cost' => 27000, 'etd' => 4],
        ];

        foreach ($shippingCosts as $cost) {
            ShippingCost::firstOrCreate(
                ['origin' => $cost['origin'], 'destination' => $cost['destination'], 'courier' => $cost['courier'], 'service' => $cost['service']],
                $cost
            );
        }
    }
}
