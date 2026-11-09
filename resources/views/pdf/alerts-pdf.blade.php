<!DOCTYPE html>
<html>
<head>
    <title>Laporan Keamanan CrowdSec</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #333; }
        h2 { text-align: center; margin-bottom: 5px; }
        .meta { text-align: center; color: #777; margin-bottom: 20px; }
        
        .chart-container { width: 100%; margin-bottom: 20px; text-align: center; }
        .chart-box { display: inline-block; width: 45%; vertical-align: top; margin: 0 2%; }
        .chart-box img { width: 100%; max-width: 300px; height: auto; }
        .chart-title { font-weight: bold; margin-bottom: 5px; }

        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 6px; text-align: left; }
        th { background-color: #f5f5f5; font-weight: bold; }
    </style>
</head>
<body>
    <h2>Security Reports (Alerts) CrowdSec</h2>
    <div class="meta">Printed on: {{ now()->format('d/m/Y H:i') }} | Total Alerts: {{ count($alerts) }}</div>

    <!-- BAGIAN CHART (MENGGUNAKAN BASE64) -->
    <div class="chart-container">
        @if($donutBase64)
            <div class="chart-box">
                <div class="chart-title">Top 5 Attacker Countries</div>
                <img src="{{ $donutBase64 }}" alt="Country Chart">
            </div>
        @endif
        @if($barBase64)
            <div class="chart-box">
                <div class="chart-title">Top 5 Attack Scenarios</div>
                <img src="{{ $barBase64 }}" alt="Skenario Chart">
            </div>
        @endif
    </div>

    <!-- BAGIAN TABEL -->
    <table>
        <thead>
            <tr>
                <th>Time</th>
                <th>IP Penyerang</th>
                <th>Country</th>
                <th>Skenario</th>
                <th>Decision</th>
            </tr>
        </thead>
        <tbody>
            @foreach($alerts as $alert)
                <tr>
                    <td>{{ !empty($alert['created_at']) ? \Carbon\Carbon::parse($alert['created_at'])->format('d/m/Y H:i') : '-' }}</td>
                    <td>{{ $alert['source']['value'] ?? 'N/A' }}</td>
                    <td>{{ $alert['source']['cn'] ?? '-' }}</td>
                    <td>{{ $alert['scenario'] ?? 'Unknown' }}</td>
                    <td>{{ $alert['decisions'][0]['type'] ?? 'none' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
