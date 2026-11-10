<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Cek alert CrowdSec setiap menit
Schedule::command('crowdsec:check-alerts')->everyMinute();
