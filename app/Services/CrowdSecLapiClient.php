<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class CrowdSecLapiClient
{
    private string $baseUrl;
    private ?string $jwt = null;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('crowdsec.lapi_url'), '/');
    }

    private function login(): void
    {
        if ($this->jwt) return;
        
        $res = Http::timeout(5)->post("{$this->baseUrl}/v1/watchers/login", [
            'machine_id' => config('crowdsec.machine_name'),
            'password'   => config('crowdsec.machine_password'),
        ]);
        
        if (! $res->successful()) throw new \RuntimeException('LAPI Auth Failed: ' . $res->status());
        
        $this->jwt = $res->json('token');
        Cache::put('crowdsec.jwt', $this->jwt, now()->addMinutes(50));
    }

    private function machineClient()
    {
        $this->login();
        return Http::withToken($this->jwt)->baseUrl($this->baseUrl)->timeout(10);
    }

    private function bouncerClient()
    {
        return Http::withHeaders(['X-Api-Key' => config('crowdsec.bouncer_key')])
                    ->baseUrl($this->baseUrl)->timeout(20); // Timeout 20s untuk tarik ribuan data
    }

    public function getDecisions(): array
    { 
        try {
            // Menghapus limit agar menarik semua data (15.000+)
            $res = $this->bouncerClient()->get('/v1/decisions');
            if ($res->successful()) {
                $data = $res->json();
                if (is_array($data) && (empty($data) || isset($data[0]))) return $data;
            }
            return [];
        } catch (\Exception $e) { 
            Log::error('LAPI getDecisions: ' . $e->getMessage()); 
            return []; 
        }
    }

    public function getAlerts(int $limit = 100): array
    { 
        try {
            $res = $this->machineClient()->get('/v1/alerts', ['limit' => $limit]);
            if ($res->successful()) {
                $data = $res->json();
                if (is_array($data)) return $data;
            }
            return [];
        } catch (\Exception $e) { return []; }
    }


    public function createDecision(string $ip, string $duration, string $reason, string $type = 'ban'): bool
    {
        try {
            $res = $this->machineClient()->post('/v1/decisions', [
                'duration' => $duration,
                'reason'   => $reason,
                'scope'    => 'Ip',
                'type'     => $type,
                'value'    => $ip
            ]);
            
            if (!$res->successful()) {
                Log::error('LAPI createDecision failed: ' . $res->body());
            }
            return $res->successful();
        } catch (\Exception $e) { 
            Log::error('LAPI createDecision: ' . $e->getMessage()); 
            return false; 
        }
    }

    public function deleteDecision(string $id): bool
    {
        try {
            return $this->machineClient()->delete("/v1/decisions/{$id}")->successful();
        } catch (\Exception $e) { return false; }
    }

    public function isConnected(): bool
    {
        try { 
            $res = $this->bouncerClient()->get('/v1/decisions', ['limit' => 1]);
            return $res->successful(); 
        } catch (\Exception $e) { return false; }
    }
}
