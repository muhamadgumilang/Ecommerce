<?php

namespace Database\Seeders;

use App\Models\ShippingCost;
use Illuminate\Database\Seeder;

class ShippingCostSeeder extends Seeder
{
    public function run(): void
    {
        // Data contoh ongkir berbasis berat untuk berbagai ekspedisi.
        // Asal diatur dari Dayeuhkolot, Cibedug, RT 4 RW 2.
        $shippingCosts = [
            ['origin' => 'dayeuhkolot-cibedug-rt4-rw2', 'destination' => '531', 'courier' => 'jne', 'service' => 'REG', 'description' => 'JNE REG', 'cost' => 24000, 'etd' => 2],
            ['origin' => 'dayeuhkolot-cibedug-rt4-rw2', 'destination' => '531', 'courier' => 'jne', 'service' => 'YES', 'description' => 'JNE YES', 'cost' => 42000, 'etd' => 1],
            ['origin' => 'dayeuhkolot-cibedug-rt4-rw2', 'destination' => '531', 'courier' => 'tiki', 'service' => 'REG', 'description' => 'TIKI REG', 'cost' => 22000, 'etd' => 3],
            ['origin' => 'dayeuhkolot-cibedug-rt4-rw2', 'destination' => '531', 'courier' => 'tiki', 'service' => 'ECO', 'description' => 'TIKI ECO', 'cost' => 19000, 'etd' => 4],
            ['origin' => 'dayeuhkolot-cibedug-rt4-rw2', 'destination' => '531', 'courier' => 'pos', 'service' => 'REG', 'description' => 'POS REG', 'cost' => 21000, 'etd' => 4],
            ['origin' => 'dayeuhkolot-cibedug-rt4-rw2', 'destination' => '531', 'courier' => 'pos', 'service' => 'YES', 'description' => 'POS YES', 'cost' => 36000, 'etd' => 2],

            ['origin' => 'dayeuhkolot-cibedug-rt4-rw2', 'destination' => '113', 'courier' => 'jne', 'service' => 'REG', 'description' => 'JNE REG', 'cost' => 26000, 'etd' => 3],
            ['origin' => 'dayeuhkolot-cibedug-rt4-rw2', 'destination' => '113', 'courier' => 'jnt', 'service' => 'REG', 'description' => 'J&T REG', 'cost' => 25000, 'etd' => 3],
            ['origin' => 'dayeuhkolot-cibedug-rt4-rw2', 'destination' => '113', 'courier' => 'sicepat', 'service' => 'REG', 'description' => 'SiCepat REG', 'cost' => 24000, 'etd' => 3],

            ['origin' => 'dayeuhkolot-cibedug-rt4-rw2', 'destination' => '153', 'courier' => 'jne', 'service' => 'REG', 'description' => 'JNE REG', 'cost' => 28000, 'etd' => 3],
            ['origin' => 'dayeuhkolot-cibedug-rt4-rw2', 'destination' => '153', 'courier' => 'tiki', 'service' => 'REG', 'description' => 'TIKI REG', 'cost' => 26000, 'etd' => 4],
            ['origin' => 'dayeuhkolot-cibedug-rt4-rw2', 'destination' => '153', 'courier' => 'pos', 'service' => 'PAKET', 'description' => 'POS PAKET', 'cost' => 23000, 'etd' => 5],
        ];

        foreach ($shippingCosts as $cost) {
            ShippingCost::firstOrCreate(
                ['origin' => $cost['origin'], 'destination' => $cost['destination'], 'courier' => $cost['courier'], 'service' => $cost['service']],
                $cost
            );
        }
    }
}
