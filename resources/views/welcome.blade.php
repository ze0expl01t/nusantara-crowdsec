<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ \App\Models\Setting::get('app_name', 'CrowdSec Portal') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @keyframes cscliFloatSlow {
            0%, 100% { transform: translate3d(-50%, 0, 0) scale(1); }
            50% { transform: translate3d(-50%, -14px, 0) scale(1.04); }
        }
        @keyframes cscliFadeUp {
            from { opacity: 0; transform: translate3d(0, 14px, 0); }
            to { opacity: 1; transform: translate3d(0, 0, 0); }
        }
        @keyframes cscliColorPulseSweep {
            0% {
                background-position: 0% 50%;
                opacity: 0.72;
                transform: scaleX(0.96);
            }
            50% {
                background-position: 100% 50%;
                opacity: 1;
                transform: scaleX(1);
            }
            100% {
                background-position: 0% 50%;
                opacity: 0.72;
                transform: scaleX(0.96);
            }
        }
        .cscli-orb {
            animation: cscliFloatSlow 9s ease-in-out infinite;
        }
        .cscli-fade-up {
            animation: cscliFadeUp 0.65s cubic-bezier(0.22, 1, 0.36, 1) both;
        }
        .cscli-pulse-line {
            height: 2px;
            width: 100%;
            background: linear-gradient(90deg, #06b6d4, #3b82f6, #10b981, #f59e0b, #ec4899, #06b6d4);
            background-size: 260% 100%;
            animation: cscliColorPulseSweep 5.5s ease-in-out infinite;
            box-shadow: 0 0 12px rgba(59, 130, 246, 0.35);
        }
        .cscli-reveal {
            opacity: 0;
            transform: translate3d(0, 14px, 0);
            transition: opacity 550ms cubic-bezier(0.22, 1, 0.36, 1), transform 550ms cubic-bezier(0.22, 1, 0.36, 1);
        }
        .cscli-reveal.is-visible {
            opacity: 1;
            transform: translate3d(0, 0, 0);
        }
        @media (prefers-reduced-motion: reduce) {
            .cscli-orb,
            .cscli-fade-up,
            .cscli-pulse-line {
                animation: none !important;
            }
            .cscli-reveal {
                opacity: 1 !important;
                transform: none !important;
                transition: none !important;
            }
        }
    </style>
</head>

<body class="antialiased bg-zinc-950 text-zinc-100 font-sans selection:bg-blue-500 selection:text-white relative min-h-screen">
@php
    $tamperTime = \Illuminate\Support\Facades\Cache::pull('brand_tamper_detected_at');
@endphp
@if ($tamperTime)
    <div id="cscli-tamper-banner" class="fixed top-0 left-0 w-full z-[100] bg-red-600 text-white py-3 px-4 text-center shadow-2xl">
        <div class="flex items-center justify-center gap-3 font-semibold text-sm">
            <svg class="size-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <span>Security Alert: Branding has been tampered at {{ $tamperTime }}. System has auto-restored.</span>
            <button type="button" onclick="document.getElementById('cscli-tamper-banner')?.remove()" class="ml-4 underline text-white/90 hover:text-white text-xs cursor-pointer">Dismiss</button>
        </div>
    </div>
@endif

    <!-- BACKGROUND EFFECTS -->
    <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden">
        <div class="cscli-orb absolute top-0 left-1/2 w-[800px] h-[500px] bg-blue-600/20 blur-[120px] rounded-full"></div>
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff0a_1px,transparent_1px),linear-gradient(to_bottom,#ffffff0a_1px,transparent_1px)] bg-[size:32px_32px] [mask-image:radial-gradient(ellipse_60%_50%_at_50%_0%,#000_70%,transparent_100%)]"></div>
    </div>

    <!-- NAVBAR -->
    <nav class="sticky top-0 z-50 w-full border-b border-zinc-800/50 bg-zinc-950/80 backdrop-blur-md">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-2.5 shrink-0">
                @if(\App\Models\Setting::get('logo_url'))
                    <img src="{{ \App\Models\Setting::get('logo_url') }}" class="h-8 object-contain" alt="Logo">
                @else
                    <flux:icon.shield-check class="size-6 text-blue-500 transition-transform duration-300 hover:scale-110" />
                @endif
                <span class="font-semibold text-lg text-white whitespace-nowrap">{{ \App\Models\Setting::get('app_name', 'CrowdSec Portal') }}</span>
                <span class="ml-1.5 px-2.5 py-0.5 text-xs font-medium bg-zinc-800 text-zinc-300 rounded-full border border-zinc-700 whitespace-nowrap">v1.0</span>
            </div>

            <div class="flex items-center gap-6 shrink-0">
                <a href="#quickstart" class="hidden sm:inline-block text-sm font-medium text-zinc-400 hover:text-blue-400 transition whitespace-nowrap">Quick Start</a>
                <a href="https://docs.crowdsec.net/" target="_blank" rel="noopener noreferrer" class="text-sm font-medium text-zinc-400 hover:text-blue-400 transition whitespace-nowrap">Docs</a>
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-sm font-medium text-zinc-400 hover:text-blue-400 transition whitespace-nowrap">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium text-zinc-400 hover:text-blue-400 transition whitespace-nowrap">Log in</a>
                    @endauth
                @endif
            </div>
        </div>
    </nav>
    <div class="sticky top-16 z-40 w-full pointer-events-none"><div class="cscli-pulse-line"></div></div>

    <!-- MAIN CONTENT -->
    <main class="relative z-10 max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-16 space-y-14">
        <div class="text-center max-w-3xl mx-auto cscli-fade-up">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-zinc-900/90 border border-zinc-800 text-xs font-medium text-zinc-300 mb-5">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                </span>
                <span>Threat Intelligence &amp; Mitigation Portal</span>
            </div>

            <h1 class="text-4xl sm:text-5xl font-extrabold tracking-tight text-white mb-6 drop-shadow-lg">
                {{ \App\Models\Setting::get('hero_title', 'CrowdSec Portal & Documentation') }}
            </h1>
            <p class="text-lg sm:text-xl text-zinc-400 leading-relaxed mb-6">
                {{ \App\Models\Setting::get('hero_subtitle', 'Real-time threat intelligence, automated mitigation, and security monitoring portal.') }}
            </p>
            <div class="max-w-md mx-auto mb-8 rounded-full overflow-hidden">
                <div class="cscli-pulse-line"></div>
            </div>
            <div class="flex flex-wrap items-center justify-center gap-4">
                <a href="{{ auth()->check() ? url('/dashboard') : route('login') }}" class="inline-flex items-center justify-center px-6 py-3 text-base font-semibold rounded-lg text-white bg-gradient-to-r from-blue-600 to-cyan-500 hover:from-blue-500 hover:to-cyan-400 transition shadow-lg shadow-blue-900/50">
                    Open Dashboard
                </a>
                <a href="#quickstart" class="inline-flex items-center justify-center px-6 py-3 text-base font-semibold rounded-lg text-zinc-300 bg-zinc-900/80 backdrop-blur-sm border border-zinc-800 hover:bg-zinc-800 transition">
                    Quick Start
                </a>
            </div>
        </div>

        <!-- FEATURE GRID -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @php
                $features = json_decode(\App\Models\Setting::get('landing_features', '[]'), true);
                if(empty($features)) {
                    $features = [
                        ['title' => 'Real-Time Monitoring', 'desc' => 'Live visibility into incoming alerts, active decisions, and threat telemetry.', 'color' => 'blue', 'icon' => 'chart-bar'],
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

            @foreach($features as $index => $feature)
                @php
                    $color = $feature['color'] ?? 'blue';
                    $style = $colorClasses[$color] ?? $colorClasses['blue'];
                    $icon = $feature['icon'] ?? 'bolt';
                @endphp
                <div
                    data-reveal
                    style="transition-delay: {{ min($index * 60, 300) }}ms;"
                    class="cscli-reveal group block p-6 bg-zinc-900/80 backdrop-blur-sm border border-zinc-800 rounded-xl {{ $style['glow'] }} hover:bg-zinc-800/80 transition-all duration-300 hover:-translate-y-1 relative overflow-hidden"
                >
                    <div class="flex items-center gap-3 mb-3">
                        <div class="p-2 {{ $style['icon'] }} rounded-lg group-hover:scale-110 transition-transform">
                            <flux:icon :name="$icon" class="size-5" />
                        </div>
                        <h3 class="text-lg font-bold text-zinc-100 transition-colors">{{ $feature['title'] }}</h3>
                    </div>
                    <p class="text-sm text-zinc-400">{{ $feature['desc'] }}</p>
                </div>
            @endforeach
        </div>

        <!-- INTERACTIVE QUICK START -->
        <section
            id="quickstart"
            data-reveal
            class="cscli-reveal rounded-2xl border border-zinc-800 bg-zinc-900/75 backdrop-blur-md overflow-hidden"
        >
            <div class="cscli-pulse-line"></div>
            <div class="p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-5 border-b border-zinc-800">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-blue-400">Quick Start</p>
                        <h2 class="text-xl font-bold text-white mt-0.5">Interactive Workflow Guide</h2>
                    </div>
                    <div class="inline-flex flex-wrap gap-1.5 p-1 rounded-xl bg-zinc-950 border border-zinc-800" role="tablist">
                        <button
                            type="button"
                            data-wf-btn
                            data-wf-title="Manage Active Decisions"
                            data-wf-desc="Inspect blocked IPs, filter by scenario or country, and unban false positives."
                            data-wf-cmd="cscli decisions list --limit 20"
                            class="px-3 py-1.5 rounded-lg text-xs font-medium transition cursor-pointer bg-blue-600 text-white"
                        >Decisions</button>
                        <button
                            type="button"
                            data-wf-btn
                            data-wf-title="Security Reports &amp; Exports"
                            data-wf-desc="Filter alert telemetry and export PDF or Excel reports directly from the portal."
                            data-wf-cmd="php artisan cscli:check-alerts"
                            class="px-3 py-1.5 rounded-lg text-xs font-medium transition cursor-pointer text-zinc-400 hover:text-zinc-200"
                        >Reports</button>
                        <button
                            type="button"
                            data-wf-btn
                            data-wf-title="System Backup &amp; Recovery"
                            data-wf-desc="Run database or full backups and download archives safely via streaming download."
                            data-wf-cmd="php artisan backup:run --disable-notifications"
                            class="px-3 py-1.5 rounded-lg text-xs font-medium transition cursor-pointer text-zinc-400 hover:text-zinc-200"
                        >Backup</button>
                    </div>
                </div>

                <div class="pt-5 grid grid-cols-1 lg:grid-cols-12 gap-5 items-center">
                    <div class="lg:col-span-7 space-y-1.5">
                        <h3 id="wf-title" class="text-base font-bold text-white">Manage Active Decisions</h3>
                        <p id="wf-desc" class="text-sm text-zinc-400">Inspect blocked IPs, filter by scenario or country, and unban false positives.</p>
                    </div>
                    <div class="lg:col-span-5">
                        <div class="rounded-xl bg-zinc-950 border border-zinc-800 p-3.5 space-y-2">
                            <div class="flex items-center justify-between text-xs text-zinc-500">
                                <span class="font-mono">Command</span>
                                <button
                                    id="wf-copy-btn"
                                    type="button"
                                    class="px-2 py-0.5 rounded bg-zinc-900 hover:bg-zinc-800 text-zinc-300 border border-zinc-700 transition cursor-pointer"
                                >Copy</button>
                            </div>
                            <pre class="overflow-x-auto text-xs font-mono text-cyan-300"><code id="wf-cmd">cscli decisions list --limit 20</code></pre>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- FOOTER -->
    <footer class="relative z-10 border-t border-zinc-800/80 py-8 mt-12 bg-zinc-950/50 backdrop-blur-sm">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-4 text-sm text-zinc-500">
            <p>&copy; {{ date('Y') }} {{ \App\Models\Setting::get('institution_name', 'SMK Muhammadiyah 1 Blora') }}. {{ \App\Models\Setting::get('footer_text', 'All rights reserved.') }}</p>
            <p class="text-xs text-zinc-600 dark:text-zinc-500">Developed by <span class="font-semibold text-blue-500">arkanintermedia</span></p>
        </div>
    </footer>

    <script>
        (() => {
            const els = document.querySelectorAll('[data-reveal]');
            if (els.length) {
                const reduced = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
                if (reduced || !('IntersectionObserver' in window)) {
                    els.forEach((el) => el.classList.add('is-visible'));
                } else {
                    const io = new IntersectionObserver((entries) => {
                        entries.forEach((entry) => {
                            if (entry.isIntersecting) {
                                entry.target.classList.add('is-visible');
                                io.unobserve(entry.target);
                            }
                        });
                    }, { threshold: 0.12 });
                    els.forEach((el) => io.observe(el));
                }
            }

            const buttons = Array.from(document.querySelectorAll('[data-wf-btn]'));
            const titleEl = document.getElementById('wf-title');
            const descEl = document.getElementById('wf-desc');
            const cmdEl = document.getElementById('wf-cmd');
            const copyBtn = document.getElementById('wf-copy-btn');

            function setActive(btn) {
                buttons.forEach((b) => {
                    b.classList.remove('bg-blue-600', 'text-white');
                    b.classList.add('text-zinc-400', 'hover:text-zinc-200');
                });
                btn.classList.remove('text-zinc-400', 'hover:text-zinc-200');
                btn.classList.add('bg-blue-600', 'text-white');

                if (titleEl && btn.dataset.wfTitle) titleEl.textContent = btn.dataset.wfTitle;
                if (descEl && btn.dataset.wfDesc) descEl.textContent = btn.dataset.wfDesc;
                if (cmdEl && btn.dataset.wfCmd) cmdEl.textContent = btn.dataset.wfCmd;
            }

            buttons.forEach((btn) => {
                btn.addEventListener('click', () => setActive(btn));
            });

            if (copyBtn && cmdEl) {
                copyBtn.addEventListener('click', () => {
                    const text = cmdEl.textContent || '';
                    if (navigator.clipboard && navigator.clipboard.writeText) {
                        navigator.clipboard.writeText(text).then(() => {
                            copyBtn.textContent = 'Copied';
                            setTimeout(() => { copyBtn.textContent = 'Copy'; }, 1600);
                        }).catch(() => {});
                    }
                });
            }
        })();
    </script>
</body>
</html>
