<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use App\Models\Setting;

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

        // 2. Override Config CrowdSec dari Database (Setelah DB Siap)
        if (Schema::hasTable('settings')) {
            config([
                'crowdsec.lapi_url' => Setting::get('crowdsec_lapi_url', config('crowdsec.lapi_url')),
                'crowdsec.bouncer_key' => Setting::get('crowdsec_bouncer_key', config('crowdsec.bouncer_key')),
                'crowdsec.machine_name' => Setting::get('crowdsec_machine_name', config('crowdsec.machine_name')),
                'crowdsec.machine_password' => Setting::get('crowdsec_machine_password', config('crowdsec.machine_password')),
            ]);
        }
    }
}
