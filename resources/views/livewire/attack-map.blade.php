<div class="py-12" wire:poll.15s="refreshMap">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <flux:heading size="xl">Live Attack Map</flux:heading>
                <flux:subheading>Geographical visualization of attack sources (Update: {{ $lastUpdated ?: '-' }}).</flux:subheading>
            </div>
            <flux:button wire:click="refreshMap" icon="arrow-path" variant="primary" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="refreshMap">Refresh Map</span>
                <span wire:loading wire:target="refreshMap">Loading...</span>
            </flux:button>
        </div>

        @if(!$isConnected)
            <flux:callout color="red" icon="exclamation-triangle">Gagal terhubung ke CrowdSec LAPI. Peta tidak dapat dimuat.</flux:callout>
        @endif

        <!-- Container Peta -->
        <flux:card class="p-4 relative">
            <div class="absolute top-6 right-6 z-10 flex gap-2">
                <flux:badge color="red" size="md" class="shadow-lg">Total Blocked: {{ number_format($totalAttacks) }}</flux:badge>
                <flux:badge color="zinc" size="md" class="shadow-lg">Samples: {{ count($attackMapData) }} IP</flux:badge>
            </div>
            <div id="attackMap" class="w-full h-[600px] rounded-lg bg-zinc-950 border border-zinc-800" wire:ignore></div>
        </flux:card>
    </div>

    @assets
    <script src="https://cdn.jsdelivr.net/npm/echarts@4.9.0/dist/echarts.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/echarts@4.9.0/map/js/world.js"></script>
    @endassets

    @script
    <script>
        function renderAttackMap() {
            const mapData = $wire.attackMapData;
            const chartDom = document.getElementById('attackMap');
            if(!chartDom) return;
            
            if(window.attackMapChart) window.attackMapChart.dispose();
            window.attackMapChart = echarts.init(chartDom);

            const heatmapData = $wire.heatmapData;
            const maxHeat = $wire.maxHeat;

            const option = {
                backgroundColor: 'transparent',
                tooltip: {
                    trigger: 'item',
                    backgroundColor: 'rgba(24, 24, 27, 0.9)',
                    borderColor: '#ef4444',
                    textStyle: { color: '#fff' },
                    formatter: function (params) {
                        if (params.seriesType === 'map') {
                            return params.name + ': <b>' + (params.value || 0) + '</b> attacks';
                        }
                        if (params.seriesType === 'effectScatter') {
                            return '<b>IP: ' + params.name + '</b><br/>Country: ' + params.value[2];
                        }
                        return params.name;
                    }
                },
                visualMap: {
                    min: 0,
                    max: maxHeat,
                    text: ['High', 'Low'],
                    realtime: false,
                    calculable: true,
                    inRange: {
                        color: ['#18181b', '#7f1d1d', '#ef4444'] // Hitam -> Merah Gelap -> Merah Menyala
                    },
                    textStyle: { color: '#a1a1aa' },
                    left: 20,
                    bottom: 20
                },
                geo: {
                    map: 'world',
                    roam: true,
                    itemStyle: { 
                        areaColor: '#18181b', 
                        borderColor: '#3f3f46',
                        borderWidth: 0.5
                    },
                    emphasis: { 
                        itemStyle: { areaColor: '#27272a' } 
                    }
                },
                series: [
                    {
                        type: 'map',
                        map: 'world',
                        geoIndex: 0,
                        data: heatmapData
                    },
                    {
                        type: 'lines',
                        zlevel: 2,
                        effect: {
                            show: true,
                            period: 3,
                            trailLength: 0.4,
                            symbol: 'arrow',
                            symbolSize: 6,
                        },
                        lineStyle: { color: '#ef4444', width: 1, opacity: 0.6, curveness: 0.2 },
                        data: $wire.attackMapData
                    },
                    {
                        type: 'effectScatter',
                        coordinateSystem: 'geo',
                        zlevel: 3,
                        rippleEffect: { brushType: 'stroke', scale: 3 },
                        symbolSize: 5,
                        itemStyle: { color: '#ef4444' },
                        data: $wire.attackMapData.map(item => ({
                            name: item.ip,
                            value: [item.coords[0][0], item.coords[0][1], item.countryName]
                        }))
                    }
                ]
            };
            window.attackMapChart.setOption(option);
        }

        renderAttackMap();
        $wire.on('map-data-updated', () => { setTimeout(renderAttackMap, 200); });
        
        window.addEventListener('resize', () => {
            if(window.attackMapChart) window.attackMapChart.resize();
        });
    </script>
    @endscript
</div>
