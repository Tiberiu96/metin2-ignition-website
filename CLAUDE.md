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

**Nothing is added to the FreeBSD VM databases.** `metin2_web` is a small local database on the Ubuntu host, owned entirely by Laravel.

---

## Authentication — Two Separate Mechanisms

### 1. Players — site login/register (`/login`, `/register`)

- **Source:** `account.accounts` on the FreeBSD VM (remote)
- **Guard:** custom guard named `metin2`, uses `App\Models\Metin2\Account`
- **Password:** MySQL `PASSWORD()` — always use `mysqlPassword($password)`, never `Hash::make()` or `md5()`
- **Session:** stored in `metin2_web.sessions`
- **Registration** creates a new row in `account.accounts` directly

### 2. Admins — Filament panel (`/admin`)

- **Source:** `metin2_web.admins` on the Ubuntu host (local)
- **Guard:** default Laravel `web` guard, uses `App\Models\Web\Admin`
- **Password:** bcrypt via `Hash::make()` — standard Laravel
- **Created** via `php artisan make:filament-user` or seeder

The two guards are completely independent. A player account does not grant admin access.

---

## Guard Configuration

### config/auth.php

```php
'guards' => [
    'web' => [                          // Filament admin
        'driver'   => 'session',
        'provider' => 'admins',
    ],
    'metin2' => [                       // Site players
        'driver'   => 'session',
        'provider' => 'metin2_accounts',
    ],
],

'providers' => [
    'admins' => [
        'driver' => 'eloquent',
        'model'  => App\Models\Web\Admin::class,
    ],
    'metin2_accounts' => [
        'driver' => 'eloquent',
        'model'  => App\Models\Metin2\Account::class,
    ],
],
```

### app/Models/Metin2/Account.php

```php
class Account extends Authenticatable
{
    protected $connection = 'account';
    protected $table = 'accounts';
    public $timestamps = false;

    protected $fillable = ['login', 'password', 'email', 'social_id'];
    protected $hidden = ['password'];

    // MD5 password check — override default bcrypt behaviour
    public function getAuthPassword(): string
    {
        return $this->password;
    }
}
```

### Custom MySQL Password Hasher — app/Hashing/MysqlPasswordHasher.php

Replicates MySQL's `PASSWORD()` function: `'*' . strtoupper(sha1(sha1($value, true)))`
Produces a 41-char hash starting with `*` — identical to what the game server stores.

```php
class MysqlPasswordHasher implements Hasher
{
    public function make($value, array $options = []): string
    {
        return '*' . strtoupper(sha1(sha1($value, true)));
    }

    public function check($value, $hashedValue, array $options = []): bool
    {
        return $this->make($value) === strtoupper($hashedValue);
    }

    public function needsRehash($hashedValue, array $options = []): bool
    {
        return false;
    }
}
```

Register in `AppServiceProvider::register()`:
```php
$this->app->when(\App\Http\Controllers\Auth\LoginController::class)
    ->needs(\Illuminate\Contracts\Hashing\Hasher::class)
    ->give(\App\Hashing\MysqlPasswordHasher::class);
```

Or bind conditionally per guard in the auth flow.

### app/Models/Web/Admin.php

```php
class Admin extends Authenticatable implements FilamentUser
{
    protected $connection = 'mysql';   // metin2_web on Ubuntu
    protected $table = 'admins';

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }
}
```

---

## Database Connections

### config/database.php

```php
'connections' => [

    // Laravel-owned (Ubuntu host)
    'mysql' => [
        'driver'   => 'mysql',
        'host'     => env('DB_HOST', '127.0.0.1'),
        'port'     => env('DB_PORT', '3306'),
        'database' => env('DB_DATABASE', 'metin2_web'),
        'username' => env('DB_USERNAME'),
        'password' => env('DB_PASSWORD'),
    ],

    // Game DBs (FreeBSD VM — all share same host/user/pass)
    'account' => [
        'driver'   => 'mysql',
        'host'     => env('METIN2_DB_HOST'),
        'port'     => env('METIN2_DB_PORT', '3306'),
        'database' => 'account',
        'username' => env('METIN2_DB_USER'),
        'password' => env('METIN2_DB_PASS'),
    ],
    'player' => [
        // same as above, database: 'player'
    ],
    'common' => [
        // same as above, database: 'common'
    ],
    'log' => [
        // same as above, database: 'log'
    ],
    'hotbackup' => [
        // same as above, database: 'hotbackup'
    ],
],
```

### .env

```
# Web DB (Ubuntu)
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=metin2_web
DB_USERNAME=...
DB_PASSWORD=...

# Game DBs (FreeBSD VM)
METIN2_DB_HOST=192.168.x.x
METIN2_DB_PORT=3306
METIN2_DB_USER=metin2
METIN2_DB_PASS=password
```

---

## Metin2 DB Schema — Key Tables

### `account.accounts`
| Column      | Type         | Notes                         |
|-------------|--------------|-------------------------------|
| id          | int          | Primary key                   |
| login       | varchar(30)  | Username                      |
| password    | varchar(45)  | **MySQL PASSWORD() hash** — starts with `*`, 41 chars |
| social_id   | varchar(14)  | Unique ID (required, non-null)|
| email       | varchar(100) |                               |
| status      | varchar(8)   | `OK`, `BLOCK`, `QUIT`         |
| availdt     | datetime     | Ban expiry                    |
| gold_expire | datetime     | Premium expiry                |
| create_time | datetime     |                               |
| last_play   | datetime     |                               |

> **Ban:** `status = 'BLOCK'`, `availdt` = expiry datetime.
> **Register:** `status = 'OK'`, `social_id` = unique string (e.g. random), `create_time` = now(), `password` = `'*' . strtoupper(sha1(sha1($password, true)))`

### `player.player`
| Column     | Type        | Notes                                 |
|------------|-------------|---------------------------------------|
| id         | int         | Character ID                          |
| account_id | int         | FK → account.accounts.id             |
| name       | varchar(24) |                                       |
| job        | tinyint     | 0=Warrior,1=Assassin,2=Sura,3=Shaman |
| level      | tinyint     | 1–120+                                |
| exp        | bigint      |                                       |
| gold       | int         | Yang                                  |
| map_index  | int         |                                       |
| x, y       | int         |                                       |
| empire     | tinyint     | 1=Red,2=Yellow,3=Blue                 |
| playtime   | int         | Minutes played                        |
| last_play  | datetime    |                                       |
| create_time| datetime    |                                       |

### `player.item`
| Column       | Type    | Notes                      |
|--------------|---------|----------------------------|
| id           | int     |                            |
| owner_id     | int     | FK → player.id             |
| window       | enum    | INVENTORY, EQUIPMENT, etc. |
| pos          | tinyint | Slot                       |
| vnum         | int     | Item template ID           |
| count        | tinyint |                            |
| socket0–5    | int     |                            |
| attrtype0–6  | tinyint |                            |
| attrvalue0–6 | int     |                            |

### `player.guild`
| Column | Type        | Notes          |
|--------|-------------|----------------|
| id     | int         |                |
| name   | varchar(12) |                |
| level  | tinyint     |                |
| master | int         | FK → player.id |

### `common.item_proto` / `common.mob_proto` — read-only
Standard Metin2 proto tables. Query by `vnum`.

---

## Project Structure

```
metin2-ignition/
├── app/
│   ├── Filament/Resources/
│   │   ├── PlayerResource.php
│   │   ├── AccountResource.php
│   │   ├── NewsResource.php
│   │   └── EventResource.php
│   ├── Hashing/
│   │   └── MysqlPasswordHasher.php
│   ├── Http/Controllers/
│   │   ├── Auth/
│   │   │   ├── LoginController.php      (guard: metin2)
│   │   │   └── RegisterController.php   (writes to account.accounts)
│   │   ├── HomeController.php
│   │   ├── RankingController.php
│   │   ├── NewsController.php
│   │   └── DownloadController.php
│   ├── Models/
│   │   ├── Metin2/
│   │   │   ├── Account.php   ($connection = 'account')
│   │   │   ├── Player.php    ($connection = 'player')
│   │   │   ├── Item.php      ($connection = 'player')
│   │   │   └── Guild.php     ($connection = 'player')
│   │   └── Web/
│   │       ├── Admin.php     ($connection = 'mysql', FilamentUser)
│   │       └── News.php      ($connection = 'mysql')
│   └── Services/
│       └── PlayerService.php
├── config/
│   ├── auth.php              (two guards: web + metin2)
│   └── database.php          (six connections)
├── database/migrations/      (only for metin2_web tables)
├── resources/views/
├── routes/web.php
└── CLAUDE.md
```

---

## Code Style

**PSR-12** strictly.

---

## Workflow — Adding a New Feature

### Public page
1. Route in `routes/web.php`
2. Controller in `app/Http/Controllers/`
3. Model with correct `$connection`
4. Blade view in `resources/views/`

### Admin panel action
1. Filament Resource in `app/Filament/Resources/`
2. Business logic in `app/Services/`
3. Wire via `Action::make()`

### New game DB model
1. `app/Models/Metin2/`
2. `protected $connection`, `protected $table`, `public $timestamps = false`
3. Conservative `$fillable`

### Migrations
- Only `metin2_web`: `php artisan migrate --database=mysql`
- Never against game DBs

---

## Key Constraints

- **Player passwords:** MySQL PASSWORD() — use `mysqlPassword($password)`, never `md5()` or `Hash::make()`
- **Admin passwords:** bcrypt — standard `Hash::make()`
- **No timestamps on game models:** `public $timestamps = false`
- **Game DB is live:** prefer read queries; write actions assume character is offline
- **Port 3306** must be open on the FreeBSD VM firewall for the Ubuntu host IP
- **social_id** is required and non-null in `account.accounts` — generate a value on registration (e.g. `substr(md5(uniqid()), 0, 13)`)
- **Hasher class:** `app/Hashing/MysqlPasswordHasher.php` — replicates MySQL `PASSWORD()` via double SHA1

---

## Work Phases

Detailed work phases are in `.claude/phases/`. Complete them in order:

1. `.claude/phases/phase-1-foundation.md` — DB connections, auth guards, models, migrations
2. `.claude/phases/phase-2-public-site.md` — layout, login/register, ranking, news, download
3. `.claude/phases/phase-3-admin-panel.md` — Filament resources, dashboard widgets
4. `.claude/phases/phase-4-polish.md` — rate limiting, error pages, SEO, production

**Do not start a new phase untili say it to you and the current one is fully verified.**