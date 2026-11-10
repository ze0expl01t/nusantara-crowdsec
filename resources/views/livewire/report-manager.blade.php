<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        
        <!-- Header & Tombol Export -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <flux:heading size="xl">Security Reports (Alerts)</flux:heading>
                <flux:subheading>Statistics and history of attacks detected by CrowdSec.</flux:subheading>
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

        <!-- Kartu Statistik & Chart -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <flux:card class="flex items-center gap-4">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <flux:icon.shield-exclamation class="w-8 h-8" />
                </div>
                <div>
                    <flux:heading size="lg">Total Alerts</flux:heading>
                    <flux:text class="text-3xl font-bold mt-1 text-gray-900 dark:text-white">{{ $totalAlerts }}</flux:text>
                </div>
            </flux:card>

            <flux:card class="md:col-span-2 flex flex-col p-4" wire:ignore>
                <flux:heading size="md" class="mb-2">Top 5 Attack Scenarios</flux:heading>
                <div class="w-full h-48 relative">
                    <canvas id="reportChart"></canvas>
                </div>
            </flux:card>
        </div>

        <!-- Tabel Data -->
        <flux:card class="p-0 overflow-hidden mt-6">
            <div class="p-6 border-b border-zinc-200 dark:border-zinc-700">
                <flux:heading size="lg">Latest Alerts</flux:heading>
            </div>
            <div class="px-6 py-2">
                <flux:table>
                    <flux:table.columns>
                        <flux:table.column>Time</flux:table.column>
                        <flux:table.column>IP Penyerang</flux:table.column>
                        <flux:table.column>Country</flux:table.column>
                        <flux:table.column>Skenario</flux:table.column>
                        <flux:table.column>Decision</flux:table.column>
                    </flux:table.columns>
                    <flux:table.rows>
                        @forelse ($paginatedAlerts as $alert)
                            <flux:table.row :key="$alert['id'] ?? md5(json_encode($alert))">
                                <flux:table.cell class="whitespace-nowrap">{{ !empty($alert['created_at']) ? \Carbon\Carbon::parse($alert['created_at'])->format('d/m/Y H:i') : '-' }}</flux:table.cell>
                                <flux:table.cell class="font-mono">{{ $alert['source']['value'] ?? 'N/A' }}</flux:table.cell>
                                <flux:table.cell>{{ $alert['source']['cn'] ?? '-' }}</flux:table.cell>
                                <flux:table.cell><flux:badge color="zinc" size="sm">{{ $alert['scenario'] ?? 'Unknown' }}</flux:badge></flux:table.cell>
                                <flux:table.cell>
                                    @if(!empty($alert['decisions'][0]['type']))
                                        <flux:badge color="red">{{ $alert['decisions'][0]['type'] }}</flux:badge>
                                    @else
                                        <flux:badge color="green">none</flux:badge>
                                    @endif
                                </flux:table.cell>
                            </flux:table.row>
                        @empty
                            <flux:table.row>
                                <flux:table.cell colspan="5" class="text-center py-6 text-zinc-500">No alert data available.</flux:table.cell>
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
        if (ctxReport) {
            const chartData = @json($chartData);
            const isDark = document.documentElement.classList.contains('dark');
            const textColor = isDark ? '#e5e7eb' : '#374151';

            new Chart(ctxReport, {
                type: 'bar',
                data: {
                    labels: chartData.scenarios.labels,
                    datasets: [{
                        label: 'Jumlah Serangan',
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
