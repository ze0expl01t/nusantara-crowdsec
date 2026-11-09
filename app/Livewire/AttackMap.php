<?php
namespace App\Livewire;

use App\Services\CrowdSecLapiClient;
use App\Services\GeoIpService;
use Livewire\Component;

class AttackMap extends Component
{
    public bool $isConnected = false;
    public int $totalAttacks = 0;
    public string $lastUpdated = '';
    
    public array $attackMapData = []; // Untuk Garis (Lines)
    public array $heatmapData = [];   // Untuk Heatmap (Country)
    public int $maxHeat = 1;          // Nilai tertinggi untuk skala warna

    public function mount(CrowdSecLapiClient $client, GeoIpService $geo)
    {
        $this->fetchMapData($client, $geo);
    }

    public function refreshMap(CrowdSecLapiClient $client, GeoIpService $geo)
    {
        $this->fetchMapData($client, $geo);
        $this->dispatch('map-data-updated');
    }

    private function fetchMapData(CrowdSecLapiClient $client, GeoIpService $geo)
    {
        $this->isConnected = $client->isConnected();
        
        if ($this->isConnected) {
            $allDecisions = $client->getDecisions();
            $this->totalAttacks = count($allDecisions);
            $this->lastUpdated = now()->format('H:i:s');
            
            $sampleDecisions = array_slice($allDecisions, 0, 50);
            $ipsForMap = array_column($sampleDecisions, 'value');
            
            $this->attackMapData = $geo->getAttackPaths($ipsForMap);

            // Hitung attacks per negara untuk Heatmap
            $countryCounts = [];
            foreach ($this->attackMapData as $attack) {
                $cName = $attack['countryName'];
                if ($cName !== 'Unknown') {
                    $countryCounts[$cName] = ($countryCounts[$cName] ?? 0) + 1;
                }
            }

            // Format data untuk ECharts Map series
            $heatmap = [];
            $this->maxHeat = 1;
            foreach ($countryCounts as $country => $count) {
                $heatmap[] = ['name' => $country, 'value' => $count];
                if ($count > $this->maxHeat) {
                    $this->maxHeat = $count;
                }
            }
            $this->heatmapData = $heatmap;

        } else {
            $this->totalAttacks = 0;
            $this->attackMapData = [];
            $this->heatmapData = [];
        }
    }

    public function render()
    {
        return view('livewire.attack-map')->layout('layouts::app');
    }
}
