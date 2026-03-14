# Phase 1 — Foundation (DB + Auth)

Complete all tasks in order. Mark with [x] when done.
Do not move to Phase 2 until all tasks here are complete and tested.

---

## 1. Database Connections — `config/database.php`

Add all 6 connections:
- `mysql` — metin2_web (Laravel-owned, Ubuntu local)
- `account` — game accounts (FreeBSD VM, remote)
- `player` — game characters/items/guilds (FreeBSD VM, remote)
- `common` — item_proto, mob_proto (FreeBSD VM, read-only)
- `log` — game logs (FreeBSD VM, read-only)
- `hotbackup` — backup (FreeBSD VM, read-only)

All game connections use env vars: `METIN2_DB_HOST`, `METIN2_DB_PORT`, `METIN2_DB_USER`, `METIN2_DB_PASS`.

- [ ] `config/database.php` updated with all 6 connections

---

## 2. Auth Guards — `config/auth.php`

Two completely separate guards:

| Guard    | Provider          | Model               | Password |
|----------|-------------------|---------------------|----------|
| `web`    | `admins`          | `Web\Admin`         | bcrypt   |
| `metin2` | `metin2_accounts` | `Metin2\Account`    | MD5      |

- [ ] `config/auth.php` updated with both guards and providers

---

## 3. MD5 Hasher — `app/Hashing/Md5Hasher.php`

Custom hasher for player passwords (game DB uses MD5, not bcrypt).
Implements `Illuminate\Contracts\Hashing\Hasher`.

Methods: `make()`, `check()`, `needsRehash()`.

Register in `AppServiceProvider` — bind only for the `metin2` guard context.

- [ ] `app/Hashing/Md5Hasher.php` created
- [ ] Registered in `AppServiceProvider`

---

## 4. Game DB Models — `app/Models/Metin2/`

Rules for ALL game models:
- `protected $connection` — required, must match connection name
- `protected $table` — required, must match exact game table name
- `public $timestamps = false` — game tables have no Laravel timestamps
- `$fillable` — conservative, only what the admin panel needs

### Account.php
- Extends `Authenticatable` (used by metin2 guard)
- connection: `account`, table: `accounts`
- fillable: `login`, `password`, `email`, `social_id`
- hidden: `password`

### Player.php
- Extends `Model`
- connection: `player`, table: `player`
- fillable: `level`, `exp`, `gold`, `map_index`, `x`, `y`

### Item.php
- Extends `Model`
- connection: `player`, table: `item`
- fillable: conservative

### Guild.php
- Extends `Model`
- connection: `player`, table: `guild`
- fillable: conservative

- [ ] `app/Models/Metin2/Account.php`
- [ ] `app/Models/Metin2/Player.php`
- [ ] `app/Models/Metin2/Item.php`
- [ ] `app/Models/Metin2/Guild.php`

---

## 5. Web DB Models — `app/Models/Web/`

### Admin.php
- Extends `Authenticatable`
- Implements `FilamentUser`
- connection: `mysql`, table: `admins`
- `canAccessPanel()` returns `true`

### News.php
- Extends `Model`
- connection: `mysql`, table: `news`
- fillable: `title`, `slug`, `body`, `excerpt`, `published_at`, `is_published`

- [ ] `app/Models/Web/Admin.php`
- [ ] `app/Models/Web/News.php`

---

## 6. Migrations (metin2_web only)

Run ONLY against `--database=mysql`. Never against game DBs.

### admins table
Columns: `id`, `name`, `email`, `password`, `remember_token`, `timestamps`

### news table
Columns: `id`, `title`, `slug`, `body`, `excerpt`, `is_published` (boolean, default false), `published_at` (nullable datetime), `timestamps`

- [ ] Migration `create_admins_table` created and run
- [ ] Migration `create_news_table` created and run
- [ ] `php artisan migrate --database=mysql` successful

---

## 7. Services — `app/Services/PlayerService.php`

Methods:
- `banAccount(Account $account, Carbon $until): void`
- `unbanAccount(Account $account): void`
- `teleportPlayer(Player $player, int $mapIndex, int $x, int $y): void`
- `setLevel(Player $player, int $level): void`

- [ ] `app/Services/PlayerService.php` created

---

## Verification

Run after all tasks complete:

```bash
php artisan config:clear
php artisan tinker --execute "DB::connection('mysql')->getPdo(); echo 'mysql ok';"
php artisan tinker --execute "DB::connection('account')->getPdo(); echo 'account ok';"
php artisan tinker --execute "DB::connection('player')->getPdo(); echo 'player ok';"
php artisan test --compact
```

All connections must respond without errors before moving to Phase 2.
