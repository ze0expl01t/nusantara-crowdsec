<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // 1. Pagination Kustom
        Paginator::defaultView('livewire.pagination.tailwind');
        Paginator::defaultSimpleView('livewire.pagination.tailwind');

        // 2. Override Config CrowdSec dari Database secara aman (dengan cache singkat & fallback)
        try {
            if (! Schema::hasTable('settings')) {
                return;
            }

            $overrides = Cache::remember('crowdsec:db_config_overrides', 60, function () {
                return [
                    'crowdsec.lapi_url' => Setting::get('crowdsec_lapi_url', config('crowdsec.lapi_url')),
                    'crowdsec.bouncer_key' => Setting::get('crowdsec_bouncer_key', config('crowdsec.bouncer_key')),
                    'crowdsec.machine_name' => Setting::get('crowdsec_machine_name', config('crowdsec.machine_name')),
                    'crowdsec.machine_password' => Setting::get('crowdsec_machine_password', config('crowdsec.machine_password')),
                ];
            });

            if (is_array($overrides)) {
                config($overrides);
            }
        } catch (\Throwable $e) {
            Log::warning('AppServiceProvider DB config override skipped: ' . $e->getMessage());
        }
    }
}
