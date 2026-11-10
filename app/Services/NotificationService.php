<?php
namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    public function send($alert)
    {
        $allowedScenarios = json_decode(Setting::get('notify_scenarios', '[]'), true);
        if (empty($allowedScenarios)) return; 

        $scenario = $alert['scenario'] ?? '';
        $cleanScenario = basename($scenario); 

        if (!in_array($scenario, $allowedScenarios) && !in_array($cleanScenario, $allowedScenarios)) {
            return; 
        }

        $ip = $alert['source']['value'] ?? 'Unknown';
        $country = $alert['source']['cn'] ?? 'Unknown';
        $message = "🚨 *CrowdSec Alert*\nScenario: {$scenario}\nIP: {$ip}\nCountry: {$country}\nTime: " . now()->format('Y-m-d H:i:s');

        if (Setting::get('notify_email_enabled') === 'true') {
            $recipient = Setting::get('notify_email_recipient');
            if ($recipient) {
                try {
                    Mail::raw($message, function ($mail) use ($recipient, $scenario) {
                        $mail->to($recipient)->subject("CrowdSec Alert: {$scenario}");
                    });
                } catch (\Exception $e) {
                    Log::error("Email Notification failed: " . $e->getMessage());
                }
            }
        }

        if (Setting::get('notify_telegram_enabled') === 'true') {
            $token = Setting::get('notify_telegram_token');
            $chatId = Setting::get('notify_telegram_chat_id');
            if ($token && $chatId) {
                try {
                    Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
                        'chat_id' => $chatId,
                        'text' => $message,
                        'parse_mode' => 'Markdown'
                    ]);
                } catch (\Exception $e) {
                    Log::error("Telegram Notification failed: " . $e->getMessage());
                }
            }
        }
    }
}
