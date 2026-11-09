<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? config('app.name', 'CrowdSec Portal') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @fluxAppearance
</head>
<body class="min-h-screen bg-zinc-950 text-zinc-100 font-sans antialiased flex flex-col justify-center items-center py-12 overflow-hidden relative">
    
    <!-- Background Grid Pattern -->
    <div class="absolute inset-0 z-0 bg-[linear-gradient(to_right,#ffffff0a_1px,transparent_1px),linear-gradient(to_bottom,#ffffff0a_1px,transparent_1px)] bg-[size:32px_32px] [mask-image:radial-gradient(ellipse_60%_50%_at_50%_0%,#000_70%,transparent_100%)]"></div>
    
    <!-- Efek Cahaya (Glow) Biru di Belakang Form -->
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-blue-600/10 rounded-full blur-[120px] pointer-events-none z-0"></div>

    <!-- Logo Header -->
    <div class="relative z-10 flex items-center gap-2 mb-6">
        <svg class="size-8 text-blue-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
        </svg>
        <span class="font-bold text-2xl tracking-tight text-white drop-shadow-md">CrowdSec Portal</span>
    </div>

    <!-- Card Form (Glassmorphism) -->
    <div class="relative z-10 w-full sm:max-w-md px-8 py-8 bg-zinc-900/80 backdrop-blur-xl shadow-2xl shadow-blue-900/20 overflow-hidden sm:rounded-2xl border border-zinc-800/80">
        <!-- Garis Aksen Warna di Atas Card -->
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-500 via-cyan-400 to-blue-500"></div>
        
        {{ $slot }}
    </div>

    <!-- Footer Text -->
    <div class="relative z-10 mt-8 text-xs text-zinc-600 font-medium tracking-wide uppercase">
        Protected by CrowdSec Security Engine
    </div>

    @livewireScripts
    @fluxScripts
</body>
</html>
