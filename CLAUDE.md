# Metin2 Ignition - Claude Code Instructions

## Project Overview

Laravel 12 + Filament 3 web platform for **Metin2 Ignition** private server.
- Public site: download, ranking, news, registration
- Admin panel (Filament): player management, events, accounts, CMS

---

## Stack

| Layer       | Technology                          |
|-------------|-------------------------------------|
| Framework   | Laravel 12                          |
| Admin panel | Filament 3                          |
| PHP         | 8.4                                 |
| DB (web)    | MySQL/MariaDB — `metin2_web` on Ubuntu host |
| DB (game)   | MariaDB 10.6 on FreeBSD VM (remote) |
| OS          | Ubuntu Linux (web host)             |
| Code style  | PSR-12                              |

---

## Database Layout

```
FreeBSD VM (game server)         Ubuntu host (web server)
────────────────────────         ────────────────────────
account      ← remote read/write  metin2_web  ← local
player       ← remote read/write    └── admins        (Filament admin users)
common       ← remote read-only     └── sessions
log          ← remote read-only     └── cache
hotbackup    ← remote read-only     └── (any future web-only tables)
```

**Nothing is added to the FreeBSD VM databases.** `metin2_web` is owned entirely by Laravel.

---

## Authentication — Two Separate Mechanisms

### 1. Players — site login/register (`/login`, `/register`)
- Guard: `metin2` → `App\Models\Metin2\Account` → `account.accounts` (remote)
- Password: MySQL `PASSWORD()` via `MysqlPasswordHasher` — **never `Hash::make()` or `md5()`**
- Session: stored in `metin2_web.sessions`

### 2. Admins — Filament panel (`/admin`)
- Guard: `web` → `App\Models\Web\Admin` → `metin2_web.admins` (local)
- Password: bcrypt via `Hash::make()` — standard Laravel
- Created via `php artisan make:filament-user` or seeder

The two guards are **completely independent**. Player accounts do not grant admin access.

See: `config/auth.php`, `app/Hashing/MysqlPasswordHasher.php`

---

## Password Hashing — MysqlPasswordHasher

Replicates MySQL `PASSWORD()`: `'*' . strtoupper(sha1(sha1($value, true)))`
- 41-char hash starting with `*`
- Used **only** for the `metin2` guard
- `needsRehash()` always returns `false`

See: `app/Hashing/MysqlPasswordHasher.php`

---

## Database Connections

Six connections defined in `config/database.php`:
- `mysql` — local `metin2_web` (Laravel-owned)
- `account`, `player`, `common`, `log`, `hotbackup` — remote FreeBSD VM, same host/user/pass, different database name

Env vars: `DB_*` for web DB, `METIN2_DB_HOST/PORT/USER/PASS` for game DBs.

**Migrations only against `mysql`:** `php artisan migrate --database=mysql`

---

## Key Tables (Game DB — read-only unless noted)

### `account.accounts` — read/write
| Column      | Type         | Notes                                          |
|-------------|--------------|------------------------------------------------|
| id          | int          | PK                                             |
| login       | varchar(30)  | Username                                       |
| password    | varchar(45)  | MySQL PASSWORD() hash — 41 chars, starts with `*` |
| social_id   | varchar(14)  | Required, non-null — generate on registration  |
| email       | varchar(100) |                                                |
| status      | varchar(8)   | `OK`, `BLOCK`, `QUIT`                          |
| availdt     | datetime     | Ban expiry                                     |
| gold_expire | datetime     | Premium expiry                                 |
| create_time | datetime     |                                                |

**Ban:** `status = 'BLOCK'`, `availdt` = expiry datetime.
**Register:** set `status = 'OK'`, `social_id` = `substr(md5(uniqid()), 0, 13)`, `create_time` = now().

### `player.player` — read/write
| Column     | Type        | Notes                                 |
|------------|-------------|---------------------------------------|
| id         | int         | Character ID                          |
| account_id | int         | FK → account.accounts.id             |
| name       | varchar(24) |                                       |
| job        | tinyint     | 0=Warrior,1=Assassin,2=Sura,3=Shaman |
| level      | tinyint     | 1–120+                                |
| exp        | bigint      |                                       |
| gold       | int         | Yang                                  |
| empire     | tinyint     | 1=Red,2=Yellow,3=Blue                 |
| playtime   | int         | Minutes played                        |
| last_play  | datetime    |                                       |
| create_time| datetime    |                                       |

### Other tables
- `player.item` — inventory/equipment items, `owner_id` → `player.id`
- `player.guild` — guilds, `master` → `player.id`
- `common.item_proto`, `common.mob_proto` — read-only proto tables, query by `vnum`

---

## Project Structure (target)

```
app/
├── Filament/Resources/          — AccountResource, PlayerResource, NewsResource, EventResource
├── Hashing/MysqlPasswordHasher.php
├── Http/Controllers/
│   ├── Auth/LoginController.php       (guard: metin2)
│   ├── Auth/RegisterController.php    (writes to account.accounts)
│   ├── HomeController.php
│   ├── RankingController.php
│   ├── NewsController.php
│   └── DownloadController.php
├── Models/
│   ├── Metin2/Account.php   ($connection = 'account', $timestamps = false)
│   ├── Metin2/Player.php    ($connection = 'player',  $timestamps = false)
│   ├── Metin2/Item.php      ($connection = 'player',  $timestamps = false)
│   ├── Metin2/Guild.php     ($connection = 'player',  $timestamps = false)
│   ├── Web/Admin.php        ($connection = 'mysql', FilamentUser)
│   └── Web/News.php         ($connection = 'mysql')
└── Services/PlayerService.php
config/auth.php                  — two guards: web + metin2
config/database.php              — six connections
database/migrations/             — only metin2_web tables
routes/web.php
```

---

## Key Constraints

- **Player passwords:** use `MysqlPasswordHasher` — never `md5()` or `Hash::make()`
- **Admin passwords:** bcrypt — `Hash::make()`
- **Game models:** always `public $timestamps = false`
- **Game DB is live:** prefer reads; writes assume character is offline
- **social_id** is required and non-null — always generate on registration
- **Migrations:** never run against game DBs

---

## Workflow — Adding a New Feature

**Public page:** route → controller → model (correct `$connection`) → blade view

**Admin action:** Filament Resource → `app/Services/` → `Action::make()`

**New game model:** `app/Models/Metin2/`, set `$connection`, `$table`, `$timestamps = false`, conservative `$fillable`

---

## Work Phases

Detailed specs in `.claude/phases/`. Complete in order, do not start next phase until current is verified:

1. `.claude/phases/phase-1-foundation.md` — DB connections, auth guards, models, migrations
2. `.claude/phases/phase-2-public-site.md` — layout, login/register, ranking, news, download
3. `.claude/phases/phase-3-admin-panel.md` — Filament resources, dashboard widgets
4. `.claude/phases/phase-4-polish.md` — rate limiting, error pages, SEO, production
