<?php
namespace App\Livewire;

use App\Models\Setting;
use Livewire\Component;
use App\Services\CrowdSecLapiClient;

class NotificationSettings extends Component
{
    public $email_enabled, $email_recipient;
    public $telegram_enabled, $telegram_token, $telegram_chat_id;
    public $scenarios = [];
    public $available_scenarios = [];

    public function mount(CrowdSecLapiClient $client)
    {
        $this->email_enabled = Setting::get('notify_email_enabled', 'false') === 'true';
        $this->email_recipient = Setting::get('notify_email_recipient', '');
        $this->telegram_enabled = Setting::get('notify_telegram_enabled', 'false') === 'true';
        $this->telegram_token = Setting::get('notify_telegram_token', '');
        $this->telegram_chat_id = Setting::get('notify_telegram_chat_id', '');
        
        $this->scenarios = json_decode(Setting::get('notify_scenarios', '[]'), true) ?: [];

        $alerts = $client->getAlerts(100);
        $scenarios = array_column($alerts, 'scenario');
        $this->available_scenarios = array_values(array_unique(array_filter($scenarios)));
        sort($this->available_scenarios);
    }

    public function save()
    {
        Setting::updateOrCreate(['key' => 'notify_email_enabled'], ['value' => $this->email_enabled ? 'true' : 'false']);
        Setting::updateOrCreate(['key' => 'notify_email_recipient'], ['value' => $this->email_recipient]);
        Setting::updateOrCreate(['key' => 'notify_telegram_enabled'], ['value' => $this->telegram_enabled ? 'true' : 'false']);
        Setting::updateOrCreate(['key' => 'notify_telegram_token'], ['value' => $this->telegram_token]);
        Setting::updateOrCreate(['key' => 'notify_telegram_chat_id'], ['value' => $this->telegram_chat_id]);
        Setting::updateOrCreate(['key' => 'notify_scenarios'], ['value' => json_encode($this->scenarios)]);

        session()->flash('message', 'Notification settings saved successfully.');
    }

    public function render()
    {
        return view('livewire.notification-settings')->layout('layouts::app');
    }
}
