<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class ActivityLogAccessLocationController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'accuracy' => ['nullable', 'numeric', 'min:0'],
            'place_name' => ['nullable', 'string', 'max:255'],
        ]);

        $location = [
            'latitude' => (float) $data['latitude'],
            'longitude' => (float) $data['longitude'],
            'accuracy' => isset($data['accuracy']) ? (float) $data['accuracy'] : null,
            'place_name' => filled($data['place_name'] ?? null)
                ? $data['place_name']
                : $this->resolvePlaceName((float) $data['latitude'], (float) $data['longitude']),
            'captured_at' => now()->toIso8601String(),
        ];

        session(['access_location' => $location]);

        ActivityLog::query()
            ->where('user_id', Auth::id())
            ->where('created_at', '>=', now()->subHours(2))
            ->latest('created_at')
            ->limit(10)
            ->get()
            ->each(function (ActivityLog $log) use ($location) {
                $properties = $log->properties ?? [];
                $properties['access_location'] = $location;
                $log->forceFill(['properties' => $properties])->save();
            });

        return response()->json([
            'location' => $location['place_name'],
        ]);
    }

    protected function resolvePlaceName(float $latitude, float $longitude): string
    {
        $cacheKey = sprintf('reverse-geocode:%0.4f:%0.4f', $latitude, $longitude);

        return Cache::remember($cacheKey, now()->addDays(30), function () use ($latitude, $longitude) {
            try {
                $response = Http::timeout(4)
                    ->acceptJson()
                    ->withHeaders([
                        'User-Agent' => config('app.name', 'Sisdik Bintuni') . '/1.0',
                    ])
                    ->get('https://nominatim.openstreetmap.org/reverse', [
                        'format' => 'jsonv2',
                        'lat' => $latitude,
                        'lon' => $longitude,
                        'zoom' => 14,
                        'addressdetails' => 1,
                        'accept-language' => 'id',
                    ]);

                if (! $response->successful()) {
                    return $this->formatCoordinates($latitude, $longitude);
                }

                return $this->formatPlaceName($response->json(), $latitude, $longitude);
            } catch (\Throwable) {
                return $this->formatCoordinates($latitude, $longitude);
            }
        });
    }

    protected function formatPlaceName(array $data, float $latitude, float $longitude): string
    {
        $address = $data['address'] ?? [];
        $parts = array_filter([
            $address['suburb']
                ?? $address['village']
                ?? $address['town']
                ?? $address['city']
                ?? $address['municipality']
                ?? null,
            $address['county'] ?? $address['city_district'] ?? null,
            $address['state'] ?? null,
            $address['country'] ?? null,
        ]);

        if (! empty($parts)) {
            return implode(', ', array_unique($parts));
        }

        return $data['display_name'] ?? $this->formatCoordinates($latitude, $longitude);
    }

    protected function formatCoordinates(float $latitude, float $longitude): string
    {
        return number_format($latitude, 5) . ', ' . number_format($longitude, 5);
    }
}
