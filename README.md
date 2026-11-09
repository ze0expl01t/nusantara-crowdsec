# 🛡️ Nusantara CrowdSec Portal

A modern, portable web dashboard for CrowdSec.

## Features
- Real-time dashboard (LAPI monitoring)
- Decisions manager (ban/unban)
- Allowlist manager
- Live attack map (heatmap)
- PDF/Excel reports
- Audit logs
- Site branding settings
- Backup manager

## Requirements
- PHP 8.2+, Composer 2.6+
- Node.js 20+ / NPM 10+
- MySQL 8.0+
- CrowdSec 1.8+ (running on 127.0.0.1:8080)

## Installation
1. `git clone https://github.com/ze0expl01t/nusantara-crowdsec.git`
2. `composer install`
3. `npm install`
4. `cp .env.example .env`
5. Configure `.env` (DB, CrowdSec Bouncer Key & Machine Password)
6. `php artisan key:generate`
7. `php artisan migrate --seed`
8. Create admin: `php artisan tinker` -> `\App\Models\User::create([...]);`
9. `npm run build`

## License
MIT License
