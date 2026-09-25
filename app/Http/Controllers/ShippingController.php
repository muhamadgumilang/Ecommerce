<?php

namespace App\Http\Controllers;

use App\Services\RajaOngkirClient;
use App\Services\ShippingCalculator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class ShippingController extends Controller
{
    public function locations(Request $request, RajaOngkirClient $client): JsonResponse
    {
        $validated = $request->validate([
            'type' => 'required|in:province,regency,district,village',
            'parent' => 'nullable|string|max:50',
        ]);

        try {
            $locations = $client->locations($validated['type'], $validated['parent'] ?? null);

            if (!empty($locations)) {
                return response()->json($locations);
            }
        } catch (Throwable $exception) {
            report($exception);
        }

        $fallback = $this->fallbackLocations($validated['type'], $validated['parent'] ?? null);

        if (!empty($fallback)) {
            return response()->json($fallback);
        }

        return response()->json(['message' => 'Data wilayah RajaOngkir tidak dapat dimuat.'], 503);
    }

    public function cost(Request $request, RajaOngkirClient $client): JsonResponse
    {
        $validated = $request->validate([
            'origin' => 'required|string|max:100',
            'destination' => 'required|string|max:100',
            'weight' => 'required|numeric|min:1',
            'courier' => 'required|string|max:50',
        ]);

        try {
            $costs = $client->cost(
                $validated['origin'],
                $validated['destination'],
                (int) round((float) $validated['weight']),
                $validated['courier'],
            );

            if (!empty($costs)) {
                return response()->json($costs);
            }
        } catch (Throwable $exception) {
            report($exception);
        }

        $fallback = (new ShippingCalculator())->availableOptions(
            $validated['destination'],
            (int) round((float) $validated['weight'])
        );

        $options = [];
        foreach ($fallback as $option) {
            if (strtolower($option['courier']) !== strtolower($validated['courier'])) {
                continue;
            }

            $options[] = [
                'courier' => $option['courier'],
                'service' => $option['service'],
                'description' => $option['description'],
                'cost' => [
                    ['value' => $option['cost'], 'etd' => (string) $option['etd'] . ' hari'],
                ],
            ];
        }

        if (empty($options)) {
            return response()->json(['message' => 'Biaya pengiriman tidak tersedia saat ini.'], 503);
        }

        return response()->json($options);
    }

    protected function fallbackLocations(string $type, ?string $parentId = null): array
    {
        // Struktur wilayah Indonesia sesuai tingkat data:
        // Provinsi 38, Kabupaten/Kota 514, Kecamatan 7.281, Desa/Kelurahan 84.276.
        $provinces = [
            ['id' => '11', 'name' => 'ACEH'],
            ['id' => '12', 'name' => 'SUMATERA UTARA'],
            ['id' => '13', 'name' => 'SUMATERA BARAT'],
            ['id' => '14', 'name' => 'RIAU'],
            ['id' => '15', 'name' => 'JAMBI'],
            ['id' => '16', 'name' => 'SUMATERA SELATAN'],
            ['id' => '17', 'name' => 'BENGKULU'],
            ['id' => '18', 'name' => 'LAMPUNG'],
            ['id' => '19', 'name' => 'KEPULAUAN BANGKA BELITUNG'],
            ['id' => '21', 'name' => 'KEPULAUAN RIAU'],
            ['id' => '31', 'name' => 'DKI JAKARTA'],
            ['id' => '32', 'name' => 'JAWA BARAT'],
            ['id' => '33', 'name' => 'JAWA TENGAH'],
            ['id' => '34', 'name' => 'JAWA TIMUR'],
            ['id' => '35', 'name' => 'DI YOGYAKARTA'],
            ['id' => '36', 'name' => 'BANTEN'],
            ['id' => '51', 'name' => 'BALI'],
            ['id' => '52', 'name' => 'NUSA TENGGARA BARAT'],
            ['id' => '53', 'name' => 'NUSA TENGGARA TIMUR'],
            ['id' => '61', 'name' => 'KALIMANTAN BARAT'],
            ['id' => '62', 'name' => 'KALIMANTAN TENGAH'],
            ['id' => '63', 'name' => 'KALIMANTAN SELATAN'],
            ['id' => '64', 'name' => 'KALIMANTAN TIMUR'],
            ['id' => '65', 'name' => 'KALIMANTAN UTARA'],
            ['id' => '71', 'name' => 'SULAWESI UTARA'],
            ['id' => '72', 'name' => 'SULAWESI TENGAH'],
            ['id' => '73', 'name' => 'SULAWESI SELATAN'],
            ['id' => '74', 'name' => 'SULAWESI TENGGARA'],
            ['id' => '75', 'name' => 'GORONTALO'],
            ['id' => '76', 'name' => 'SULAWESI BARAT'],
            ['id' => '81', 'name' => 'MALUKU'],
            ['id' => '82', 'name' => 'MALUKU UTARA'],
            ['id' => '91', 'name' => 'PAPUA BARAT'],
            ['id' => '92', 'name' => 'PAPUA'],
            ['id' => '93', 'name' => 'PAPUA TENGAH'],
            ['id' => '94', 'name' => 'PAPUA PEGUNUNGAN'],
            ['id' => '95', 'name' => 'PAPUA SELATAN'],
            ['id' => '96', 'name' => 'PAPUA BARAT DAYA'],
        ];

        $regencies = [
            '11' => [
                ['id' => '1101', 'name' => 'KABUPATEN ACEH SELATAN'],
                ['id' => '1102', 'name' => 'KOTA BANDA ACEH'],
            ],
            '12' => [
                ['id' => '1201', 'name' => 'KABUPATEN DELI SERDANG'],
                ['id' => '1271', 'name' => 'KOTA MEDAN'],
            ],
            '31' => [
                ['id' => '3101', 'name' => 'KOTA JAKARTA PUSAT'],
                ['id' => '3171', 'name' => 'KOTA JAKARTA SELATAN'],
                ['id' => '3172', 'name' => 'KOTA JAKARTA BARAT'],
                ['id' => '3173', 'name' => 'KOTA JAKARTA TIMUR'],
            ],
            '32' => [
                ['id' => '3204', 'name' => 'KABUPATEN BANDUNG'],
                ['id' => '3273', 'name' => 'KOTA BANDUNG'],
                ['id' => '3201', 'name' => 'KABUPATEN BOGOR'],
                ['id' => '3275', 'name' => 'KOTA BEKASI'],
            ],
            '33' => [
                ['id' => '3301', 'name' => 'KABUPATEN CILACAP'],
                ['id' => '3371', 'name' => 'KOTA SEMARANG'],
                ['id' => '3317', 'name' => 'KABUPATEN PEMALANG'],
            ],
            '34' => [
                ['id' => '3401', 'name' => 'KABUPATEN PACITAN'],
                ['id' => '3573', 'name' => 'KOTA SURABAYA'],
                ['id' => '3501', 'name' => 'KABUPATEN PACITAN'],
            ],
            '35' => [
                ['id' => '3500', 'name' => 'KOTA YOGYAKARTA'],
                ['id' => '3501', 'name' => 'KABUPATEN SLEMAN'],
            ],
            '36' => [
                ['id' => '3601', 'name' => 'KABUPATEN PANDEGLANG'],
                ['id' => '3671', 'name' => 'KOTA TANGERANG'],
                ['id' => '3674', 'name' => 'KOTA TANGERANG SELATAN'],
            ],
            '51' => [
                ['id' => '5101', 'name' => 'KABUPATEN JEMBRANA'],
                ['id' => '5171', 'name' => 'KOTA DENPASAR'],
            ],
            '73' => [
                ['id' => '7301', 'name' => 'KABUPATEN KEPULAUAN SELAYAR'],
                ['id' => '7371', 'name' => 'KOTA MAKASSAR'],
            ],
            '92' => [
                ['id' => '9201', 'name' => 'KABUPATEN MERAUKE'],
                ['id' => '9202', 'name' => 'KOTA JAYAPURA'],
            ],
        ];

        $districts = [
            '3204' => [
                ['id' => '320425', 'name' => 'DAYEUHKOLOT'],
                ['id' => '320401', 'name' => 'BANDUNG'],
                ['id' => '320426', 'name' => 'CILEUNYI'],
                ['id' => '320427', 'name' => 'UJUNGBERUNG'],
            ],
            '3273' => [
                ['id' => '327302', 'name' => 'BANDUNG KULON'],
                ['id' => '327303', 'name' => 'BANDUNG WETAN'],
                ['id' => '327304', 'name' => 'ANDIR'],
            ],
            '3171' => [
                ['id' => '317101', 'name' => 'SETIABUDI'],
                ['id' => '317102', 'name' => 'KEBAYORAN BARU'],
            ],
            '3573' => [
                ['id' => '357301', 'name' => 'GUBENG'],
                ['id' => '357302', 'name' => 'WONOKROMO'],
            ],
            '3671' => [
                ['id' => '367101', 'name' => 'TANGERANG'],
                ['id' => '367102', 'name' => 'CIPUTAT'],
            ],
        ];

        $villages = [
            '320425' => [
                ['id' => '3204251001', 'name' => 'CIBEDUG'],
                ['id' => '3204251002', 'name' => 'DAYEUHKOLOT'],
                ['id' => '3204251003', 'name' => 'NAGRAK'],
            ],
            '317101' => [
                ['id' => '3171011001', 'name' => 'GUNUNG'],
                ['id' => '3171011002', 'name' => 'SADANG'],
            ],
            '357301' => [
                ['id' => '3573011001', 'name' => 'GUBENG'],
                ['id' => '3573011002', 'name' => 'KERTAJAYA'],
            ],
        ];

        return match ($type) {
            'province' => $provinces,
            'regency' => $regencies[$parentId] ?? array_values($regencies)[0],
            'district' => $districts[$parentId] ?? [],
            'village' => $villages[$parentId] ?? [],
            default => [],
        };
    }
}
