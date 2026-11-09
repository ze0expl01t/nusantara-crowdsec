<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ \App\Models\Setting::get('app_name', 'CrowdSec Portal') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

    

<body class="antialiased bg-zinc-950 text-zinc-100 font-sans selection:bg-blue-500 selection:text-white relative min-h-screen">
@php
    $tamperTime = \Illuminate\Support\Facades\Cache::pull('brand_tamper_detected_at');
@endphp
@if ($tamperTime)
    <!-- TAMPER DETECTION BANNER -->
    <div x-data="{ show: true }" x-show="show" class="fixed top-0 left-0 w-full z-[100] bg-red-600 text-white py-3 px-4 text-center shadow-2xl">
        <div class="flex items-center justify-center gap-3 font-semibold text-sm">
            <svg class="size-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <span>Security Alert: Branding has been tampered at {{ $tamperTime }}. System has auto-restored.</span>
            <button @click="show = false" class="ml-4 underline text-white/90 hover:text-white text-xs">Dismiss</button>
        </div>
    </div>
@endif


    
    <!-- BACKGROUND EFFECTS -->
    <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden">
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[500px] bg-blue-600/20 blur-[120px] rounded-full"></div>
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff0a_1px,transparent_1px),linear-gradient(to_bottom,#ffffff0a_1px,transparent_1px)] bg-[size:32px_32px] [mask-image:radial-gradient(ellipse_60%_50%_at_50%_0%,#000_70%,transparent_100%)]"></div>
    </div>

    <!-- NAVBAR DINAMIS -->
    <nav class="sticky top-0 z-50 w-full border-b border-zinc-800/50 bg-zinc-950/80 backdrop-blur-md">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-2">
                @if(\App\Models\Setting::get('logo_url'))
                    <img src="{{ \App\Models\Setting::get('logo_url') }}" class="h-8 object-contain" alt="Logo">
                @else
                    <flux:icon.shield-check class="size-6 text-blue-500" />
                @endif
                <span class="font-semibold text-lg text-white">{{ \App\Models\Setting::get('app_name', 'CrowdSec Portal') }}</span>
                <span class="ml-2 px-2 py-0.5 text-xs font-medium bg-zinc-800 text-zinc-300 rounded-full border border-zinc-700">v1.0</span>
            </div>
            
            <div class="flex items-center gap-6">
                <a href="https://docs.crowdsec.net/" target="_blank" class="text-sm font-medium text-zinc-400 hover:text-blue-400 transition">Docs</a>
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-sm font-medium text-zinc-400 hover:text-blue-400 transition">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium text-zinc-400 hover:text-blue-400 transition">Log in</a>
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    <!-- HERO SECTION DINAMIS -->
    <main class="relative z-10 max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 pb-16">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <h1 class="text-4xl sm:text-5xl font-extrabold tracking-tight text-white mb-6 drop-shadow-lg">
                {{ \App\Models\Setting::get('hero_title', 'Dokumentasi & Portal CrowdSec') }}
            </h1>
            <p class="text-lg sm:text-xl text-zinc-400 leading-relaxed mb-8">
                {{ \App\Models\Setting::get('hero_subtitle', 'Panduan lengkap...') }}
            </p>
            <div class="flex items-center justify-center gap-4">
                <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-6 py-3 text-base font-semibold rounded-lg text-white bg-gradient-to-r from-blue-600 to-cyan-500 hover:from-blue-500 hover:to-cyan-400 transition shadow-lg shadow-blue-900/50">Buka Dashboard</a>
                <a href="#quickstart" class="inline-flex items-center justify-center px-6 py-3 text-base font-semibold rounded-lg text-zinc-300 bg-zinc-900/80 backdrop-blur-sm border border-zinc-800 hover:bg-zinc-800 transition">Mulai Cepat</a>
            </div>
        </div>

        <!-- GRID FITUR DINAMIS -->
        <div id="quickstart" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @php
                $features = json_decode(\App\Models\Setting::get('landing_features', '[]'), true);
                if(empty($features)) {
                    $features = [
                        ['title' => 'Monitoring Real-time', 'desc' => '...', 'color' => 'blue', 'icon' => 'chart-bar'],
                    ];
                }
                
                $colorClasses = [
                    'blue' => ['icon' => 'bg-blue-900/30 text-blue-400', 'glow' => 'hover:border-blue-500/50 hover:shadow-[0_0_20px_rgba(59,130,246,0.3)]'],
                    'red' => ['icon' => 'bg-red-900/30 text-red-400', 'glow' => 'hover:border-red-500/50 hover:shadow-[0_0_20px_rgba(239,68,68,0.3)]'],
                    'green' => ['icon' => 'bg-green-900/30 text-green-400', 'glow' => 'hover:border-green-500/50 hover:shadow-[0_0_20px_rgba(34,197,94,0.3)]'],
                    'orange' => ['icon' => 'bg-orange-900/30 text-orange-400', 'glow' => 'hover:border-orange-500/50 hover:shadow-[0_0_20px_rgba(249,115,22,0.3)]'],
                    'zinc' => ['icon' => 'bg-zinc-800 text-zinc-400', 'glow' => 'hover:border-zinc-500/50 hover:shadow-[0_0_20px_rgba(161,161,170,0.3)]'],
                ];
            @endphp

            @foreach($features as $feature)
                @php
                    $color = $feature['color'] ?? 'blue';
                    $style = $colorClasses[$color] ?? $colorClasses['blue'];
                    $icon = $feature['icon'] ?? 'bolt';
                @endphp
                <div class="group block p-6 bg-zinc-900/80 backdrop-blur-sm border border-zinc-800 rounded-xl {{ $style['glow'] }} hover:bg-zinc-800/80 transition-all duration-300 hover:-translate-y-1 relative">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="p-2 {{ $style['icon'] }} rounded-lg group-hover:scale-110 transition-transform">
                            <!-- RENDER IKON DINAMIS MENGGUNAKAN FLUX UI -->
                            <flux:icon :name="$icon" class="size-5" />
                        </div>
                        <h3 class="text-lg font-bold text-zinc-100 transition-colors">{{ $feature['title'] }}</h3>
                    </div>
                    <p class="text-sm text-zinc-400">{{ $feature['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </main>

    <!-- FOOTER -->
    <footer class="relative z-10 border-t border-zinc-800/80 py-8 mt-12 bg-zinc-950/50 backdrop-blur-sm">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-4 text-sm text-zinc-500">
            <p>&copy; {{ date('Y') }} {{ \App\Models\Setting::get('institution_name', 'SMK Muhammadiyah 1 Blora') }}. {{ \App\Models\Setting::get('footer_text', 'All rights reserved.') }}</p>
            <p class="text-xs text-zinc-600 dark:text-zinc-500">Developed by <span class="font-semibold text-blue-500">arkanintermedia</span></p>
        </div>
    </footer>
</body>
</html>
