<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ \App\Models\Setting::get('app_name', 'CrowdSec Portal') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @fluxAppearance
</head>
<body class="min-h-screen bg-zinc-50 dark:bg-zinc-900">
    <flux:sidebar sticky stashable class="bg-zinc-50 dark:bg-zinc-900 border-r border-zinc-200 dark:border-zinc-700">
        <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />
        <a href="/dashboard" class="flex items-center gap-2 px-4 py-2" wire:navigate>
            @if(\App\Models\Setting::get('logo_url'))
                <img src="{{ \App\Models\Setting::get('logo_url') }}" class="h-8 w-8 object-contain" alt="Logo">
            @else
                <flux:icon.shield-check class="size-8 text-blue-600" />
            @endif
            <span class="font-bold text-lg">{{ \App\Models\Setting::get('app_name', 'CrowdSec Portal') }}</span>
        </a>

        <flux:navlist variant="outline">
            <flux:navlist.item icon="home" href="/dashboard" :current="request()->routeIs('dashboard')" wire:navigate>Dashboard</flux:navlist.item>
            <flux:navlist.item icon="globe-alt" href="/attack-map" :current="request()->routeIs('attack-map')" wire:navigate>
                Attack Map
            </flux:navlist.item>
            <flux:navlist.item icon="shield-exclamation" href="/decisions" :current="request()->routeIs('decisions')" wire:navigate>Decisions (Blokir)</flux:navlist.item>
            <flux:navlist.item icon="check-badge" href="/allowlists" :current="request()->routeIs('allowlists')" wire:navigate>Allowlist</flux:navlist.item>
            <flux:navlist.item icon="document-text" href="/audit-logs" :current="request()->routeIs('audit-logs')" wire:navigate>Audit Logs</flux:navlist.item>
            <flux:navlist.item icon="chart-bar" href="/reports" :current="request()->routeIs('reports')" wire:navigate>Reports</flux:navlist.item>
        </flux:navlist>

        <flux:spacer />

        <flux:navlist variant="outline">
            <flux:navlist.item icon="cog-6-tooth" href="/settings/profile" :current="request()->routeIs('settings.profile')" wire:navigate>Profile Settings</flux:navlist.item>
            
            <flux:navlist.item icon="bell" href="/settings/notifications" :current="request()->routeIs('settings.notifications')" wire:navigate>
                Notifications
            </flux:navlist.item>
            <flux:navlist.item icon="paint-brush" href="/settings/site" :current="request()->routeIs('settings.site')" wire:navigate>Web Appearance</flux:navlist.item>
            <flux:navlist.item icon="circle-stack" href="/settings/backup" :current="request()->routeIs('settings.backup')" wire:navigate>Data Backup</flux:navlist.item>
        </flux:navlist>

        <flux:dropdown position="top" align="start">
            <flux:profile class="cursor-pointer" :name="auth()->user()->name ?? 'Administrator'" :initials="substr(auth()->user()->name ?? 'A', 0, 2)" />
            <flux:menu>
                <div class="px-4 py-2 text-xs text-zinc-500 dark:text-zinc-300">{{ auth()->user()->email ?? '' }}</div>
                <flux:menu.separator />
                <flux:menu.item icon="arrow-right-start-on-rectangle" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</flux:menu.item>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
            </flux:menu>
        </flux:dropdown>

        <!-- Developer Credit -->
        <div class="px-4 pt-4 pb-2 text-[10px] text-center text-zinc-500 dark:text-zinc-500 border-t border-zinc-200 dark:border-zinc-800 mt-2">
            Developed by <span class="font-semibold text-blue-500">arkanintermedia</span>
        </div>
    </flux:sidebar>

    <flux:header class="lg:hidden">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />
        <flux:spacer />
    </flux:header>

    <flux:main>
        {{ $slot }}
    </flux:main>

    @livewireScripts
    @fluxScripts
</body>
</html>
