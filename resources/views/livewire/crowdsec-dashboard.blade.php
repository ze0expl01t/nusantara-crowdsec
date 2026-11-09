<div class="py-12" wire:poll.15s="refreshData">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <flux:heading size="xl">CrowdSec Security Portal</flux:heading>
                <flux:subheading>Real-time server security activity monitoring. (Update: {{ $lastUpdated ?: '-' }})</flux:subheading>
            </div>
            <flux:button wire:click="refreshData" icon="arrow-path" variant="primary" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="refreshData">Refresh Data</span>
                <span wire:loading wire:target="refreshData">Loading...</span>
            </flux:button>
        </div>

        @if (session()->has('message'))
            <flux:callout color="green" icon="check-circle">{{ session('message') }}</flux:callout>
        @endif
        @if (session()->has('error'))
            <flux:callout color="red" icon="exclamation-triangle">{{ session('error') }}</flux:callout>
        @endif

        <!-- Status Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <flux:card>
                <div class="flex items-center gap-4">
                    <div class="p-3 rounded-full {{ $isConnected ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                        <flux:icon.server class="w-8 h-8" />
                    </div>
                    <div>
                        <flux:heading size="lg">LAPI Status</flux:heading>
                        <flux:text class="mt-1 {{ $isConnected ? 'text-green-600 font-semibold' : 'text-red-600 font-semibold' }}">{{ $isConnected ? 'Connected to CrowdSec' : 'Connection Failed' }}</flux:text>
                    </div>
                </div>
            </flux:card>
            <flux:card>
                <div class="flex items-center gap-4">
                    <div class="p-3 rounded-full bg-red-100 text-red-600">
                        <flux:icon.shield-exclamation class="w-8 h-8" />
                    </div>
                    <div>
                        <flux:heading size="lg">Total Blocked Samples</flux:heading>
                        <flux:text class="text-3xl font-bold mt-1 text-gray-900 dark:text-white">{{ number_format($totalDecisions) }}</flux:text>
                    </div>
                </div>
            </flux:card>
        </div>

        
        

        <!-- CHARTS SECTION -->
        <div class="space-y-6" wire:ignore>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <flux:card class="flex flex-col items-center justify-center p-4">
                    <flux:heading size="md" class="mb-4">Top 5 Attacker Countries</flux:heading>
                    @if(count($chartData['countries']['data']) > 0)
                        <div class="w-full h-72 relative"><canvas id="countryChart"></canvas></div>
                    @else
                        <div class="w-full h-72 flex items-center justify-center text-gray-400">Country data unavailable</div>
                    @endif
                </flux:card>

                <flux:card class="flex flex-col items-center justify-center p-4">
                    <flux:heading size="md" class="mb-4">Top 5 Attack Scenarios</flux:heading>
                    @if(count($chartData['scenarios']['data']) > 0)
                        <div class="w-full h-72 relative"><canvas id="scenarioChart"></canvas></div>
                    @else
                        <div class="w-full h-72 flex items-center justify-center text-gray-400">Scenario data unavailable</div>
                    @endif
                </flux:card>
            </div>
            <div class="grid grid-cols-1 gap-6">
                <flux:card class="flex flex-col items-center justify-center p-4">
                    <flux:heading size="md" class="mb-4">Daily Block Trend</flux:heading>
                    @if(count($chartData['trends']['data']) > 0)
                        <div class="w-full h-64 relative"><canvas id="trendChart"></canvas></div>
                    @else
                        <div class="w-full h-64 flex items-center justify-center text-gray-400">Trend data unavailable</div>
                    @endif
                </flux:card>
            </div>
        </div>

        <!-- Tabel Decision (Bersih & Proporsional) -->
        <flux:card class="p-0 overflow-hidden">
            <div class="p-6 border-b border-zinc-200 dark:border-zinc-700">
                <flux:heading size="lg">Latest Blocklist</flux:heading>
            </div>
            
            <div class="px-6 py-2">
                <flux:table class="table-fixed w-full">
                    <flux:table.columns>
                        <flux:table.column class="w-1/4 py-3 text-sm font-semibold">IP Address</flux:table.column>
                        <flux:table.column class="w-1/4 py-3 text-sm font-semibold">Reason (Scenario)</flux:table.column>
                        <flux:table.column class="w-1/4 py-3 text-center text-sm font-semibold">Country</flux:table.column>
                        <flux:table.column class="w-1/4 py-3 text-right text-sm font-semibold">Action</flux:table.column>
                    </flux:table.columns>
                    
                    <flux:table.rows>
                        @forelse ($decisions as $decision)
                            <flux:table.row :key="$decision['id'] ?? md5(json_encode($decision))" class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                                <flux:table.cell class="py-4 font-mono text-sm text-zinc-900 dark:text-zinc-100 truncate">
                                    {{ $decision['value'] ?? 'N/A' }}
                                </flux:table.cell>
                                <flux:table.cell class="py-4 truncate">
                                    <flux:badge color="zinc" size="sm">{{ basename($decision['scenario'] ?? 'Unknown') }}</flux:badge>
                                </flux:table.cell>
                                <flux:table.cell class="py-4 text-center text-zinc-500">
                                    {{ !empty($decision['country']) && $decision['country'] !== '-' ? $decision['country'] : '-' }}
                                </flux:table.cell>
                                <flux:table.cell class="py-4 text-right">
                                    <flux:badge color="red" class="px-3">ban</flux:badge>
                                </flux:table.cell>
                            </flux:table.row>
                        @empty
                            <flux:table.row>
                                <flux:table.cell colspan="4" class="text-center py-6 text-zinc-500">No recent blocklist data.</flux:table.cell>
                            </flux:table.row>
                        @endforelse
                    </flux:table.rows>
                </flux:table>
            </div>
            
            <div class="p-4 border-t border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-900/50 text-center text-sm text-zinc-500">
                Showing latest 50 records out of {{ number_format($totalDecisions) }} blocked records.
            </div>
        </flux:card>
    </div>
    
    @assets
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @endassets
    @script
    <script>
        function renderCharts() {
            const chartData = $wire.chartData;
            const isDark = document.documentElement.classList.contains('dark');
            const textColor = isDark ? '#e5e7eb' : '#374151';

            const ctxCountry = document.getElementById('countryChart');
            if(ctxCountry && chartData.countries.data.length > 0) {
                if(window.countryChartInstance) window.countryChartInstance.destroy();
                window.countryChartInstance = new Chart(ctxCountry, {
                    type: 'doughnut',
                    data: { labels: chartData.countries.labels, datasets: [{ data: chartData.countries.data, backgroundColor: ['#ef4444', '#f97316', '#f59e0b', '#84cc16', '#06b6d4'], borderWidth: 0 }] },
                    options: { responsive: true, maintainAspectRatio: false, cutout: '65%', plugins: { legend: { position: 'right', labels: { color: textColor, padding: 20 } } } }
                });
            }

            const ctxScenario = document.getElementById('scenarioChart');
            if(ctxScenario && chartData.scenarios.data.length > 0) {
                if(window.scenarioChartInstance) window.scenarioChartInstance.destroy();
                window.scenarioChartInstance = new Chart(ctxScenario, {
                    type: 'radar',
                    data: { labels: chartData.scenarios.labels, datasets: [{ label: 'Serangan', data: chartData.scenarios.data, backgroundColor: 'rgba(59, 130, 246, 0.2)', borderColor: '#3b82f6', pointBackgroundColor: '#3b82f6', borderWidth: 2 }] },
                    options: { responsive: true, maintainAspectRatio: false, scales: { r: { beginAtZero: true, ticks: { display: false }, pointLabels: { color: textColor, font: { size: 12 } }, grid: { color: isDark ? '#374151' : '#e5e7eb' }, angleLines: { color: isDark ? '#374151' : '#e5e7eb' } } }, plugins: { legend: { display: false } } }
                });
            }

            const ctxTrend = document.getElementById('trendChart');
            if(ctxTrend && chartData.trends.data.length > 0) {
                if(window.trendChartInstance) window.trendChartInstance.destroy();
                window.trendChartInstance = new Chart(ctxTrend, {
                    type: 'bar',
                    data: { labels: chartData.trends.labels, datasets: [{ label: 'Jumlah Blokir', data: chartData.trends.data, backgroundColor: '#10b981', borderRadius: 4, barThickness: 40 }] },
                    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { color: textColor }, grid: { color: isDark ? '#374151' : '#e5e7eb' } }, x: { ticks: { color: textColor, maxRotation: 45, minRotation: 45 }, grid: { display: false } } } }
                });
            }
        }
        renderCharts();
        $wire.on('chart-data-updated', () => { setTimeout(renderCharts, 100); });
    </script>
    @endscript
</div>
