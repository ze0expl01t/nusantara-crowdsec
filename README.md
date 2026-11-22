<div align="center">

<img src="https://api.iconify.design/heroicons/shield-check.svg?color=%233b82f6" width="52" height="52" alt="Shield Check Icon" />

# Nusantara CrowdSec Portal

**A modern, portable web dashboard for CrowdSec.**  
*Built for infrastructure and security administrators who need visibility, mitigation control, and operational documentation without SSH access.*

[![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![Livewire](https://img.shields.io/badge/Livewire-4-4E56A6?style=for-the-badge)](https://livewire.laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.4-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-4-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white)](https://tailwindcss.com)
[![License](https://img.shields.io/badge/license-MIT-10b981?style=for-the-badge)](LICENSE)

</div>

| Security Operations | Direct LAPI Integration | Portable Web Control | Zero-SSH Daily Ops |
| :--- | :--- | :--- | :--- |
| Real-time threat telemetry, decisions, and allowlists | Communicates over HTTP using Bouncer & Machine keys | Runs cleanly on Nginx, Apache, or OpenLiteSpeed | Manage alerts, exports, backups, and settings in browser |

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

<img src="https://api.iconify.design/heroicons/book-open.svg?color=%2360a5fa" width="18" height="18" alt="" /> **About**

**Nusantara CrowdSec Portal** translates CrowdSec security operations into a dark-mode web interface built with Laravel 12, Livewire 4 (Volt), Flux UI, and Tailwind CSS 4. Administrators can inspect threat telemetry, review decisions, manage IP allowlists, export reports, and configure site and notification settings from the browser.

The application communicates **directly with CrowdSec Local API (LAPI) over HTTP** using Bouncer and Watcher/Machine credentials—avoiding shell execution from the web application layer and keeping system privileges separated.

---

## Architecture & Reliability

<img src="https://api.iconify.design/heroicons/computer-desktop.svg?color=%2338bdf8" width="18" height="18" alt="" /> **Operational Design**

- **LAPI Client Split (`CrowdSecLapiClient`):** Uses Bouncer API key authentication (`X-Api-Key`) for reading decisions and Watcher/Machine JWT authentication (`/v1/watchers/login`) for alert polling and decision creation, with failure logging on LAPI calls.
- **GeoIP Negative Caching (`GeoIpService`):** Caches resolved IP geolocation lookups and applies a short-lived negative cache marker (`__geoip_failed__` for 20 minutes) when external lookups time out or fail, preventing repeated blocking requests and excessive warning logs.
- **Concurrency-Safe Alert Polling (`CheckCrowdSecAlerts` & Scheduler):** Uses cache lock guards and `withoutOverlapping(5)` on the minute-level alert check schedule so overlapping runs do not duplicate notifications.
- **Resilient Boot & Portable Migrations (`AppServiceProvider` & Migrations):** Wraps database-backed CrowdSec configuration overrides in fault-tolerant checks with short caching, and guards settings seed/update migrations with `Schema::hasTable('settings')` for clean execution across fresh databases and test runners.

---

## Features

<img src="https://api.iconify.design/heroicons/sparkles.svg?color=%2334d399" width="18" height="18" alt="" /> **Core Capabilities**

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

<img src="https://api.iconify.design/heroicons/photo.svg?color=%23a78bfa" width="18" height="18" alt="" /> **Interface Preview**

<details open>
<summary><strong>Click to expand or collapse portal screenshots</strong></summary>

<img width="1235" height="621" alt="Nusantara CrowdSec Portal Screenshot 1" src="https://github.com/user-attachments/assets/efedfabb-7cee-4558-b08f-0b7c4262aa55" />
<img width="1255" height="617" alt="Nusantara CrowdSec Portal Screenshot 2" src="https://github.com/user-attachments/assets/6b400612-d019-4cca-9a28-43314dbc26b0" />
<img width="1230" height="622" alt="Nusantara CrowdSec Portal Screenshot 3" src="https://github.com/user-attachments/assets/bd1b85fb-f2bb-4ebc-9e9e-debc9685b560" />

</details>

---

## Default Admin Credentials

<img src="https://api.iconify.design/heroicons/key.svg?color=%23fbbf24" width="18" height="18" alt="" /> **Initial Access**

After completing installation and running database seeders, sign in with:

- **Email:** `admin@yourdomain.com`
- **Password:** `CrowdSec2026!`

> [!WARNING]
> Change the default password immediately after your first login via **Profile Settings**, ensure `APP_DEBUG=false` in production, and restrict access to `.env` (`chmod 640 .env`).

---

## User Management (CLI)

<img src="https://api.iconify.design/heroicons/users.svg?color=%2322d3ee" width="18" height="18" alt="" /> **CLI Administration**

Account creation and password resets are performed via Artisan Tinker from the application directory:

<details open>
<summary><strong>1. Adding a New Admin User</strong></summary>

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

```bash
cd /path/to/nusantara-crowdsec
php artisan tinker --execute="\App\Models\User::where('email', 'admin@yourdomain.com')->update(['password' => bcrypt('NewPassword123!')]);"
```

</details>

> [!IMPORTANT]
> Always hash passwords with `bcrypt()` (or `Hash::make()`); plain-text strings will prevent authentication.

---

## Installation Guide

<img src="https://api.iconify.design/heroicons/rocket-launch.svg?color=%23f472b6" width="18" height="18" alt="" /> **Setup Steps**

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

<img src="https://api.iconify.design/heroicons/clock.svg?color=%23fb923c" width="18" height="18" alt="" /> **Cron Schedule**

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

<img src="https://api.iconify.design/heroicons/wrench-screwdriver.svg?color=%23f87171" width="18" height="18" alt="" /> **Common Issues & Fixes**

<details open>
<summary><strong>HTTP 403 or Empty Data from CrowdSec LAPI</strong></summary>

- **Cause:** Invalid or revoked `CROWDSEC_BOUNCER_KEY` / machine credentials, or CrowdSec LAPI is stopped.
- **Fix:** Verify LAPI and test the bouncer key:

```bash
sudo cscli lapi status
curl -i -s -H "X-Api-Key: YOUR_BOUNCER_KEY" [http://127.0.0.1:8080/v1/decisions](http://127.0.0.1:8080/v1/decisions)
```

If needed, recreate the bouncer and update `.env` (or Site Settings), then clear config cache via `php artisan config:clear`.

</details>

<details open>
<summary><strong>Permission denied on npm run build (noexec mount)</strong></summary>

- **Cause:** The filesystem mount where the project resides has `noexec` enabled on executable binaries inside `node_modules/.bin`.
- **Fix:** Fix ownership and invoke Vite directly via `node`:

```bash
chown -R $USER:$USER node_modules public storage bootstrap/cache
node node_modules/vite/bin/vite.js build
```

</details>

<details open>
<summary><strong>Stale Configuration or View Cache After Updates</strong></summary>

- **Fix:** Clear cached configuration, views, and application cache:

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

</details>

---

## Tech Stack

<img src="https://api.iconify.design/heroicons/code-bracket.svg?color=%23818cf8" width="18" height="18" alt="" /> **Stack**

- **Backend:** Laravel 12, PHP 8.4
- **Frontend:** Livewire 4 (Volt), Flux UI, Tailwind CSS 4
- **Data Visualization:** Chart.js, Apache ECharts
- **Database:** MySQL 8.0+ / MariaDB
- **Threat Engine:** CrowdSec LAPI

---

## Changelog

<img src="https://api.iconify.design/heroicons/clock.svg?color=%2334d399" width="18" height="18" alt="" /> **Recent Updates (`v1.0.0` Latest)**

- **Localization (English UI & Pagination):** Translated navigation labels (`Decisions`), dashboard charts (`Attacks`, `Blocked IPs`), Attack Map error notice, Backup Manager messages, Site Settings defaults, PDF report headers (`CrowdSec Security Report`, `Attacker IP`, `Scenario`), and Livewire Tailwind pagination summary (`Showing ... to ... of ... results`) to English.
- **Reports & Chart Normalization:** Added interactive filtering (`search`, `scenario`, `country`, `decision`, and reset) applied consistently to tables, charts, PDF exports, and Excel exports, normalized empty/malformed scenario labels to `Unknown`, and added a 5-second timeout with warning logs for QuickChart rendering.
- **Portable Backup Download (`BinaryFileResponse`):** Refactored backup archive downloads to a dedicated authenticated `GET settings/backup/download/{filename}` route via `BackupDownloadController` using `response()->download(...)` with `basename()` and `.zip` validation to avoid Livewire memory exhaustion on large archives.
- **Interactive Landing Page, Hero Icon & Colorful Pulse Line:** Added scroll-reveal and ambient motion animations (`prefers-reduced-motion` aware), configurable `heroicon` support on the landing page, interactive workflow guide tabs (`Decisions`, `Reports`, `Backup`) with one-click CLI command copy, and colorful animated pulse lines below the header navigation and top of the workflow guide (excluding feature cards and hero subtitle).
- **Reliability & Test Coverage:** Added GeoIP negative caching (`__geoip_failed__` for 20 minutes), LAPI error/warning logging (`deleteDecision`, `isConnected`, `getAlerts`), 5-second Telegram alert timeout, `CheckCrowdSecAlerts` lock & `withoutOverlapping(5)`, cached/guarded boot settings & portable migrations, 15-minute `refresh_dashboard` audit throttling, `PortalModulesTest`, and full passing test suite (`26 passed, 60 assertions`).

---

## License

<img src="https://api.iconify.design/heroicons/document-text.svg?color=%2394a3b8" width="18" height="18" alt="" /> **License**

This project is open-sourced under the [MIT License](LICENSE).

<div align="center">
  <b>Maintained by arkanintermedia</b><br>
  <i>Built in Indonesia</i>
</div>
<div align="center">
  <a href="https://saweria.co/arkanintermedia">
      <img width="1024" height="254" alt="image" src="https://github.com/user-attachments/assets/1e83252f-20e1-4282-b466-f6ee5cb09148" />
  </a>
</div>
