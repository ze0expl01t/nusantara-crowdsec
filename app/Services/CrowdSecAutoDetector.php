<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CrowdSecAutoDetector
{
    public array $result = [
        'status' => 'idle',
        'lapi_url' => null,
        'bouncer_key' => null,
        'machine_name' => null,
        'machine_password' => null,
        'message' => '',
    ];

    public function detect(): array
    {
        $this->result['status'] = 'detecting';
        $this->result['message'] = 'Mencoba terhubung ke LAPI di 127.0.0.1:8080...';

        // 1. Cek apakah LAPI berjalan di server lokal
        $lapiUrl = 'http://127.0.0.1:8080';
        try {
            // Memanggil base URL. LAPI tidak punya /health.
            // Jika dapat respons 404, berarti server HIDUP.
            Http::timeout(3)->get("{$lapiUrl}/");
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            // Hanya error koneksi (server mati / port tertutup) yang dianggap gagal.
            $this->result['status'] = 'error';
            $this->result['message'] = 'LAPI tidak merespons di localhost. Pastikan layanan CrowdSec aktif.';
            return $this->result;
        }

        $this->result['lapi_url'] = $lapiUrl;
        $this->result['message'] = 'LAPI terdeteksi. Mencari kredensial...';

        // 2. Coba deteksi file kredensial lokal
        $this->detectCredentials();

        $this->result['status'] = 'success';
        $this->result['message'] = 'Deteksi selesai. Silakan periksa hasilnya.';
        return $this->result;
    }

    private function detectCredentials(): void
    {
        // Path default file konfigurasi CrowdSec
        $bouncerFile = '/etc/crowdsec/bouncers/crowdsec-firewall-bouncer.yaml';
        $localApiFile = '/etc/crowdsec/local_api_credentials.yaml';

        // Deteksi Bouncer Key
        if (file_exists($bouncerFile) && is_readable($bouncerFile)) {
            $content = file_get_contents($bouncerFile);
            if (preg_match('/api_key:\s*(\S+)/', $content, $matches)) {
                $this->result['bouncer_key'] = $matches[1];
            }
        }

        // Deteksi Machine Credentials
        if (file_exists($localApiFile) && is_readable($localApiFile)) {
            $content = file_get_contents($localApiFile);
            if (preg_match('/login:\s*(\S+)/', $content, $login)) {
                $this->result['machine_name'] = $login[1];
            }
            if (preg_match('/password:\s*(\S+)/', $content, $pass)) {
                $this->result['machine_password'] = $pass[1];
            }
        }

        // Fallback ke .env jika file lokal tidak ditemukan / tidak bisa dibaca
        if (!$this->result['bouncer_key']) {
            $this->result['bouncer_key'] = config('crowdsec.bouncer_key');
        }
        if (!$this->result['machine_name']) {
            $this->result['machine_name'] = config('crowdsec.machine_name');
        }
        if (!$this->result['machine_password']) {
            $this->result['machine_password'] = config('crowdsec.machine_password');
        }
    }
}
