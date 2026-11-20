<?php

namespace App\Console\Commands;

use App\Models\Setting;
use App\Services\CrowdSecLapiClient;
use App\Services\NotificationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CheckCrowdSecAlerts extends Command
{
    protected $signature = 'crowdsec:check-alerts';
    protected $description = 'Cek alert baru dari LAPI dan kirim notifikasi';

    public function handle(CrowdSecLapiClient $client, NotificationService $notifier): int
    {
        $lock = Cache::lock('crowdsec:check-alerts:lock', 55);

        if (! $lock->get()) {
            return self::SUCCESS;
        }

        try {
            $lastAlertId = (int) Setting::get('last_notified_alert_id', 0);
            $alerts = $client->getAlerts(20);

            if (empty($alerts)) {
                return self::SUCCESS;
            }

            usort($alerts, fn($a, $b) => ($a['id'] ?? 0) <=> ($b['id'] ?? 0));
            $newAlertsFound = false;

            foreach ($alerts as $alert) {
                $alertId = (int) ($alert['id'] ?? 0);
                if ($alertId > $lastAlertId) {
                    $notifier->send($alert);
                    $lastAlertId = $alertId;
                    $newAlertsFound = true;
                }
            }

            if ($newAlertsFound) {
                Setting::updateOrCreate(['key' => 'last_notified_alert_id'], ['value' => (string) $lastAlertId]);
            }

            return self::SUCCESS;
        } catch (\Throwable $e) {
            Log::warning('CheckCrowdSecAlerts failed: ' . $e->getMessage());
            return self::FAILURE;
        } finally {
            optional($lock)->release();
        }
    }
}
