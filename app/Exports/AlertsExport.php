<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AlertsExport implements FromArray, WithHeadings
{
    protected $alerts;

    public function __construct(array $alerts)
    {
        $this->alerts = $alerts;
    }

    public function array(): array
    {
        $data = [];
        foreach ($this->alerts as $alert) {
            $data[] = [
                'Waktu' => !empty($alert['created_at']) ? \Carbon\Carbon::parse($alert['created_at'])->format('d/m/Y H:i') : '-',
                'IP Penyerang' => $alert['source']['value'] ?? 'N/A',
                'Negara' => $alert['source']['cn'] ?? '-',
                'Skenario' => $alert['scenario'] ?? 'Unknown',
                'Keputusan' => $alert['decisions'][0]['type'] ?? 'none',
            ];
        }
        return $data;
    }

    public function headings(): array
    {
        return ['Waktu', 'IP Penyerang', 'Negara', 'Skenario', 'Keputusan'];
    }
}
