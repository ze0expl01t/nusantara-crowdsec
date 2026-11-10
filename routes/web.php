<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Livewire\CrowdSecDashboard;
use App\Livewire\AttackMap;
use App\Livewire\DecisionManager;
use App\Livewire\AllowlistManager;
use App\Livewire\AuditLogViewer;
use App\Livewire\ReportManager;
use App\Livewire\SiteSettings;
use App\Livewire\BackupManager;
use App\Livewire\NotificationSettings;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', CrowdSecDashboard::class)->name('dashboard');
    Route::get('attack-map', AttackMap::class)->name('attack-map');
    Route::get('decisions', DecisionManager::class)->name('decisions');
    Route::get('allowlists', AllowlistManager::class)->name('allowlists');
    Route::get('audit-logs', AuditLogViewer::class)->name('audit-logs');
    Route::get('reports', ReportManager::class)->name('reports');
    Route::get('settings/site', SiteSettings::class)->name('settings.site');
    Route::get('settings/notifications', NotificationSettings::class)->name('settings.notifications');
    Route::get('settings/backup', BackupManager::class)->name('settings.backup');
});

Route::middleware(['auth'])->group(function () {
    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';
