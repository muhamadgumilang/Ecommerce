<?php

namespace App\Services;

class ShippingCalculator
{
    public const DEFAULT_ORIGIN = 'dayeuhkolot-cibedug-rt4-rw2';

    protected array $courierMap = [
        'jne' => ['label' => 'JNE', 'services' => ['REG' => ['base' => 9000, 'per_kg' => 2500], 'YES' => ['base' => 16000, 'per_kg' => 3500], 'OKE' => ['base' => 7000, 'per_kg' => 2200]]],
        'tiki' => ['label' => 'TIKI', 'services' => ['REG' => ['base' => 8500, 'per_kg' => 2200], 'ECO' => ['base' => 6500, 'per_kg' => 1800], 'ONS' => ['base' => 11000, 'per_kg' => 2800]]],
        'pos' => ['label' => 'POS Indonesia', 'services' => ['REG' => ['base' => 10000, 'per_kg' => 2600], 'YES' => ['base' => 12000, 'per_kg' => 3000], 'PAKET' => ['base' => 9000, 'per_kg' => 2400]]],
        'jnt' => ['label' => 'J&T', 'services' => ['REG' => ['base' => 9500, 'per_kg' => 2600], 'EZ' => ['base' => 13000, 'per_kg' => 3200]]],
        'sicepat' => ['label' => 'SiCepat', 'services' => ['REG' => ['base' => 8800, 'per_kg' => 2300], 'BEST' => ['base' => 15000, 'per_kg' => 3400]]],
    ];

    protected array $destinationMultiplier = [
        '531' => 1.15,
        '113' => 1.10,
        '153' => 1.20,
        'default' => 1.00,
    ];

    public function calculate(string $courier, string $service, string $destination, int|float $weightInGram = 1000): int
    {
        $courier = strtolower(trim($courier));
        $service = strtoupper(trim($service));
        $destination = (string) $destination;

        if (!isset($this->courierMap[$courier])) {
            return 0;
        }

        if (!isset($this->courierMap[$courier]['services'][$service])) {
            $service = array_key_first($this->courierMap[$courier]['services']);
        }

        $config = $this->courierMap[$courier]['services'][$service];
        $weightInKg = max(1, (float) $weightInGram / 1000);
        $extraKg = max(0, (int) ceil($weightInKg) - 1);
        $baseFee = (int) round($config['base'] + ($config['per_kg'] * $extraKg));

        $multiplier = $this->destinationMultiplier[$destination] ?? $this->destinationMultiplier['default'];

        return (int) round($baseFee * $multiplier);
    }

    public function availableOptions(string $destination = null, int $weightInGram = 1000): array
    {
        $allOptions = [];

        foreach ($this->courierMap as $courier => $config) {
            foreach ($config['services'] as $service => $rate) {
                $destinationKey = $destination ?: 'default';
                $cost = $this->calculate($courier, $service, $destinationKey, $weightInGram);

                $allOptions[$courier . '|' . $destinationKey . '|' . $service] = [
                    'courier' => $courier,
                    'destination' => $destinationKey === 'default' ? '531' : $destinationKey,
                    'service' => $service,
                    'cost' => $cost,
                    'description' => $this->formatDescription($courier, $service),
                    'etd' => $this->estimateDay($courier, $service),
                ];
            }
        }

        return $allOptions;
    }

    public function formatDescription(string $courier, string $service): string
    {
        $courierName = $this->courierMap[strtolower($courier)]['label'] ?? strtoupper($courier);

        return $courierName . ' ' . $service;
    }

    public function estimateDay(string $courier, string $service): int
    {
        $service = strtoupper($service);

        return match (strtolower($courier)) {
            'jne' => $service === 'YES' ? 1 : 2,
            'tiki' => 3,
            'pos' => 4,
            'jnt' => $service === 'EZ' ? 2 : 3,
            'sicepat' => $service === 'BEST' ? 2 : 3,
            default => 3,
        };
    }
}
