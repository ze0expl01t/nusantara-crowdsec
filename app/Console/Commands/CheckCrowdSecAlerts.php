<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\CrowdSecLapiClient;
use App\Services\NotificationService;
use App\Models\Setting;

class CheckCrowdSecAlerts extends Command
{
    protected $signature = 'crowdsec:check-alerts';
    protected $description = 'Cek alert baru dari LAPI dan kirim notifikasi';

    public function handle(CrowdSecLapiClient $client, NotificationService $notifier)
    {
        $lastAlertId = (int) Setting::get('last_notified_alert_id', 0);
        $alerts = $client->getAlerts(20);
        
        if (empty($alerts)) return;

        usort($alerts, fn($a, $b) => ($a['id'] ?? 0) <=> ($b['id'] ?? 0));
        $newAlertsFound = false;

        foreach ($alerts as $alert) {
            $alertId = $alert['id'] ?? 0;
            if ($alertId > $lastAlertId) {
                $notifier->send($alert);
                $lastAlertId = $alertId;
                $newAlertsFound = true;
            }
        }

        if ($newAlertsFound) {
            Setting::updateOrCreate(['key' => 'last_notified_alert_id'], ['value' => $lastAlertId]);
        }
    }
}
