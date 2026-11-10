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

        // KIRIM EMAIL (Menggunakan dynamic config dari Database)
        if (Setting::get('notify_email_enabled') === 'true') {
            $recipient = Setting::get('notify_email_recipient');
            if ($recipient) {
                try {
                    // Inject SMTP Config secara dinamis
                    $this->setDynamicSmtpConfig();
                    
                    Mail::raw($message, function ($mail) use ($recipient, $scenario) {
                        $mail->to($recipient)->subject("CrowdSec Alert: {$scenario}");
                    });
                } catch (\Exception $e) {
                    Log::error("Email Notification failed: " . $e->getMessage());
                }
            }
        }

        // KIRIM TELEGRAM
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

    public function testEmail($recipient)
    {
        try {
            $this->setDynamicSmtpConfig();
            Mail::raw("This is a test email from Nusantara CrowdSec Portal.", function ($mail) use ($recipient) {
                $mail->to($recipient)->subject("CrowdSec Portal - SMTP Test");
            });
            return true;
        } catch (\Exception $e) {
            Log::error("SMTP Test Failed: " . $e->getMessage());
            return false;
        }
    }

    private function setDynamicSmtpConfig()
    {
        config([
            'mail.default' => 'smtp',
            'mail.mailers.smtp.host' => Setting::get('smtp_host', '127.0.0.1'),
            'mail.mailers.smtp.port' => Setting::get('smtp_port', 2525),
            'mail.mailers.smtp.username' => Setting::get('smtp_username'),
            'mail.mailers.smtp.password' => Setting::get('smtp_password'),
            'mail.mailers.smtp.encryption' => Setting::get('smtp_encryption'),
            'mail.from.address' => Setting::get('smtp_from_address', 'noreply@localhost'),
            'mail.from.name' => Setting::get('smtp_from_name', 'CrowdSec Portal'),
        ]);
    }
}
