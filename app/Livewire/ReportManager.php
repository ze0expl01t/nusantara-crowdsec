<?php

namespace App\Livewire;

use App\Exports\AlertsExport;
use App\Services\CrowdSecLapiClient;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class ReportManager extends Component
{
    use WithPagination;

    public int $perPage = 15;

    public string $search = '';

    public string $selectedScenario = '';

    public string $selectedCountry = '';

    public string $decisionFilter = 'all';

    public array $chartData = [
        'scenarios' => ['labels' => [], 'data' => []],
    ];

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedSelectedScenario(): void
    {
        $this->resetPage();
    }

    public function updatedSelectedCountry(): void
    {
        $this->resetPage();
    }

    public function updatedDecisionFilter(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->search = '';
        $this->selectedScenario = '';
        $this->selectedCountry = '';
        $this->decisionFilter = 'all';
        $this->resetPage();
    }

    public function exportPdf(CrowdSecLapiClient $client)
    {
        $alerts = $this->filterAlerts($this->getAlertsData($client));
        $topScenarios = $this->buildTopScenarios($alerts);

        $barConfig = [
            'type' => 'bar',
            'data' => [
                'labels' => $topScenarios['labels'],
                'datasets' => [[
                    'label' => 'Alerts Count',
                    'data' => $topScenarios['data'],
                    'backgroundColor' => '#3b82f6',
                ]],
            ],
            'options' => ['plugins' => ['legend' => ['display' => false]]],
        ];
        $barChartUrl = 'https://quickchart.io/chart?w=400&h=200&bkg=white&c=' . urlencode(json_encode($barConfig));

        $countries = [];
        foreach ($alerts as $a) {
            $cn = trim((string) ($a['source']['cn'] ?? ''));
            $cn = $cn !== '' ? $cn : 'Unknown';
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
                    'backgroundColor' => ['#ef4444', '#f97316', '#f59e0b', '#84cc16', '#06b6d4'],
                ]],
            ],
        ];
        $donutChartUrl = 'https://quickchart.io/chart?w=400&h=200&bkg=white&c=' . urlencode(json_encode($donutConfig));

        $barBase64 = $this->fetchChartAsBase64($barChartUrl, 'bar');
        $donutBase64 = $this->fetchChartAsBase64($donutChartUrl, 'donut');

        $pdf = Pdf::loadView('pdf.alerts-pdf', [
            'alerts' => $alerts,
            'barBase64' => $barBase64,
            'donutBase64' => $donutBase64,
        ]);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'crowdsec-alerts-' . date('Ymd') . '.pdf');
    }

    public function exportExcel(CrowdSecLapiClient $client)
    {
        $alerts = $this->filterAlerts($this->getAlertsData($client));

        return Excel::download(new AlertsExport($alerts), 'crowdsec-alerts-' . date('Ymd') . '.xlsx');
    }

    private function normalizeScenarioName(mixed $raw): string
    {
        $value = trim((string) ($raw ?? ''));
        if ($value === '' || $value === '0' || str_starts_with($value, '-')) {
            return 'Unknown';
        }

        $clean = trim(basename($value));
        if ($clean === '' || $clean === '.' || $clean === '..' || $clean === '0' || str_starts_with($clean, '-')) {
            return 'Unknown';
        }

        return $clean;
    }

    private function buildTopScenarios(array $alerts): array
    {
        $scenarios = [];
        foreach ($alerts as $a) {
            $sc = $this->normalizeScenarioName($a['scenario'] ?? null);
            $scenarios[$sc] = ($scenarios[$sc] ?? 0) + 1;
        }
        arsort($scenarios);
        $topScenarios = array_slice($scenarios, 0, 5, true);

        return [
            'labels' => array_keys($topScenarios),
            'data' => array_values($topScenarios),
        ];
    }

    private function filterAlerts(array $alerts): array
    {
        $search = strtolower(trim($this->search));

        return array_values(array_filter($alerts, function (array $alert) use ($search): bool {
            $ip = strtolower((string) ($alert['source']['value'] ?? ''));
            $cn = trim((string) ($alert['source']['cn'] ?? ''));
            $cnLabel = $cn !== '' ? $cn : 'Unknown';
            $rawScenario = (string) ($alert['scenario'] ?? '');
            $normScenario = $this->normalizeScenarioName($rawScenario);
            $hasDecision = ! empty($alert['decisions'][0]['type']);

            if ($search !== '') {
                $haystack = $ip . ' ' . strtolower($cnLabel) . ' ' . strtolower($rawScenario) . ' ' . strtolower($normScenario);
                if (! str_contains($haystack, $search)) {
                    return false;
                }
            }

            if ($this->selectedScenario !== '' && $normScenario !== $this->selectedScenario) {
                return false;
            }

            if ($this->selectedCountry !== '' && $cnLabel !== $this->selectedCountry) {
                return false;
            }

            if ($this->decisionFilter === 'banned' && ! $hasDecision) {
                return false;
            }

            if ($this->decisionFilter === 'none' && $hasDecision) {
                return false;
            }

            return true;
        }));
    }

    private function fetchChartAsBase64(string $url, string $chartType): string
    {
        try {
            $res = Http::timeout(5)->get($url);
            if ($res->successful() && $res->body() !== '') {
                return 'data:image/png;base64,' . base64_encode($res->body());
            }
            Log::warning("ReportManager {$chartType} chart returned HTTP " . $res->status());
        } catch (\Throwable $e) {
            Log::warning("ReportManager {$chartType} chart failed: " . $e->getMessage());
        }

        return '';
    }

    private function getAlertsData(CrowdSecLapiClient $client): array
    {
        try {
            $rawData = $client->getAlerts(100);

            return array_values(array_filter($rawData, 'is_array'));
        } catch (\Throwable $e) {
            Log::warning('ReportManager getAlertsData failed: ' . $e->getMessage());

            return [];
        }
    }

    public function render(CrowdSecLapiClient $client)
    {
        $allAlerts = $this->getAlertsData($client);

        $scenarioOptions = [];
        $countryOptions = [];
        foreach ($allAlerts as $alert) {
            $sc = $this->normalizeScenarioName($alert['scenario'] ?? null);
            $cn = trim((string) ($alert['source']['cn'] ?? ''));
            $cn = $cn !== '' ? $cn : 'Unknown';
            $scenarioOptions[$sc] = $sc;
            $countryOptions[$cn] = $cn;
        }
        ksort($scenarioOptions);
        ksort($countryOptions);

        $filteredAlerts = $this->filterAlerts($allAlerts);
        $this->chartData['scenarios'] = $this->buildTopScenarios($filteredAlerts);

        $currentPage = $this->getPage();
        $paginated = new LengthAwarePaginator(
            array_slice($filteredAlerts, ($currentPage - 1) * $this->perPage, $this->perPage),
            count($filteredAlerts),
            $this->perPage,
            $currentPage,
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );

        return view('livewire.report-manager', [
            'paginatedAlerts' => $paginated,
            'totalAlerts' => count($allAlerts),
            'filteredCount' => count($filteredAlerts),
            'scenarioOptions' => array_values($scenarioOptions),
            'countryOptions' => array_values($countryOptions),
        ])->layout('layouts::app');
    }
}
