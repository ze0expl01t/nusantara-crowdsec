# Changelog

All notable changes to this project are documented here.

Format based on [Keep a Changelog](https://keepachangelog.com/),
this project adheres to [Semantic Versioning](https://semver.org/).

## [1.0.0] - 2026-11-09

### 🎉 Initial Release — "Nusantara"

A modern, portable web dashboard for CrowdSec.

### Added
- Real-time dashboard (LAPI status, top countries, scenarios, trends)
- Decisions manager (ban/unban via UI)
- Allowlist manager (IP/CIDR validation)
- Live attack map (heatmap with animated lines)
- Reports with PDF/Excel export
- Audit logs for every action
- Dynamic site branding settings
- Backup manager (Spatie + migrations/seeders)
- Auto-refresh dashboard (15-second polling)

### Security
- Zero sudo, all CrowdSec interactions via LAPI HTTP
- Dual authentication (Bouncer + Machine JWT)
- APP_DEBUG=false in production
- Strict .env permissions (640)
- Complete audit trail

### Known Limitations
- Country data in Decisions shows `-` (CrowdSec API limitation)
- Attack map limited to 30 IPs per refresh (rate limit protection)
- System Control (cscli web UI) removed (requires sudo, security risk)

[1.0.0]: https://github.com/ze0expl01t/nusantara-crowdsec/releases/tag/v1.0.0
