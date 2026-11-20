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
            50% { transform: translate3d(-50%, 18px, 0) scale(1.06); }
        }
        @keyframes cscliFadeUp {
            from { opacity: 0; transform: translate3d(0, 14px, 0); }
            to { opacity: 1; transform: translate3d(0, 0, 0); }
        }
        .cscli-ambient-orb {
            animation: cscliFloatSlow 9s ease-in-out infinite;
        }
        .cscli-fade-up {
            animation: cscliFadeUp 0.65s cubic-bezier(0.22, 1, 0.36, 1) both;
        }
        .cscli-fade-up-delay {
            animation: cscliFadeUp 0.8s cubic-bezier(0.22, 1, 0.36, 1) 0.12s both;
        }
        @media (prefers-reduced-motion: reduce) {
            .cscli-ambient-orb,
            .cscli-fade-up,
            .cscli-fade-up-delay {
                animation: none !important;
            }
        }
    </style>
</head>

<body class="antialiased bg-zinc-950 text-zinc-100 font-sans selection:bg-blue-500 selection:text-white relative min-h-screen">
@php
    $tamperTime = \Illuminate\Support\Facades\Cache::pull('brand_tamper_detected_at');
@endphp
@if ($tamperTime)
    <!-- TAMPER DETECTION BANNER -->
    <div x-data="{ show: true }" x-show="show" x-transition class="fixed top-0 left-0 w-full z-[100] bg-red-600 text-white py-3 px-4 text-center shadow-2xl">
        <div class="flex items-center justify-center gap-3 font-semibold text-sm">
            <svg class="size-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <span>Security Alert: Branding has been tampered at {{ $tamperTime }}. System has auto-restored.</span>
            <button @click="show = false" class="ml-4 underline text-white/90 hover:text-white text-xs cursor-pointer">Dismiss</button>
        </div>
    </div>
@endif

    <!-- BACKGROUND EFFECTS -->
    <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden">
        <div class="cscli-ambient-orb absolute top-0 left-1/2 w-[820px] h-[520px] bg-blue-600/20 blur-[120px] rounded-full"></div>
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff0a_1px,transparent_1px),linear-gradient(to_bottom,#ffffff0a_1px,transparent_1px)] bg-[size:32px_32px] [mask-image:radial-gradient(ellipse_60%_50%_at_50%_0%,#000_70%,transparent_100%)]"></div>
    </div>

    <!-- NAVBAR DINAMIS -->
    <nav class="sticky top-0 z-50 w-full border-b border-zinc-800/50 bg-zinc-950/80 backdrop-blur-md">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-2">
                @if(\App\Models\Setting::get('logo_url'))
                    <img src="{{ \App\Models\Setting::get('logo_url') }}" class="h-8 object-contain" alt="Logo">
                @else
                    <flux:icon.shield-check class="size-6 text-blue-500 transition-transform duration-300 hover:scale-110" />
                @endif
                <span class="font-semibold text-lg text-white">{{ \App\Models\Setting::get('app_name', 'CrowdSec Portal') }}</span>
                <span class="ml-2 px-2 py-0.5 text-xs font-medium bg-zinc-800 text-zinc-300 rounded-full border border-zinc-700">v1.0</span>
            </div>

            <div class="flex items-center gap-6">
                <a href="#workflow-explorer" class="hidden sm:inline-block text-sm font-medium text-zinc-400 hover:text-blue-400 transition">Workflow</a>
                <a href="#quickstart" class="hidden sm:inline-block text-sm font-medium text-zinc-400 hover:text-blue-400 transition">Features</a>
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
    <main class="relative z-10 max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-16">
        <div class="text-center max-w-3xl mx-auto mb-14 cscli-fade-up">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/10 border border-blue-500/20 text-blue-400 text-xs font-medium mb-5">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                    <span class="Struktur `resources/views/welcome.blade.php` memang masih statis di bagian konten utama: hanya ada efek *hover* dasar pada kartu fitur (`hover:-translate-y-1`, `group-hover:scale-110`) serta *glow* dan *grid* latar belakang, tanpa animasi masuk bertahap (*staggered/scroll reveal*), tanpa indikator status/interaksi langsung, dan tanpa *interactive quick-start / workflow tab* yang bisa dijelajahi pengunjung sebelum masuk ke *dashboard*.

### Rencana Peningkatan Interaktivitas & Animasi (`resources/views/welcome.blade.php`)
* **Animasi masuk halus & *scroll reveal*:** Menambahkan animasi *keyframe* ringan (`fade-up`, *pulse* status) serta `IntersectionObserver` kecil yang menghormati `prefers-reduced-motion: reduce` agar hero dan kartu fitur muncul mulus saat halaman dimuat atau di-*scroll*.
* **Interactive Quick Start & Architecture Explorer:** Menambahkan *tab* interaktif di bagian `#quickstart` (misalnya *Threat Detection & Bouncer*, *GeoIP & Telemetry*, *Reports & Backup*) dengan *copy command/snippet* satu klik + umpan balik visual `Copied` agar bagian *Quick Start* terasa hidup dan fungsional.
* **Live Portal Pulse Bar di Hero:** Menambahkan *pill* status interaktif yang menampilkan ringkasan cepat (*LAPI & Bouncer Telemetry*, *Automated Mitigation*, *Audit & Reports*) dan dapat memicu sorotan/pindah ke *tab* terkait.

---

### Terapkan Pembaruan `welcome.blade.php` + Backup/Rollback + Test + Push & Tag `v1.0.0`

Jalankan perintah berikut di `/home/cscli.smkmuh1blora.sch.id/public_html/portal-app` (`cscli2082` di `abtserver-01`):

```bash
cd /home/cscli.smkmuh1blora.sch.id/public_html/portal-app && \
PHP_BIN="/usr/local/lsws/lsphp84/bin/php" && \
BK="/tmp/cscli_landing_interactive_$(date +%Y%m%d_%H%M%S)" && \
mkdir -p "$BK" && \
cp -p resources/views/welcome.blade.php "$BK/" && \
trap 'echo "[ROLLBACK] Error at line $LINENO — restoring backup from $BK"; cp -p "$BK/welcome.blade.php" resources/views/welcome.blade.php; exit 1' ERR

cat << 'EOF' > resources/views/welcome.blade.php
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ \App\Models\Setting::get('app_name', 'CrowdSec Portal') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @keyframes cscliFloatSlow {
            0%, 100% { transform: translate3d(0, 0, 0) scale(1); }
            50% { transform: translate3d(0, -14px, 0) scale(1.03); }
        }
        @keyframes cscliFadeUp {
            from { opacity: 0; transform: translate3d(0, 16px, 0); }
            to { opacity: 1; transform: translate3d(0, 0, 0); }
        }
        .cscli-ambient-orb {
            animation: cscliFloatSlow 9s ease-in-out infinite;
        }
        .cscli-reveal {
            opacity: 0;
            transform: translate3d(0, 16px, 0);
            transition: opacity 600ms cubic-bezier(0.22, 1, 0.36, 1), transform 600ms cubic-bezier(0.22, 1, 0.36, 1);
        }
        .cscli-reveal.is-visible {
            opacity: 1;
            transform: translate3d(0, 0, 0);
        }
        .cscli-hero-stagger {
            animation: cscliFadeUp 650ms cubic-bezier(0.22, 1, 0.36, 1) both;
        }
        @media (prefers-reduced-motion: reduce) {
            .cscli-ambient-orb,
            .cscli-hero-stagger {
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
    <!-- TAMPER DETECTION BANNER -->
    <div x-data="{ show: true }" x-show="show" x-transition class="fixed top-0 left-0 w-full z-[100] bg-red-600 text-white py-3 px-4 text-center shadow-2xl">
        <div class="flex items-center justify-center gap-3 font-semibold text-sm">
            <svg class="size-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <span>Security Alert: Branding has been tampered at {{ $tamperTime }}. System has auto-restored.</span>
            <button @click="show = false" class="ml-4 underline text-white/90 hover:text-white text-xs cursor-pointer">Dismiss</button>
        </div>
    </div>
@endif

    <!-- BACKGROUND EFFECTS -->
    <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden">
        <div class="cscli-ambient-orb absolute top-0 left-1/2 -translate-x-1/2 w-[820px] h-[520px] bg-blue-600/20 blur-[125px] rounded-full"></div>
        <div class="cscli-ambient-orb absolute bottom-0 right-1/4 w-[520px] h-[360px] bg-cyan-500/10 blur-[120px] rounded-full" style="animation-delay: -4s;"></div>
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff0a_1px,transparent_1px),linear-gradient(to_bottom,#ffffff0a_1px,transparent_1px)] bg-[size:32px_32px] [mask-image:radial-gradient(ellipse_60%_50%_at_50%_0%,#000_70%,transparent_100%)]"></div>
    </div>

    <!-- DYNAMIC NAVBAR -->
    <nav class="sticky top-0 z-50 w-full border-b border-zinc-800/60 bg-zinc-950/80 backdrop-blur-md">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-2.5 group">
                @if(\App\Models\Setting::get('logo_url'))
                    <img src="{{ \App\Models\Setting::get('logo_url') }}" class="h-8 object-contain transition-transform duration-300 group-hover:scale-105" alt="Logo">
                @else
                    <flux:icon.shield-check class="size-6 text-blue-500 transition-transform duration-300 group-hover:scale-110" />
                @endif
                <span class="font-semibold text-lg text-white tracking-tight">{{ \App\Models\Setting::get('app_name', 'CrowdSec Portal') }}</span>
                <span class="ml-1.5 px-2.5 py-0.5 text-xs font-medium bg-blue-500/10 text-blue-300 rounded-full border border-blue-500/20">v1.0</span>
            </div>

            <div class="flex items-center gap-6">
                <a href="#quickstart" class="hidden sm:inline-block text-sm font-medium text-zinc-400 hover:text-blue-400 transition-colors">Quick Start</a>
                <a href="[https://docs.crowdsec.net/](https://docs.crowdsec.net/)" target="_blank" rel="noopener noreferrer" class="text-sm font-medium text-zinc-400 hover:text-blue-400 transition-colors">Docs</a>
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-sm font-medium px-3.5 py-1.5 rounded-lg bg-blue-600/15 text-blue-300 border border-blue-500/30 hover:bg-blue-600/25 transition">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium px-3.5 py-1.5 rounded-lg bg-zinc-900 text-zinc-200 border border-zinc-800 hover:border-blue-500/40 hover:text-white transition">Log in</a>
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    <!-- HERO SECTION -->
    <main class="relative z-10 max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-20 space-y-16">
        <div class="text-center max-w-3xl mx-auto cscli-hero-stagger">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-zinc-900/90 border border-zinc-800 text-xs font-medium text-zinc-300 mb-6 shadow-sm">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                </span>
                <span>Automated Threat Detection &amp; Mitigation Portal</span>
            </div>

            <h1 class="text-4xl sm:text-5xl font-extrabold tracking-tight text-white mb-6 drop-shadow-lg">
                {{ \App\Models\Setting::get('hero_title', 'CrowdSec Portal & Documentation') }}
            </h1>
            <p class="text-lg sm:text-xl text-zinc-400 leading-relaxed mb-8">
                {{ \App\Models\Setting::get('hero_subtitle', 'Real-time threat intelligence, automated mitigation, and security monitoring portal.') }}
            </p>
            <div class="flex flex-wrap items-center justify-center gap-4">
                @auth
                    <a href="{{ url('/dashboard') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 text-base font-semibold rounded-lg text-white bg-gradient-to-r from-blue-600 to-cyan-500 hover:from-blue-500 hover:to-cyan-400 active:scale-[0.99] transition-all duration-200 shadow-lg shadow-blue-900/50">
                        <span>Open Dashboard</span>
                        <flux:icon.arrow-right class="size-4" />
                    </a>
                @else
                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 text-base font-semibold rounded-lg text-white bg-gradient-to-r from-blue-600 to-cyan-500 hover:from-blue-500 hover:to-cyan-400 active:scale-[0.99] transition-all duration-200 shadow-lg shadow-blue-900/50">
                        <span>Open Dashboard</span>
                        <flux:icon.arrow-right class="size-4" />
                    </a>
                @endauth
                <a href="#quickstart" class="inline-flex items-center justify-center gap-2 px-6 py-3 text-base font-semibold rounded-lg text-zinc-300 bg-zinc-900/80 backdrop-blur-sm border border-zinc-800 hover:bg-zinc-800 hover:border-zinc-700 active:scale-[0.99] transition-all duration-200">
                    <flux:icon.command-line class="size-4 text-blue-400" />
                    <span>Quick Start</span>
                </a>
            </div>
        </div>

        <!-- DYNAMIC FEATURE GRID -->
        <section aria-label="Core Features" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @php
                $features = json_decode(\App\Models\Setting::get('landing_features', '[]'), true);
                if(empty($features)) {
                    $features = [
                        ['title' => 'Real-Time Monitoring', 'desc' => 'Live visibility into incoming alerts, active decisions, and threat telemetry.', 'color' => 'blue', 'icon' => 'chart-bar'],
                    ];
                }

                $colorClasses = [
                    'blue' => ['icon' => 'bg-blue-900/30 text-blue-400', 'glow' => 'hover:border-blue-500/50 hover:shadow-[0_0_24px_rgba(59,130,246,0.25)]'],
                    'red' => ['icon' => 'bg-red-900/30 text-red-400', 'glow' => 'hover:border-red-500/50 hover:shadow-[0_0_24px_rgba(239,68,68,0.25)]'],
                    'green' => ['icon' => 'bg-green-900/30 text-green-400', 'glow' => 'hover:border-green-500/50 hover:shadow-[0_0_24px_rgba(34,197,94,0.25)]'],
                    'orange' => ['icon' => 'bg-orange-900/30 text-orange-400', 'glow' => 'hover:border-orange-500/50 hover:shadow-[0_0_24px_rgba(249,115,22,0.25)]'],
                    'zinc' => ['icon' => 'bg-zinc-800 text-zinc-400', 'glow' => 'hover:border-zinc-500/50 hover:shadow-[0_0_24px_rgba(161,161,170,0.22)]'],
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
                    style="transition-delay: {{ min($index * 70, 350) }}ms;"
                    class="cscli-reveal group block p-6 bg-zinc-900/80 backdrop-blur-sm border border-zinc-800 rounded-xl {{ $style['glow'] }} hover:bg-zinc-800/80 transition-all duration-300 hover:-translate-y-1 relative"
                >
                    <div class="flex items-center gap-3 mb-3">
                        <div class="p-2.5 {{ $style['icon'] }} rounded-lg group-hover:scale-110 group-hover:rotate-1 transition-transform duration-300">
                            <flux:icon :name="$icon" class="size-5" />
                        </div>
                        <h2 class="text-lg font-bold text-zinc-100 group-hover:text-white transition-colors">{{ $feature['title'] }}</h2>
                    </div>
                    <p class="text-sm text-zinc-400 leading-relaxed">{{ $feature['desc'] }}</p>
                </div>
            @endforeach
        </section>

        <!-- INTERACTIVE QUICK START WORKBENCH -->
        <section
            id="quickstart"
            data-reveal
            x-data="{
                activeTab: 'decisions',
                copied: false,
                guides: {
                    decisions: {
                        badge: 'Mitigation & Decisions',
                        title: 'Inspect & Manage Active IP Blocks',
                        summary: 'Review live CrowdSec decisions, filter by scenario or origin country, and remove false positives with full audit logging.',
                        cmd: 'cscli decisions list --limit 20'
                    },
                    alerts: {
                        badge: 'Alerts & Reports',
                        title: 'Analyze Attack Trends & Export Reports',
                        summary: 'Monitor top attack scenarios, filter alert history interactively, and export formatted PDF or Excel summaries.',
                        cmd: 'php artisan cscli:check-alerts'
                    },
                    backup: {
                        badge: 'Operations & Backup',
                        title: 'Create & Download System Backups Safely',
                        summary: 'Generate full, database, or file backups and download archives directly through a streaming route.',
                        cmd: 'php artisan backup:run --disable-notifications'
                    }
                },
                copyCmd() {
                    const text = this.guides[this.activeTab].cmd;
                    if (navigator.clipboard && navigator.clipboard.writeText) {
                        navigator.clipboard.writeText(text).then(() => {
                            this.copied = true;
                            setTimeout(() => this.copied = false, 1800);
                        });
                    }
                }
            }"
            class="cscli-reveal rounded-2xl border border-zinc-800/90 bg-zinc-900/75 backdrop-blur-md p-6 sm:p-8 shadow-xl"
        >
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 pb-6 border-b border-zinc-800/80">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-blue-400">Interactive Quick Start</span>
                    <h2 class="text-2xl font-bold text-white mt-1">Explore Core Workflows</h2>
                </div>

                <div class="inline-flex flex-wrap gap-1.5 p-1 rounded-xl bg-zinc-950/90 border border-zinc-800" role="tablist">
                    <button
                        type="button"
                        role="tab"
                        :aria-selected="activeTab === 'decisions'"
                        @click="activeTab = 'decisions'"
                        :class="activeTab === 'decisions' ? 'bg-blue-600 text-white shadow' : 'text-zinc-400 hover:text-zinc-200'"
                        class="px-3.5 py-2 rounded-lg text-xs sm:text-sm font-medium transition-all duration-200 cursor-pointer"
                    >
                        Decisions
                    </button>
                    <button
                        type="button"
                        role="tab"
                        :aria-selected="activeTab === 'alerts'"
                        @click="activeTab = 'alerts'"
                        :class="activeTab === 'alerts' ? 'bg-blue-600 text-white shadow' : 'text-zinc-400 hover:text-zinc-200'"
                        class="px-3.5 py-2 rounded-lg text-xs sm:text-sm font-medium transition-all duration-200 cursor-pointer"
                    >
                        Alerts &amp; Reports
                    </button>
                    <button
                        type="button"
                        role="tab"
                        :aria-selected="activeTab === 'backup'"
                        @click="activeTab = 'backup'"
                        :class="activeTab === 'backup' ? 'bg-blue-600 text-white shadow' : 'text-zinc-400 hover:text-zinc-200'"
                        class="px-3.5 py-2 rounded-lg text-xs sm:text-sm font-medium transition-all duration-200 cursor-pointer"
                    >
                        Backup &amp; Ops
                    </button>
                </div>
            </div>

            <div class="pt-6 grid grid-cols-1 lg:grid-cols-12 gap-6 items-center">
                <div class="lg:col-span-7 space-y-3">
                    <span
                        x-text="guides[activeTab].badge"
                        class="inline-block px-2.5 py-1 text-xs font-medium rounded-md bg-cyan-500/10 text-cyan-300 border border-cyan-500/20"
                    ></span>
                    <h3 x-text="guides[activeTab].title" class="text-xl font-bold text-white"></h3>
                    <p x-text="guides[activeTab].summary" class="text-sm text-zinc-400 leading-relaxed"></p>
                </div>

                <div class="lg:col-span-5">
                    <div class="rounded-xl bg-zinc-950 border border-zinc-800 p-4 space-y-3">
                        <div class="flex items-center justify-between text-xs text-zinc-500">
                            <span class="font-mono">CLI Reference</span>
                            <button
                                type="button"
                                @click="copyCmd()"
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-zinc-900 hover:bg-zinc-800 text-zinc-300 border border-zinc-700/80 transition cursor-pointer"
                            >
                                <span x-text="copied ? 'Copied' : 'Copy'"></span>
                            </button>
                        </div>
                        <pre class="overflow-x-auto text-xs sm:text-sm font-mono text-cyan-300"><code x-text="guides[activeTab].cmd"></code></pre>
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
            const items = document.querySelectorAll('[data-reveal]');
            if (!items.length) return;

            const prefersReduced = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            if (prefersReduced || !('IntersectionObserver' in window)) {
                items.forEach((el) => el.classList.add('is-visible'));
                return;
            }

            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.12, rootMargin: '0px 0px -24px 0px' });

            items.forEach((el) => observer.observe(el));
        })();
    </script>
</body>
</html>
