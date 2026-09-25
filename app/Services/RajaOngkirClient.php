<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class RajaOngkirClient
{
    public function locations(string $type, ?string $parentId = null): array
    {
        $endpoint = match ($type) {
            'province' => 'province',
            'regency' => 'city',
            'district' => 'subdistrict',
            'village' => 'village',
            default => throw new RuntimeException('Tipe wilayah tidak valid.'),
        };

        $query = $parentId ? ['id' => $parentId] : [];
        $response = $this->request()->get($endpoint, $query)->throw()->json();
        $results = data_get($response, 'rajaongkir.results', data_get($response, 'data', []));

        return collect($results)->map(function (array $location): array {
            return [
                'id' => (string) ($location['id'] ?? $location['city_id'] ?? $location['subdistrict_id'] ?? $location['village_id'] ?? ''),
                'name' => $location['name'] ?? $location['city_name'] ?? $location['subdistrict_name'] ?? $location['village_name'] ?? '',
                'postal_code' => $location['postal_code'] ?? $location['zip_code'] ?? null,
            ];
        })->filter(fn (array $location) => $location['id'] !== '' && $location['name'] !== '')->values()->all();
    }

    public function cost(string $origin, string $destination, int $weight, string $courier): array
    {
        $response = $this->request()->asForm()->post('cost', [
            'origin' => $origin,
            'destination' => $destination,
            'weight' => $weight,
            'courier' => $courier,
        ])->throw()->json();

        return data_get($response, 'rajaongkir.results.0.costs', data_get($response, 'data', []));
    }

    protected function request()
    {
        $key = config('services.rajaongkir.key');

        if (!$key) {
            throw new RuntimeException('RAJAONGKIR_API_KEY belum diatur.');
        }

        return Http::baseUrl(rtrim(config('services.rajaongkir.base_url'), '/') . '/')
            ->acceptJson()
            ->timeout(15)
            ->withHeaders(['key' => $key]);
    }
}
