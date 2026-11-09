<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class GeoIpService
{
    public function getAttackPaths(array $ips): array
    {
        $result = [];
        $targetCoords = [111.418, -6.969]; // Blora

        $uniqueIps = array_slice(array_unique($ips), 0, 50);

        foreach ($uniqueIps as $ip) {
            if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
                continue;
            }

            $geo = Cache::remember("geo_ip_v2_{$ip}", 86400, function () use ($ip) {
                try {
                    $res = Http::timeout(3)->get("https://freeipapi.com/api/json/{$ip}");
                    if ($res->successful() && $res->json('latitude') !== null) {
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
            });

            if ($geo) {
                $result[] = [
                    'coords' => [
                        [$geo['lon'], $geo['lat']], 
                        $targetCoords               
                    ],
                    'ip' => $ip,
                    'countryCode' => $geo['countryCode'],
                    'countryName' => $geo['countryName']
                ];
            }
        }
        return $result;
    }
}
