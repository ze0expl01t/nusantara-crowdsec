<div align="center">

# 🛡️ Nusantara CrowdSec Portal

**A modern, portable web dashboard for CrowdSec.**
*Built for admins who want full control without the terminal.*

[![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?logo=laravel&logoColor=white)](https://laravel.com)
[![Livewire](https://img.shields.io/badge/Livewire-4-4E56A6)](https://livewire.laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.4-777BB4?logo=php&logoColor=white)](https://php.net)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)

</div>

---

## 📖 About

**Nusantara CrowdSec Portal** transforms CrowdSec's command-line operations into a beautiful, dark-mode web experience. Manage decisions, allowlists, and alerts through an intuitive interface — no SSH required.

It talks **directly to CrowdSec LAPI via HTTP**, never touching the shell — keeping the web server isolated from system-level privileges.

---

## 🔑 Default Admin Credentials

After completing the installation and running the database seeders, you can log in using the following default credentials:

*   **Email:** `admin@smkmuh1blora.sch.id`
*   **Password:** `admin123`

> ⚠️ **SECURITY WARNING**: You **MUST** change this password immediately after your first login via the Profile Settings page.

---

## ✨ Features

| Feature | Description |
| :--- | :--- |
| 📊 **Real-time Dashboard** | LAPI status, total blocks, Top 5 countries, Top 5 scenarios, daily trends (Chart.js) |
| 🚫 **Decision Manager** | Browse, search, ban, unban IPs with duration & reason |
| ✅ **Allowlist Manager** | Whitelist trusted IPs/CIDR with regex validation |
| 🗺️ **Live Attack Map** | Heatmap visualization with animated attack lines (ECharts) |
| 📄 **Reports** | Security alerts with PDF/Excel export including charts |
| 🎨 **Site Settings** | Dynamic branding (app name, logo, hero text, features) |
| 💾 **Backup Manager** | Database backups + migrations/seeders generation |
| 📜 **Audit Logs** | Every admin action recorded with actor, IP, target |
| 🔄 **Auto-refresh** | Dashboard polls every 15 seconds |
| 🔐 **Security-First** | Zero sudo, APP_DEBUG=false, strict .env permissions |

---

## 🚀 Installation Guide

### 1. System Requirements

| Component | Version | Check Command |
| :--- | :--- | :--- |
| **PHP** | 8.2+ | `php -v` |
| **Composer** | 2.6+ | `composer --version` |
| **Node.js** | 20+ | `node -v` |
| **NPM** | 10+ | `npm -v` |
| **MySQL** | 8.0+ | `mysql --version` |

**Required PHP Extensions:**
```bash
# Debian/Ubuntu
sudo apt install php8.2-{cli,mysql,xml,mbstring,zip,curl,gd,bcmath} -y
```

### 2. Install CrowdSec (Required)

This portal **will not work** without CrowdSec running locally.

```bash
curl -s https://install.crowdsec.net | sudo sh
sudo systemctl enable --now crowdsec
sudo cscli lapi status
```

### 3. Clone Repository & Dependencies

```bash
git clone https://github.com/ze0expl01t/nusantara-crowdsec.git
cd nusantara-crowdsec
composer install --optimize-autoloader
npm install
```

### 4. Setup CrowdSec Credentials

You need two credentials: Bouncer (Read) and Machine (Write/Unban).

```bash
# 1. Bouncer Key (For reading decisions)
sudo cscli bouncers add nusantara-reader
# -> Copy the API Key

# 2. Machine Account (For banning/unbanning)
sudo cscli machines add nusantara-writer --auto
# -> Copy username & password
```

### 5. Environment Configuration

```bash
cp .env.example .env
nano .env
```

Fill in your database credentials and the CrowdSec keys you generated above:

```env
APP_URL=https://your-domain.com
DB_DATABASE=nusantara_crowdsec
DB_USERNAME=nusantara_user
DB_PASSWORD="YourStrongPassword"

CROWDSEC_BOUNCER_KEY="Your_Bouncer_Key"
CROWDSEC_MACHINE_NAME="nusantara-writer"
CROWDSEC_MACHINE_PASSWORD="Your_Machine_Password"
```

Generate the application key and secure the environment file:

```bash
php artisan key:generate
chmod 640 .env
```

### 6. Database Migration & Seeding

Create a database in MySQL, then run:

```bash
php artisan migrate:fresh --seed
```

*This command will create all tables and automatically populate the database with the default admin account and site settings.*

### 7. Build Frontend Assets

```bash
npm run build
```

### 8. Configure Web Server

Point your Nginx/Apache **Document Root** to the `public/` folder.

```bash
chmod -R 775 storage bootstrap/cache
```

---

## 🔧 Troubleshooting

### ❌ HTTP 403 on Dashboard

**Cause:** Bouncer Key is wrong or LAPI is down.

```bash
# Test key directly
curl -s -H "X-Api-Key: YOUR_KEY" http://127.0.0.1:8080/v1/decisions
# If 403, recreate bouncer: sudo cscli bouncers delete nusantara-reader && sudo cscli bouncers add nusantara-reader
```

### ❌ "Permission denied" on `npm run build` (CyberPanel/Noexec)

**Cause:** `/home` partition is mounted with `noexec`.

**Fix:** Change ownership or bypass via node.

```bash
sudo chown -R $USER:$USER node_modules public
chmod +x node_modules/.bin/vite
npm run build
```

*If it still fails, edit `package.json` and change `"build": "vite build"` to `"build": "node node_modules/vite/bin/vite.js build"`.*

---

## 🛠️ Tech Stack

- **Backend:** Laravel 12, PHP 8.4
- **Frontend:** Livewire 4 (Volt), Flux UI 2.21, Tailwind CSS 4
- **Charts:** Chart.js 4.x, Apache ECharts 4.9
- **Database:** MySQL 8.0+
- **Security Engine:** CrowdSec 1.8+

---
<img width="1235" height="621" alt="image" src="https://github.com/user-attachments/assets/efedfabb-7cee-4558-b08f-0b7c4262aa55" />
<img width="1255" height="617" alt="image" src="https://github.com/user-attachments/assets/6b400612-d019-4cca-9a28-43314dbc26b0" />
<img width="1230" height="622" alt="image" src="https://github.com/user-attachments/assets/bd1b85fb-f2bb-4ebc-9e9e-debc9685b560" />



## 📝 License

MIT License — see [LICENSE](LICENSE) for details.

<div align="center">
  <b>Maintained by arkanintermedia</b><br>
  <i>Made with ❤️ in Indonesia</i>
</div>
