<?php
namespace App\Livewire;

use App\Models\Setting;
use Livewire\Component;
use App\Services\CrowdSecLapiClient;
use App\Services\NotificationService;

class NotificationSettings extends Component
{
    public $email_enabled, $email_recipient;
    public $smtp_host, $smtp_port, $smtp_username, $smtp_password, $smtp_encryption, $smtp_from_address, $smtp_from_name;
    
    public $telegram_enabled, $telegram_token, $telegram_chat_id;
    
    public $scenarios = [];
    public $available_scenarios = [];

    public function mount(CrowdSecLapiClient $client)
    {
        // Email & SMTP
        $this->email_enabled = Setting::get('notify_email_enabled', 'false') === 'true';
        $this->email_recipient = Setting::get('notify_email_recipient', '');
        $this->smtp_host = Setting::get('smtp_host', '');
        $this->smtp_port = Setting::get('smtp_port', '587');
        $this->smtp_username = Setting::get('smtp_username', '');
        $this->smtp_password = Setting::get('smtp_password', '');
        $this->smtp_encryption = Setting::get('smtp_encryption', 'tls');
        $this->smtp_from_address = Setting::get('smtp_from_address', '');
        $this->smtp_from_name = Setting::get('smtp_from_name', 'CrowdSec Portal');

        // Telegram
        $this->telegram_enabled = Setting::get('notify_telegram_enabled', 'false') === 'true';
        $this->telegram_token = Setting::get('notify_telegram_token', '');
        $this->telegram_chat_id = Setting::get('notify_telegram_chat_id', '');
        
        // Scenarios
        $this->scenarios = json_decode(Setting::get('notify_scenarios', '[]'), true) ?: [];
        $alerts = $client->getAlerts(100);
        $scenarios = array_column($alerts, 'scenario');
        $this->available_scenarios = array_values(array_unique(array_filter($scenarios)));
        sort($this->available_scenarios);
    }

    public function testSmtp(NotificationService $notifier)
    {
        if (!$this->email_recipient) {
            session()->flash('error', 'Please enter a recipient email first.');
            return;
        }

        // Simpan sementara untuk test
        $this->save();

        if ($notifier->testEmail($this->email_recipient)) {
            session()->flash('message', 'Test email sent successfully to ' . $this->email_recipient);
        } else {
            session()->flash('error', 'SMTP connection failed. Check your credentials and logs.');
        }
    }

    public function save()
    {
        // Simpan Email & SMTP
        Setting::updateOrCreate(['key' => 'notify_email_enabled'], ['value' => $this->email_enabled ? 'true' : 'false']);
        Setting::updateOrCreate(['key' => 'notify_email_recipient'], ['value' => $this->email_recipient]);
        Setting::updateOrCreate(['key' => 'smtp_host'], ['value' => $this->smtp_host]);
        Setting::updateOrCreate(['key' => 'smtp_port'], ['value' => $this->smtp_port]);
        Setting::updateOrCreate(['key' => 'smtp_username'], ['value' => $this->smtp_username]);
        Setting::updateOrCreate(['key' => 'smtp_password'], ['value' => $this->smtp_password]);
        Setting::updateOrCreate(['key' => 'smtp_encryption'], ['value' => $this->smtp_encryption]);
        Setting::updateOrCreate(['key' => 'smtp_from_address'], ['value' => $this->smtp_from_address]);
        Setting::updateOrCreate(['key' => 'smtp_from_name'], ['value' => $this->smtp_from_name]);

        // Simpan Telegram
        Setting::updateOrCreate(['key' => 'notify_telegram_enabled'], ['value' => $this->telegram_enabled ? 'true' : 'false']);
        Setting::updateOrCreate(['key' => 'notify_telegram_token'], ['value' => $this->telegram_token]);
        Setting::updateOrCreate(['key' => 'notify_telegram_chat_id'], ['value' => $this->telegram_chat_id]);

        // Simpan Scenarios
        Setting::updateOrCreate(['key' => 'notify_scenarios'], ['value' => json_encode($this->scenarios)]);

        session()->flash('message', 'Notification settings saved successfully.');
    }

    public function render()
    {
        return view('livewire.notification-settings')->layout('layouts::app');
    }
}
