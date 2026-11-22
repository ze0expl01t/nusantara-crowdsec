<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>CrowdSec Security Report</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #333; margin: 18px; }
        h2 { text-align: center; margin-bottom: 4px; color: #111827; }
        .meta { text-align: center; color: #6b7280; margin-bottom: 12px; font-size: 10px; }
        .filters { background-color: #f9fafb; border: 1px solid #e5e7eb; padding: 8px 10px; margin-bottom: 14px; font-size: 10px; color: #374151; }

        .stats-table { width: 100%; border-collapse: separate; border-spacing: 6px 0; margin: 0 -6px 14px -6px; }
        .stat-cell { width: 25%; border: 1px solid #d1d5db; background-color: #f9fafb; padding: 8px; text-align: center; vertical-align: top; }
        .stat-label { font-size: 9px; text-transform: uppercase; color: #6b7280; margin-bottom: 4px; }
        .stat-value { font-size: 14px; font-weight: bold; color: #111827; }

        .chart-container { width: 100%; margin-bottom: 16px; text-align: center; }
        .chart-box { display: inline-block; width: 46%; vertical-align: top; margin: 0 1%; border: 1px solid #e5e7eb; padding: 8px; box-sizing: border-box; }
        .chart-box img { width: 100%; max-width: 290px; height: auto; }
        .chart-title { font-weight: bold; margin-bottom: 6px; color: #1f2937; }
        .chart-fallback { color: #6b7280; font-size: 10px; padding: 24px 8px; }

        table.data-table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        table.data-table th, table.data-table td { border: 1px solid #d1d5db; padding: 6px; text-align: left; }
        table.data-table th { background-color: #f3f4f6; font-weight: bold; color: #111827; }
        .mono { font-family: monospace; }
    </style>
</head>
<body>
    @php
        $rows = $normalizedAlerts ?? [];
        $stats = $summary ?? [
            'total_alerts' => count($rows),
            'active_decisions' => 0,
            'no_decision' => count($rows),
            'unique_ips' => 0,
        ];
        $activeFilters = $filtersSummary ?? [
            'search' => 'All',
            'scenario' => 'All Scenarios',
            'country' => 'All Countries',
            'decision' => 'All Decisions',
        ];
    @endphp

    <h2>Security Reports (Alerts) CrowdSec</h2>
    <div class="meta">Printed on: {{ now()->format('d/m/Y H:i') }} | Filtered Alerts: {{ $stats['total_alerts'] }}</div>

    <div class="filters">
        <strong>Active Filters:</strong>
        Search: <strong>{{ $activeFilters['search'] }}</strong> |
        Scenario: <strong>{{ $activeFilters['scenario'] }}</strong> |
        Country: <strong>{{ $activeFilters['country'] }}</strong> |
        Decision: <strong>{{ $activeFilters['decision'] }}</strong>
    </div>

    <table class="stats-table">
        <tr>
            <td class="stat-cell">
                <div class="stat-label">Filtered Alerts</div>
                <div class="stat-value">{{ $stats['total_alerts'] }}</div>
            </td>
            <td class="stat-cell">
                <div class="stat-label">Active Decisions</div>
                <div class="stat-value">{{ $stats['active_decisions'] }}</div>
            </td>
            <td class="stat-cell">
                <div class="stat-label">No Decision</div>
                <div class="stat-value">{{ $stats['no_decision'] }}</div>
            </td>
            <td class="stat-cell">
                <div class="stat-label">Unique IPs</div>
                <div class="stat-value">{{ $stats['unique_ips'] }}</div>
            </td>
        </tr>
    </table>

    <div class="chart-container">
        <div class="chart-box">
            <div class="chart-title">Top 5 Attacker Countries</div>
            @if(!empty($donutBase64))
                <img src="{{ $donutBase64 }}" alt="Country Chart">
            @else
                <div class="chart-fallback">Chart image unavailable in current environment.</div>
            @endif
        </div>
        <div class="chart-box">
            <div class="chart-title">Top 5 Attack Scenarios</div>
            @if(!empty($barBase64))
                <img src="{{ $barBase64 }}" alt="Scenario Chart">
            @else
                <div class="chart-fallback">Chart image unavailable in current environment.</div>
            @endif
        </div>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>Time</th>
                <th>Attacker IP</th>
                <th>Country</th>
                <th>Scenario</th>
                <th>Decision</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rows as $row)
                <tr>
                    <td>{{ $row['time'] }}</td>
                    <td class="mono">{{ $row['ip'] }}</td>
                    <td>{{ $row['country'] }}</td>
                    <td>{{ $row['scenario'] }}</td>
                    <td>{{ $row['decision'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 12px; color: #6b7280;">No alert records match the selected filters.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
