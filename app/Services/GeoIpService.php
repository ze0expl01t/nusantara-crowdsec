<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeoIpService
{
    private const NEGATIVE_SENTINEL = '__geoip_failed__';

    public function getAttackPaths(array $ips): array
    {
        $result = [];
        $targetCoords = [111.418, -6.969]; // Blora

        $uniqueIps = array_slice(array_unique($ips), 0, 50);

        foreach ($uniqueIps as $ip) {
            if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
                continue;
            }

            $cacheKey = "geo_ip_v3_{$ip}";
            $cached = Cache::get($cacheKey);

            if ($cached === null) {
                $geo = $this->fetchGeo($ip);
                if (is_array($geo)) {
                    Cache::put($cacheKey, $geo, 86400);
                    $cached = $geo;
                } else {
                    // Negative cache selama 20 menit agar IP yang timeout tidak memicu hang berulang
                    Cache::put($cacheKey, self::NEGATIVE_SENTINEL, 1200);
                    $cached = self::NEGATIVE_SENTINEL;
                }
            }

            if (is_array($cached)) {
                $result[] = [
                    'coords' => [
                        [$cached['lon'], $cached['lat']],
                        $targetCoords,
                    ],
                    'ip' => $ip,
                    'countryCode' => $cached['countryCode'],
                    'countryName' => $cached['countryName'],
                ];
            }
        }

        return $result;
    }

    private function fetchGeo(string $ip): ?array
    {
        try {
            $res = Http::timeout(3)->get("https://freeipapi.com/api/json/{$ip}");
            if ($res->successful() && $res->json('latitude') !== null && $res->json('longitude') !== null) {
                return [
                    'lat' => $res->json('latitude'),
                    'lon' => $res->json('longitude'),
                    'countryCode' => $res->json('countryCode') ?? 'Unknown',
                    'countryName' => $res->json('countryName') ?? 'Unknown',
                ];
            }
        } catch (\Throwable $e) {
            Log::warning("GeoIP failed for {$ip}: " . $e->getMessage());
        }

        return null;
    }
}
