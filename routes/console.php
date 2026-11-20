<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Cek alert CrowdSec setiap menit dengan proteksi overlapping
Schedule::command('crowdsec:check-alerts')
    ->everyMinute()
    ->withoutOverlapping(5);
