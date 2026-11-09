<?php
namespace App\Livewire;

use App\Services\CrowdSecLapiClient;
use App\Services\GeoIpService;
use App\Models\AuditLog;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class CrowdSecDashboard extends Component
{
    public bool $isConnected = false;
    public array $decisions = []; 
    public int $totalDecisions = 0;
    public string $lastUpdated = '';
    
    public array $chartData = [
        'countries' => ['labels' => [], 'data' => []],
        'scenarios' => ['labels' => [], 'data' => []],
        'trends'    => ['labels' => [], 'data' => []],
    ];

    public array $attackMapData = []; 

    public function mount(CrowdSecLapiClient $client, GeoIpService $geo)
    {
        $this->fetchData($client, $geo);
    }

    public function refreshData(CrowdSecLapiClient $client, GeoIpService $geo)
    {
        $this->fetchData($client, $geo);
        $this->dispatch('chart-data-updated');
    }

    private function fetchData(CrowdSecLapiClient $client, GeoIpService $geo)
    {
        $this->isConnected = $client->isConnected();
        
        if ($this->isConnected) {
            $allDecisions = $client->getDecisions();
            $this->totalDecisions = count($allDecisions);
            $this->lastUpdated = now()->format('H:i:s');

            $this->decisions = array_slice($allDecisions, 0, 50);
            $alerts = array_values(array_filter($client->getAlerts(100), 'is_array'));

            // Ambil IP untuk Peta
            $ipsForMap = array_column($this->decisions, 'value');
            $this->attackMapData = $geo->getAttackPaths($ipsForMap);

            $this->generateChartData($this->decisions, $alerts);
            
            if ($this->totalDecisions > 0) {
                AuditLog::create([
                    'action'  => 'refresh_dashboard',
                    'target'  => 'lapi',
                    'actor'   => Auth::user()->email ?? 'system',
                    'meta'    => ['total_fetched' => $this->totalDecisions]
                ]);
            }
        } else {
            $this->decisions = [];
            $this->totalDecisions = 0;
            $this->attackMapData = [];
        }
    }

    private function generateChartData(array $samples, array $alerts)
    {
        if (!empty($samples)) {
            $scenarios = array_count_values(array_filter(array_column($samples, 'scenario')));
            arsort($scenarios);
            $topScenarios = array_slice($scenarios, 0, 5, true);
            $cleanScenarios = array_map(fn($key) => basename($key), array_keys($topScenarios));
            $this->chartData['scenarios'] = ['labels' => $cleanScenarios, 'data' => array_values($topScenarios)];
        }

        if (!empty($alerts)) {
            $countries = [];
            foreach ($alerts as $a) {
                $c = !empty($a['source']['cn']) ? $a['source']['cn'] : 'Unknown';
                $countries[$c] = ($countries[$c] ?? 0) + 1;
            }
            arsort($countries);
            $topCountries = array_slice($countries, 0, 5, true);
            $this->chartData['countries'] = ['labels' => array_keys($topCountries), 'data' => array_values($topCountries)];

            $trends = [];
            foreach ($alerts as $a) {
                $date = substr($a['created_at'] ?? date('Y-m-d'), 0, 10); 
                $trends[$date] = ($trends[$date] ?? 0) + 1;
            }
            ksort($trends);
            $this->chartData['trends'] = ['labels' => array_keys($trends), 'data' => array_values($trends)];
        } else {
            $this->chartData['countries'] = ['labels' => ['Tidak ada data'], 'data' => [0]];
            $this->chartData['trends'] = ['labels' => [date('Y-m-d')], 'data' => [0]];
        }
    }

    public function render()
    {
        return view('livewire.crowdsec-dashboard')->layout('layouts::app');
    }
}
