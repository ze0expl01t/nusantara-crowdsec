<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        <!-- Header & Export Actions -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <flux:heading size="xl">Security Reports (Alerts)</flux:heading>
                <flux:subheading>Statistics, filtering, and history of security alerts detected by CrowdSec (up to 100 latest alerts).</flux:subheading>
            </div>

            <div class="flex flex-wrap gap-2">
                <flux:button wire:click="exportPdf" icon="document-arrow-down" variant="primary" class="cursor-pointer">
                    Export PDF
                </flux:button>
                <flux:button wire:click="exportExcel" icon="table-cells" variant="filled" class="cursor-pointer">
                    Export Excel
                </flux:button>
            </div>
        </div>

        <!-- Summary Card & Chart -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <flux:card class="flex items-center gap-4">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <flux:icon.shield-exclamation class="w-8 h-8" />
                </div>
                <div>
                    <flux:heading size="lg">Total Alerts</flux:heading>
                    <flux:text class="text-3xl font-bold mt-1 text-gray-900 dark:text-white">{{ $totalAlerts }}</flux:text>
                    <flux:text class="text-xs text-zinc-500 mt-1">Showing {{ $filteredCount }} matching records</flux:text>
                </div>
            </flux:card>

            <flux:card class="md:col-span-2 flex flex-col p-4" wire:ignore>
                <flux:heading size="md" class="mb-2">Top 5 Attack Scenarios</flux:heading>
                <div class="w-full h-48 relative">
                    <canvas id="reportChart"></canvas>
                </div>
            </flux:card>
        </div>

        <!-- Filter Bar & Alerts Table -->
        <flux:card class="p-0 overflow-hidden mt-6">
            <div class="p-6 border-b border-zinc-200 dark:border-zinc-700 space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                    <flux:heading size="lg">Latest Alerts</flux:heading>
                    <flux:text class="text-xs text-zinc-500">Filtered records: {{ $filteredCount }} / {{ $totalAlerts }}</flux:text>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
                    <flux:input
                        wire:model.live.debounce.300ms="search"
                        placeholder="Search IP, country, scenario..."
                        icon="magnifying-glass"
                    />

                    <flux:select wire:model.live="selectedScenario" placeholder="All Scenarios">
                        <flux:select.option value="">All Scenarios</flux:select.option>
                        @foreach ($scenarioOptions as $scOption)
                            <flux:select.option value="{{ $scOption }}">{{ $scOption }}</flux:select.option>
                        @endforeach
                    </flux:select>

                    <flux:select wire:model.live="selectedCountry" placeholder="All Countries">
                        <flux:select.option value="">All Countries</flux:select.option>
                        @foreach ($countryOptions as $cnOption)
                            <flux:select.option value="{{ $cnOption }}">{{ $cnOption }}</flux:select.option>
                        @endforeach
                    </flux:select>

                    <flux:select wire:model.live="decisionFilter">
                        <flux:select.option value="all">All Decisions</flux:select.option>
                        <flux:select.option value="banned">Banned / Active Decision</flux:select.option>
                        <flux:select.option value="none">No Decision (none)</flux:select.option>
                    </flux:select>

                    <flux:button wire:click="resetFilters" variant="ghost" icon="arrow-path" class="cursor-pointer">
                        Reset
                    </flux:button>
                </div>
            </div>

            <div class="px-6 py-2">
                <flux:table>
                    <flux:table.columns>
                        <flux:table.column>Time</flux:table.column>
                        <flux:table.column>Attacker IP</flux:table.column>
                        <flux:table.column>Country</flux:table.column>
                        <flux:table.column>Scenario</flux:table.column>
                        <flux:table.column>Decision</flux:table.column>
                    </flux:table.columns>
                    <flux:table.rows>
                        @forelse ($paginatedAlerts as $alert)
                            @php
                                $alertArr = is_array($alert) ? $alert : [];
                                $countryCode = trim((string) ($alertArr['source']['cn'] ?? ''));
                                $countryDisplay = $countryCode !== '' ? $countryCode : 'Unknown';
                                $scenarioRaw = trim((string) ($alertArr['scenario'] ?? ''));
                                $scenarioDisplay = ($scenarioRaw !== '' && $scenarioRaw !== '0' && !str_starts_with($scenarioRaw, '-'))
                                    ? basename($scenarioRaw)
                                    : 'Unknown';
                                $decisionType = trim((string) ($alertArr['decisions'][0]['type'] ?? ''));
                            @endphp
                            <flux:table.row :key="$alertArr['id'] ?? md5(json_encode($alertArr))">
                                <flux:table.cell class="whitespace-nowrap">{{ !empty($alertArr['created_at']) ? \Carbon\Carbon::parse((string) $alertArr['created_at'])->format('d/m/Y H:i') : '-' }}</flux:table.cell>
                                <flux:table.cell class="font-mono">{{ $alertArr['source']['value'] ?? 'N/A' }}</flux:table.cell>
                                <flux:table.cell>{{ $countryDisplay }}</flux:table.cell>
                                <flux:table.cell><flux:badge color="zinc" size="sm">{{ $scenarioDisplay }}</flux:badge></flux:table.cell>
                                <flux:table.cell>
                                    @if($decisionType !== '')
                                        <flux:badge color="red">{{ $decisionType }}</flux:badge>
                                    @else
                                        <flux:badge color="green">none</flux:badge>
                                    @endif
                                </flux:table.cell>
                            </flux:table.row>
                        @empty
                            <flux:table.row>
                                <flux:table.cell colspan="5" class="text-center py-6 text-zinc-500">No alert data matches the current filter.</flux:table.cell>
                            </flux:table.row>
                        @endforelse
                    </flux:table.rows>
                </flux:table>
            </div>
            <div class="p-4 border-t border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-900/50">
                {{ $paginatedAlerts->links('livewire.pagination.tailwind') }}
            </div>
        </flux:card>
    </div>

    @assets
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @endassets

    @script
    <script>
        const ctxReport = document.getElementById('reportChart');
        if (ctxReport && typeof Chart !== 'undefined') {
            if (window.__cscliReportChart) {
                window.__cscliReportChart.destroy();
            }

            const chartData = @json($chartData);
            const isDark = document.documentElement.classList.contains('dark');
            const textColor = isDark ? '#e5e7eb' : '#374151';

            window.__cscliReportChart = new Chart(ctxReport, {
                type: 'bar',
                data: {
                    labels: chartData.scenarios.labels,
                    datasets: [{
                        label: 'Alerts Count',
                        data: chartData.scenarios.data,
                        backgroundColor: '#3b82f6',
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, ticks: { color: textColor }, grid: { color: isDark ? '#374151' : '#e5e7eb' } },
                        x: { ticks: { color: textColor }, grid: { display: false } }
                    }
                }
            });
        }
    </script>
    @endscript
</div>
