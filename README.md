<div align="center">

<!-- Heroicon: shield-check (Heroicons Outline 24x24) -->
<svg xmlns="http://www.w3.org/2000/svg" width="52" height="52" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-label="Heroicon Shield Check">
  <path d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
</svg>

# Nusantara CrowdSec Portal

**A modern, portable web dashboard for CrowdSec.**  
*Built for infrastructure and security administrators who need visibility, mitigation control, and operational documentation without SSH access.*

[![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![Livewire](https://img.shields.io/badge/Livewire-4-4E56A6?style=for-the-badge)](https://livewire.laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.4-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-4-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white)](https://tailwindcss.com)
[![License](https://img.shields.io/badge/license-MIT-10b981?style=for-the-badge)](LICENSE)

<br>

| Security Operations | Direct LAPI Integration | Portable Web Control | Zero-SSH Daily Ops |
| :---: | :---: | :---: | :---: |
| Real-time threat telemetry, decisions, and allowlists | Communicates over HTTP using Bouncer & Machine keys | Runs cleanly on Nginx, Apache, or OpenLiteSpeed | Manage alerts, exports, backups, and settings in browser |

</div>

---

## Navigation

| Section | Focus | Section | Focus |
| :--- | :--- | :--- | :--- |
| [Overview](#overview) | Portal architecture & operational model | [Installation Guide](#installation-guide) | Step-by-step setup & environment config |
| [Architecture & Reliability](#architecture--reliability) | Caching, locking, logging & migrations | [Scheduler & Background Tasks](#scheduler--background-tasks) | Minute-level cron & overlap protection |
| [Features](#features) | Core modules & operational capabilities | [Troubleshooting](#troubleshooting) | Fast fixes for LAPI, Vite & cache issues |
| [Screenshots](#screenshots) | Interface previews | [Tech Stack](#tech-stack) | Frameworks, charts & data stack |
| [Default Admin Credentials](#default-admin-credentials) | Initial sign-in & security hardening | [Changelog](#changelog) | Recent updates & release highlights |
| [User Management (CLI)](#user-management-cli) | Admin accounts & password resets | [License](#license) | MIT open-source terms |

---

## Overview

<!-- Heroicon: book-open -->
<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" /></svg> **About**

**Nusantara CrowdSec Portal** translates CrowdSec security operations into a dark-mode web interface built with Laravel 12, Livewire 4 (Volt), Flux UI, and Tailwind CSS 4. Administrators can inspect threat telemetry, review decisions, manage IP allowlists, export reports, and configure site and notification settings from the browser.

The application communicates **directly with CrowdSec Local API (LAPI) over HTTP** using Bouncer and Watcher/Machine credentials—avoiding shell execution from the web application layer and keeping system privileges separated.

---

## Architecture & Reliability

<!-- Heroicon: computer-desktop -->
<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 17.25v1.007a3 3 0 0 1-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0 1 15 18.257V17.25m6-12V15a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 15V5.25m18 0A2.25 2.25 0 0 0 18.75 3H5.25A2.25 2.25 0 0 0 3 5.25m18 0V12a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 12V5.25" /></svg> **Operational Design**

- **LAPI Client Split (`CrowdSecLapiClient`):** Uses Bouncer API key authentication (`X-Api-Key`) for reading decisions and Watcher/Machine JWT authentication (`/v1/watchers/login`) for alert polling and decision creation, with failure logging on LAPI calls.
- **GeoIP Negative Caching (`GeoIpService`):** Caches resolved IP geolocation lookups and applies a short-lived negative cache marker (`__geoip_failed__` for 20 minutes) when external lookups time out or fail, preventing repeated blocking requests and excessive warning logs.
- **Concurrency-Safe Alert Polling (`CheckCrowdSecAlerts` & Scheduler):** Uses cache lock guards and `withoutOverlapping(5)` on the minute-level alert check schedule so overlapping runs do not duplicate notifications.
- **Resilient Boot & Portable Migrations (`AppServiceProvider` & Migrations):** Wraps database-backed CrowdSec configuration overrides in fault-tolerant checks with short caching, and guards settings seed/update migrations with `Schema::hasTable('settings')` for clean execution across fresh databases and test runners.

---

## Features

<!-- Heroicon: sparkles -->
<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 0 0-2.456 2.456ZM16.894 20.567 16.5 21.75l-.394-1.183a2.25 2.25 0 0 0-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 0 0 1.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 0 0 1.423 1.423l1.183.394-1.183.394a2.25 2.25 0 0 0-1.423 1.423Z" /></svg> **Core Capabilities**

| Module | Description | Key Capabilities |
| :--- | :--- | :--- |
| **Real-Time Dashboard** | Overview of active threat telemetry and mitigation posture | LAPI health status, total active blocks, Top 5 countries, Top 5 scenarios, daily attack trends (Chart.js), and 15-second auto-refresh |
| **Decision Manager** | Full lifecycle management of CrowdSec decisions | Search/filter active decisions, create manual bans with duration and reason, and remove/unban IPs |
| **Allowlist Manager** | Trusted address management | Add and manage allowlisted IPs/CIDR ranges with validation so trusted sources are not blocked |
| **Live Attack Map** | Geographic visualization of incoming threats | Interactive map and attack path lines rendered via Apache ECharts with GeoIP caching |
| **Reports** | Operational alert and mitigation reporting | Interactive filtering (`search`, `scenario`, `country`, `decision`), normalized scenario labels, and PDF/Excel export with charts |
| **Notification Settings** | Automated alert delivery for selected scenarios | Filter by scenario name and deliver alerts via dynamically configured SMTP email and Telegram bot (`timeout(5)`) |
| **Site Settings** | Dynamic landing page and portal configuration | Customize application name, branding, hero copy, hero icon (`heroicon`), and feature cards stored in database settings |
| **Backup Manager** | Database/archive backup and download utilities | Create full, database, or file backups, run cleanups, and download `.zip` archives via a dedicated `BinaryFileResponse` route |
| **Audit Logs** | Traceability for administrative actions | Records actor identity, IP address, action type, target resource, and metadata |

---

## Screenshots

<!-- Heroicon: photo -->
<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" /></svg> **Interface Preview**

<details open>
<summary><strong>Click to expand / collapse portal screenshots</strong></summary>

<br>

<img width="1235" height="621" alt="Nusantara CrowdSec Portal Screenshot 1" src="https://github.com/user-attachments/assets/efedfabb-7cee-4558-b08f-0b7c4262aa55" />
<img width="1255" height="617" alt="Nusantara CrowdSec Portal Screenshot 2" src="https://github.com/user-attachments/assets/6b400612-d019-4cca-9a28-43314dbc26b0" />
<img width="1230" height="622" alt="Nusantara CrowdSec Portal Screenshot 3" src="https://github.com/user-attachments/assets/bd1b85fb-f2bb-4ebc-9e9e-debc9685b560" />

</details>

---

## Default Admin Credentials

<!-- Heroicon: key -->
<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z" /></svg> **Initial Access**

After completing installation and running database seeders, sign in with:

- **Email:** `admin@yourdomain.com`
- **Password:** `CrowdSec2026!`

> [!WARNING]
> Change the default password immediately after your first login via **Profile Settings**, ensure `APP_DEBUG=false` in production, and restrict access to `.env` (`chmod 640 .env`).

---

## User Management (CLI)

<!-- Heroicon: users -->
<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" /></svg> **CLI Administration**

Account creation and password resets are performed via Artisan Tinker from the application directory:

<details open>
<summary><strong>1. Adding a New Admin User</strong></summary>

<br>

```bash
cd /path/to/nusantara-crowdsec
php artisan tinker
```

Inside Tinker:

```php
\App\Models\User::create([
    'name' => 'New Admin',
    'email' => 'newadmin@yourdomain.com',
    'password' => bcrypt('YourNewStrongPassword!'),
]);
```

Type `exit` to leave Tinker.

</details>

<details open>
<summary><strong>2. Resetting an Existing User's Password</strong></summary>

<br>

```bash
cd /path/to/nusantara-crowdsec
php artisan tinker --execute="\App\Models\User::where('email', 'admin@yourdomain.com')->update(['password' => bcrypt('NewPassword123!')]);"
```

</details>

> [!IMPORTANT]
> Always hash passwords with `bcrypt()` (or `Hash::make()`); plain-text strings will prevent authentication.

---

## Installation Guide

<!-- Heroicon: rocket-launch -->
<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M15.59 14.37a6 6 0 0 1-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 0 0 6.16-12.12A14.98 14.98 0 0 0 9.631 8.41m5.96 5.96a14.926 14.926 0 0 1-5.841 2.58m-.119-8.54a6 6 0 0 0-7.381 5.84h4.8m2.581-5.84a14.927 14.927 0 0 0-2.58 5.84m2.699 2.7c-.103.021-.207.041-.311.06a15.09 15.09 0 0 1-2.448-2.448 14.9 14.9 0 0 1 .06-.312m-2.24 2.39a4.493 4.493 0 0 0-1.757 4.306 4.493 4.493 0 0 0 4.306-1.758M16.5 9a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z" /></svg> **Setup Steps**

### 1. System Requirements

| Component | Recommended / Minimum | Verification Command |
| :--- | :--- | :--- |
| **PHP** | 8.2+ (8.4 recommended) | `php -v` |
| **Composer** | 2.6+ | `composer --version` |
| **Node.js** | 20+ | `node -v` |
| **NPM** | 10+ | `npm -v` |
| **Database** | MySQL 8.0+ / MariaDB | `mysql --version` |

Required PHP extensions on Debian/Ubuntu:

```bash
sudo apt install php8.4-{cli,mysql,xml,mbstring,zip,curl,gd,bcmath} -y
```

### 2. Install & Verify CrowdSec

CrowdSec Local API (LAPI) must be installed and running:

```bash
curl -s [https://install.crowdsec.net](https://install.crowdsec.net) | sudo sh
sudo systemctl enable --now crowdsec
sudo cscli lapi status
```

### 3. Clone Repository & Install Dependencies

```bash
git clone [https://github.com/ze0expl01t/nusantara-crowdsec.git](https://github.com/ze0expl01t/nusantara-crowdsec.git)
cd nusantara-crowdsec
composer install --optimize-autoloader
npm install
```

### 4. Generate CrowdSec Bouncer & Machine Credentials

```bash
# Read-only / bouncer key for querying decisions
sudo cscli bouncers add nusantara-reader

# Machine credentials for creating/removing decisions and querying alerts
sudo cscli machines add nusantara-writer --auto
```

### 5. Configure Environment (`.env`)

```bash
cp .env.example .env
nano .env
```

Update your database and CrowdSec settings:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=[https://your-domain.com](https://your-domain.com)

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nusantara_crowdsec
DB_USERNAME=nusantara_user
DB_PASSWORD="YourStrongPassword"

CROWDSEC_LAPI_URL=[http://127.0.0.1:8080](http://127.0.0.1:8080)
CROWDSEC_BOUNCER_KEY="Your_Bouncer_Key"
CROWDSEC_MACHINE_NAME="nusantara-writer"
CROWDSEC_MACHINE_PASSWORD="Your_Machine_Password"
```

Generate the application key and set restrictive `.env` permissions:

```bash
php artisan key:generate
chmod 640 .env
```

### 6. Run Migrations, Seeders & Build Assets

```bash
php artisan migrate:fresh --seed
npm run build
chmod -R 775 storage bootstrap/cache
```

### 7. Web Server Configuration

Set your web server (**Nginx**, **Apache**, or **OpenLiteSpeed**) document root to the `public/` directory of the application.

---

## Scheduler & Background Tasks

<!-- Heroicon: clock -->
<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg> **Cron Schedule**

Ensure the Laravel scheduler runs every minute via cron:

```bash
* * * * * cd /path/to/nusantara-crowdsec && php artisan schedule:run >> /dev/null 2>&1
```

You can inspect active scheduled jobs (including `crowdsec:check-alerts` configured with `withoutOverlapping(5)`) with:

```bash
php artisan schedule:list
```

---

## Troubleshooting

<!-- Heroicon: wrench-screwdriver -->
<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M11.42 15.17 17.25 21A2.652 2.652 0 0 0 21 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 1 1-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 0 0 4.486-6.336l-3.276 3.277a3.004 3.004 0 0 1-2.25-2.25l3.276-3.276a4.5 4.5 0 0 0-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437 1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008Z" /></svg> **Common Issues & Fixes**

<details open>
<summary><strong>HTTP 403 or Empty Data from CrowdSec LAPI</strong></summary>

<br>

- **Cause:** Invalid or revoked `CROWDSEC_BOUNCER_KEY` / machine credentials, or CrowdSec LAPI is stopped.
- **Fix:** Verify LAPI and test the bouncer key:

```bash
sudo cscli lapi status
curl -i -s -H "X-Api-Key: YOUR_BOUNCER_KEY" [http://127.0.0.1:8080/v1/decisions](http://127.0.0.1:8080/v1/decisions)
```

If needed, recreate the bouncer and update `.env` (or Site Settings), then clear config cache via `php artisan config:clear`.

</details>

<details open>
<summary><strong><code>Permission denied</code> on <code>npm run build</code> (<code>noexec</code> mount)</strong></summary>

<br>

- **Cause:** The filesystem mount where the project resides has `noexec` enabled on executable binaries inside `node_modules/.bin`.
- **Fix:** Fix ownership and invoke Vite directly via `node`:

```bash
chown -R $USER:$USER node_modules public storage bootstrap/cache
node node_modules/vite/bin/vite.js build
```

</details>

<details open>
<summary><strong>Stale Configuration or View Cache After Updates</strong></summary>

<br>

- **Fix:** Clear cached configuration, views, and application cache:

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

</details>

---

## Tech Stack

<!-- Heroicon: code-bracket -->
<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M17.25 6.75 22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3-4.5 16.5" /></svg> **Stack**

- **Backend:** Laravel 12, PHP 8.4
- **Frontend:** Livewire 4 (Volt), Flux UI, Tailwind CSS 4
- **Data Visualization:** Chart.js, Apache ECharts
- **Database:** MySQL 8.0+ / MariaDB
- **Threat Engine:** CrowdSec LAPI

---

## Changelog

<!-- Heroicon: clock -->
<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg> **Recent Updates (`v1.0.0` Latest)**

- **Localization (English UI & Pagination):** Translated navigation labels (`Decisions`), dashboard charts (`Attacks`, `Blocked IPs`), Attack Map error notice, Backup Manager messages, Site Settings defaults, PDF report headers (`CrowdSec Security Report`, `Attacker IP`, `Scenario`), and Livewire Tailwind pagination summary (`Showing ... to ... of ... results`) to English.
- **Reports & Chart Normalization:** Added interactive filtering (`search`, `scenario`, `country`, `decision`, and reset) applied consistently to tables, charts, PDF exports, and Excel exports, normalized empty/malformed scenario labels to `Unknown`, and added a 5-second timeout with warning logs for QuickChart rendering.
- **Portable Backup Download (`BinaryFileResponse`):** Refactored backup archive downloads to a dedicated authenticated `GET settings/backup/download/{filename}` route via `BackupDownloadController` using `response()->download(...)` with `basename()` and `.zip` validation to avoid Livewire memory exhaustion on large archives.
- **Interactive Landing Page, Hero Icon & Colorful Pulse Line:** Added scroll-reveal and ambient motion animations (`prefers-reduced-motion` aware), configurable `heroicon` support on the landing page, interactive workflow guide tabs (`Decisions`, `Reports`, `Backup`) with one-click CLI command copy, and colorful animated pulse lines below the header navigation and top of the workflow guide (excluding feature cards and hero subtitle).
- **Reliability & Test Coverage:** Added GeoIP negative caching (`__geoip_failed__` for 20 minutes), LAPI error/warning logging (`deleteDecision`, `isConnected`, `getAlerts`), 5-second Telegram alert timeout, `CheckCrowdSecAlerts` lock & `withoutOverlapping(5)`, cached/guarded boot settings & portable migrations, 15-minute `refresh_dashboard` audit throttling, `PortalModulesTest`, and full passing test suite (`26 passed, 60 assertions`).

---

## License

<!-- Heroicon: document-text -->
<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg> **License**

This project is open-sourced under the [MIT License](LICENSE).

<div align="center">
  <b>Maintained by arkanintermedia</b><br>
  <i>Built in Indonesia</i>
</div>
