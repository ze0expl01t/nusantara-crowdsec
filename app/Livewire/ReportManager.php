<?php
namespace App\Livewire;

use App\Services\CrowdSecLapiClient;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Pagination\LengthAwarePaginator;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AlertsExport;
use Illuminate\Support\Facades\Http;

class ReportManager extends Component
{
    use WithPagination;
    public int $perPage = 15;
    
    public array $chartData = [
        'scenarios' => ['labels' => [], 'data' => []],
    ];

    public function exportPdf(CrowdSecLapiClient $client)
    {
        $alerts = $this->getAlertsData($client);
        
        // 1. Bar Chart (Skenario)
        $scenarios = [];
        foreach ($alerts as $a) {
            $sc = $a['scenario'] ?? 'Unknown';
            $scenarios[$sc] = ($scenarios[$sc] ?? 0) + 1;
        }
        arsort($scenarios);
        $topScenarios = array_slice($scenarios, 0, 5, true);
        $cleanScenarios = array_map(fn($key) => basename($key), array_keys($topScenarios));

        $barConfig = [
            'type' => 'bar',
            'data' => [
                'labels' => $cleanScenarios,
                'datasets' => [['label' => 'Jumlah', 'data' => array_values($topScenarios), 'backgroundColor' => '#3b82f6']]
            ],
            'options' => ['plugins' => ['legend' => ['display' => false]]]
        ];
        $barChartUrl = 'https://quickchart.io/chart?w=400&h=200&bkg=white&c=' . urlencode(json_encode($barConfig));

        // 2. Donut Chart (Country)
        $countries = [];
        foreach ($alerts as $a) {
            $cn = $a['source']['cn'] ?? 'Unknown';
            $countries[$cn] = ($countries[$cn] ?? 0) + 1;
        }
        arsort($countries);
        $topCountries = array_slice($countries, 0, 5, true);

        $donutConfig = [
            'type' => 'doughnut',
            'data' => [
                'labels' => array_keys($topCountries),
                'datasets' => [[
                    'data' => array_values($topCountries), 
                    'backgroundColor' => ['#ef4444', '#f97316', '#f59e0b', '#84cc16', '#06b6d4']
                ]]
            ]
        ];
        $donutChartUrl = 'https://quickchart.io/chart?w=400&h=200&bkg=white&c=' . urlencode(json_encode($donutConfig));

        // 3. DOWNLOAD GAMBAR & JADIKAN BASE64
        try {
            $barImageContent = Http::get($barChartUrl)->body();
            $barBase64 = 'data:image/png;base64,' . base64_encode($barImageContent);
        } catch (\Exception $e) {
            $barBase64 = ''; // Fallback jika gagal
        }

        try {
            $donutImageContent = Http::get($donutChartUrl)->body();
            $donutBase64 = 'data:image/png;base64,' . base64_encode($donutImageContent);
        } catch (\Exception $e) {
            $donutBase64 = ''; // Fallback jika gagal
        }

        $pdf = Pdf::loadView('pdf.alerts-pdf', [
            'alerts' => $alerts,
            'barBase64' => $barBase64,
            'donutBase64' => $donutBase64
        ]);
        
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'crowdsec-alerts-' . date('Ymd') . '.pdf');
    }

    public function exportExcel(CrowdSecLapiClient $client)
    {
        $alerts = $this->getAlertsData($client);
        return Excel::download(new AlertsExport($alerts), 'crowdsec-alerts-' . date('Ymd') . '.xlsx');
    }

    private function getAlertsData(CrowdSecLapiClient $client): array
    {
        try {
            $rawData = $client->getAlerts(100);
            return array_values(array_filter($rawData, 'is_array'));
        } catch (\Throwable $e) {
            return [];
        }
    }

    public function render(CrowdSecLapiClient $client)
    {
        $alerts = $this->getAlertsData($client);
        
        $scenarios = [];
        foreach ($alerts as $a) {
            $sc = $a['scenario'] ?? 'Unknown';
            $scenarios[$sc] = ($scenarios[$sc] ?? 0) + 1;
        }
        arsort($scenarios);
        $topScenarios = array_slice($scenarios, 0, 5, true);
        $cleanScenarios = array_map(fn($key) => basename($key), array_keys($topScenarios));

        $this->chartData['scenarios'] = [
            'labels' => $cleanScenarios,
            'data'   => array_values($topScenarios)
        ];

        $currentPage = $this->getPage();
        $paginated = new LengthAwarePaginator(
            array_slice($alerts, ($currentPage - 1) * $this->perPage, $this->perPage),
            count($alerts),
            $this->perPage,
            $currentPage,
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );

        return view('livewire.report-manager', [
            'paginatedAlerts' => $paginated,
            'totalAlerts' => count($alerts)
        ])->layout('layouts::app');
    }
}
