<?php
namespace App\Livewire;

use App\Models\Setting;
use App\Services\CrowdSecAutoDetector;
use Livewire\Component;

class SiteSettings extends Component
{
    public $app_name;
    public $institution_name;
    public $logo_url;
    public $hero_title;
    public $hero_subtitle;
    public $footer_text;
    
    public $features = [];

    public function mount()
    {
        $this->app_name = Setting::get('app_name', 'CrowdSec Portal');
        $this->institution_name = Setting::get('institution_name', 'SMK Muhammadiyah 1 Blora');
        $this->logo_url = Setting::get('logo_url', '');
        $this->hero_title = Setting::get('hero_title', 'Dokumentasi & Portal CrowdSec');
        $this->hero_subtitle = Setting::get('hero_subtitle', 'Panduan lengkap untuk mengelola, memantau, dan mengamankan server Anda menggunakan CrowdSec Security Engine.');
        $this->footer_text = Setting::get('footer_text', 'All rights reserved.');

        $savedFeatures = json_decode(Setting::get('landing_features', '[]'), true);
        if (empty($savedFeatures)) {
            $this->features = [
                ['title' => 'Monitoring Real-time', 'desc' => 'Lihat grafik attacks dan status blokir IP secara langsung.', 'color' => 'blue', 'icon' => 'chart-bar'],
                ['title' => 'Manajemen Blokir', 'desc' => 'Kelola daftar IP yang diblokir dan lakukan Unban dengan cepat.', 'color' => 'red', 'icon' => 'shield-exclamation'],
                ['title' => 'Allowlist IP', 'desc' => 'Tambahkan IP tepercaya agar tidak pernah diblokir oleh sistem.', 'color' => 'green', 'icon' => 'check-badge'],
                ['title' => 'Audit Log', 'desc' => 'Lihat riwayat aktivitas administratif untuk keperluan audit.', 'color' => 'zinc', 'icon' => 'document-text'],
                ['title' => 'Dokumentasi', 'desc' => 'Buka panduan resmi CrowdSec di situs eksternal.', 'color' => 'blue', 'icon' => 'book-open'],
                ['title' => 'Pengaturan Akun', 'desc' => 'Ubah profil, kata sandi, dan preferensi tampilan.', 'color' => 'zinc', 'icon' => 'cog-6-tooth'],
            ];
        } else {
            foreach ($savedFeatures as $key => $feature) {
                if (!isset($feature['icon'])) {
                    $savedFeatures[$key]['icon'] = 'bolt';
                }
            }
            $this->features = $savedFeatures;
        }
    }

    public function autoDetectCrowdSec(CrowdSecAutoDetector $detector)
    {
        $result = $detector->detect();
        
        if ($result['status'] === 'success') {
            if ($result['lapi_url']) Setting::updateOrCreate(['key' => 'crowdsec_lapi_url'], ['value' => $result['lapi_url']]);
            if ($result['bouncer_key']) Setting::updateOrCreate(['key' => 'crowdsec_bouncer_key'], ['value' => $result['bouncer_key']]);
            if ($result['machine_name']) Setting::updateOrCreate(['key' => 'crowdsec_machine_name'], ['value' => $result['machine_name']]);
            if ($result['machine_password']) Setting::updateOrCreate(['key' => 'crowdsec_machine_password'], ['value' => $result['machine_password']]);
            
            session()->flash('message', 'Konfigurasi CrowdSec berhasil dideteksi dan disimpan!');
        } else {
            session()->flash('error', $result['message']);
        }
    }

    public function addFeature()
    {
        $this->features[] = ['title' => 'Fitur Baru', 'desc' => 'Deskripsi fitur...', 'color' => 'zinc', 'icon' => 'star'];
    }

    public function removeFeature($index)
    {
        unset($this->features[$index]);
        $this->features = array_values($this->features);
    }

    public function save()
    {
        Setting::updateOrCreate(['key' => 'app_name'], ['value' => $this->app_name]);
        Setting::updateOrCreate(['key' => 'institution_name'], ['value' => $this->institution_name]);
        Setting::updateOrCreate(['key' => 'logo_url'], ['value' => $this->logo_url]);
        Setting::updateOrCreate(['key' => 'hero_title'], ['value' => $this->hero_title]);
        Setting::updateOrCreate(['key' => 'hero_subtitle'], ['value' => $this->hero_subtitle]);
        Setting::updateOrCreate(['key' => 'footer_text'], ['value' => $this->footer_text]);
        Setting::updateOrCreate(['key' => 'landing_features'], ['value' => json_encode($this->features)]);
        
        session()->flash('message', 'Branding & Features settings saved successfully.');
    }

    public function render()
    {
        return view('livewire.site-settings')->layout('layouts::app');
    }
}
