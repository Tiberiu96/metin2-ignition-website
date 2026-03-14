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

===

## Screenshots
Always search screenshots in the /screenshots folder from .claude folder
===

<laravel-boost-guidelines>
=== foundation rules ===

# Laravel Boost Guidelines

The Laravel Boost guidelines are specifically curated by Laravel maintainers for this application. These guidelines should be followed closely to ensure the best experience when building Laravel applications.

## Foundational Context

This application is a Laravel application and its main Laravel ecosystems package & versions are below. You are an expert with them all. Ensure you abide by these specific packages & versions.

- php - 8.4.18
- filament/filament (FILAMENT) - v4
- laravel/framework (LARAVEL) - v12
- laravel/prompts (PROMPTS) - v0
- livewire/livewire (LIVEWIRE) - v3
- laravel/boost (BOOST) - v2
- laravel/mcp (MCP) - v0
- laravel/pail (PAIL) - v1
- laravel/pint (PINT) - v1
- laravel/sail (SAIL) - v1
- phpunit/phpunit (PHPUNIT) - v11
- tailwindcss (TAILWINDCSS) - v4

## Skills Activation

This project has domain-specific skills available. You MUST activate the relevant skill whenever you work in that domain—don't wait until you're stuck.

- `tailwindcss-development` — Styles applications using Tailwind CSS v4 utilities. Activates when adding styles, restyling components, working with gradients, spacing, layout, flex, grid, responsive design, dark mode, colors, typography, or borders; or when the user mentions CSS, styling, classes, Tailwind, restyle, hero section, cards, buttons, or any visual/UI changes.

## Conventions

- You must follow all existing code conventions used in this application. When creating or editing a file, check sibling files for the correct structure, approach, and naming.
- Use descriptive names for variables and methods. For example, `isRegisteredForDiscounts`, not `discount()`.
- Check for existing components to reuse before writing a new one.

## Verification Scripts

- Do not create verification scripts or tinker when tests cover that functionality and prove they work. Unit and feature tests are more important.

## Application Structure & Architecture

- Stick to existing directory structure; don't create new base folders without approval.
- Do not change the application's dependencies without approval.

## Frontend Bundling

- If the user doesn't see a frontend change reflected in the UI, it could mean they need to run `npm run build`, `npm run dev`, or `composer run dev`. Ask them.

## Documentation Files

- You must only create documentation files if explicitly requested by the user.

## Replies

- Be concise in your explanations - focus on what's important rather than explaining obvious details.

=== boost rules ===

# Laravel Boost

- Laravel Boost is an MCP server that comes with powerful tools designed specifically for this application. Use them.

## Artisan Commands

- Run Artisan commands directly via the command line (e.g., `php artisan route:list`, `php artisan tinker --execute "..."`).
- Use `php artisan list` to discover available commands and `php artisan [command] --help` to check parameters.

## URLs

- Whenever you share a project URL with the user, you should use the `get-absolute-url` tool to ensure you're using the correct scheme, domain/IP, and port.

## Debugging

- Use the `database-query` tool when you only need to read from the database.
- Use the `database-schema` tool to inspect table structure before writing migrations or models.
- To execute PHP code for debugging, run `php artisan tinker --execute "your code here"` directly.
- To read configuration values, read the config files directly or run `php artisan config:show [key]`.
- To inspect routes, run `php artisan route:list` directly.
- To check environment variables, read the `.env` file directly.

## Reading Browser Logs With the `browser-logs` Tool

- You can read browser logs, errors, and exceptions using the `browser-logs` tool from Boost.
- Only recent browser logs will be useful - ignore old logs.

## Searching Documentation (Critically Important)

- Boost comes with a powerful `search-docs` tool you should use before trying other approaches when working with Laravel or Laravel ecosystem packages. This tool automatically passes a list of installed packages and their versions to the remote Boost API, so it returns only version-specific documentation for the user's circumstance. You should pass an array of packages to filter on if you know you need docs for particular packages.
- Search the documentation before making code changes to ensure we are taking the correct approach.
- Use multiple, broad, simple, topic-based queries at once. For example: `['rate limiting', 'routing rate limiting', 'routing']`. The most relevant results will be returned first.
- Do not add package names to queries; package information is already shared. For example, use `test resource table`, not `filament 4 test resource table`.

### Available Search Syntax

1. Simple Word Searches with auto-stemming - query=authentication - finds 'authenticate' and 'auth'.
2. Multiple Words (AND Logic) - query=rate limit - finds knowledge containing both "rate" AND "limit".
3. Quoted Phrases (Exact Position) - query="infinite scroll" - words must be adjacent and in that order.
4. Mixed Queries - query=middleware "rate limit" - "middleware" AND exact phrase "rate limit".
5. Multiple Queries - queries=["authentication", "middleware"] - ANY of these terms.

=== php rules ===

# PHP

- Always use curly braces for control structures, even for single-line bodies.

## Constructors

- Use PHP 8 constructor property promotion in `__construct()`.
    - `public function __construct(public GitHub $github) { }`
- Do not allow empty `__construct()` methods with zero parameters unless the constructor is private.

## Type Declarations

- Always use explicit return type declarations for methods and functions.
- Use appropriate PHP type hints for method parameters.

<!-- Explicit Return Types and Method Params -->
```php
protected function isAccessible(User $user, ?string $path = null): bool
{
    ...
}
```

## Enums

- Typically, keys in an Enum should be TitleCase. For example: `FavoritePerson`, `BestLake`, `Monthly`.

## Comments

- Prefer PHPDoc blocks over inline comments. Never use comments within the code itself unless the logic is exceptionally complex.

## PHPDoc Blocks

- Add useful array shape type definitions when appropriate.

=== laravel/core rules ===

# Do Things the Laravel Way

- Use `php artisan make:` commands to create new files (i.e. migrations, controllers, models, etc.). You can list available Artisan commands using `php artisan list` and check their parameters with `php artisan [command] --help`.
- If you're creating a generic PHP class, use `php artisan make:class`.
- Pass `--no-interaction` to all Artisan commands to ensure they work without user input. You should also pass the correct `--options` to ensure correct behavior.

## Database

- Always use proper Eloquent relationship methods with return type hints. Prefer relationship methods over raw queries or manual joins.
- Use Eloquent models and relationships before suggesting raw database queries.
- Avoid `DB::`; prefer `Model::query()`. Generate code that leverages Laravel's ORM capabilities rather than bypassing them.
- Generate code that prevents N+1 query problems by using eager loading.
- Use Laravel's query builder for very complex database operations.

### Model Creation

- When creating new models, create useful factories and seeders for them too. Ask the user if they need any other things, using `php artisan make:model --help` to check the available options.

### APIs & Eloquent Resources

- For APIs, default to using Eloquent API Resources and API versioning unless existing API routes do not, then you should follow existing application convention.

## Controllers & Validation

- Always create Form Request classes for validation rather than inline validation in controllers. Include both validation rules and custom error messages.
- Check sibling Form Requests to see if the application uses array or string based validation rules.

## Authentication & Authorization

- Use Laravel's built-in authentication and authorization features (gates, policies, Sanctum, etc.).

## URL Generation

- When generating links to other pages, prefer named routes and the `route()` function.

## Queues

- Use queued jobs for time-consuming operations with the `ShouldQueue` interface.

## Configuration

- Use environment variables only in configuration files - never use the `env()` function directly outside of config files. Always use `config('app.name')`, not `env('APP_NAME')`.

## Testing

- When creating models for tests, use the factories for the models. Check if the factory has custom states that can be used before manually setting up the model.
- Faker: Use methods such as `$this->faker->word()` or `fake()->randomDigit()`. Follow existing conventions whether to use `$this->faker` or `fake()`.
- When creating tests, make use of `php artisan make:test [options] {name}` to create a feature test, and pass `--unit` to create a unit test. Most tests should be feature tests.

## Vite Error

- If you receive an "Illuminate\Foundation\ViteException: Unable to locate file in Vite manifest" error, you can run `npm run build` or ask the user to run `npm run dev` or `composer run dev`.

=== laravel/v12 rules ===

# Laravel 12

- CRITICAL: ALWAYS use `search-docs` tool for version-specific Laravel documentation and updated code examples.
- Since Laravel 11, Laravel has a new streamlined file structure which this project uses.

## Laravel 12 Structure

- In Laravel 12, middleware are no longer registered in `app/Http/Kernel.php`.
- Middleware are configured declaratively in `bootstrap/app.php` using `Application::configure()->withMiddleware()`.
- `bootstrap/app.php` is the file to register middleware, exceptions, and routing files.
- `bootstrap/providers.php` contains application specific service providers.
- The `app\Console\Kernel.php` file no longer exists; use `bootstrap/app.php` or `routes/console.php` for console configuration.
- Console commands in `app/Console/Commands/` are automatically available and do not require manual registration.

## Database

- When modifying a column, the migration must include all of the attributes that were previously defined on the column. Otherwise, they will be dropped and lost.
- Laravel 12 allows limiting eagerly loaded records natively, without external packages: `$query->latest()->limit(10);`.

### Models

- Casts can and likely should be set in a `casts()` method on a model rather than the `$casts` property. Follow existing conventions from other models.

=== pint/core rules ===

# Laravel Pint Code Formatter

- If you have modified any PHP files, you must run `vendor/bin/pint --dirty --format agent` before finalizing changes to ensure your code matches the project's expected style.
- Do not run `vendor/bin/pint --test --format agent`, simply run `vendor/bin/pint --format agent` to fix any formatting issues.

=== phpunit/core rules ===

# PHPUnit

- This application uses PHPUnit for testing. All tests must be written as PHPUnit classes. Use `php artisan make:test --phpunit {name}` to create a new test.
- If you see a test using "Pest", convert it to PHPUnit.
- Every time a test has been updated, run that singular test.
- When the tests relating to your feature are passing, ask the user if they would like to also run the entire test suite to make sure everything is still passing.
- Tests should cover all happy paths, failure paths, and edge cases.
- You must not remove any tests or test files from the tests directory without approval. These are not temporary or helper files; these are core to the application.

## Running Tests

- Run the minimal number of tests, using an appropriate filter, before finalizing.
- To run all tests: `php artisan test --compact`.
- To run all tests in a file: `php artisan test --compact tests/Feature/ExampleTest.php`.
- To filter on a particular test name: `php artisan test --compact --filter=testName` (recommended after making a change to a related file).

=== tailwindcss/core rules ===

# Tailwind CSS

- Always use existing Tailwind conventions; check project patterns before adding new ones.
- IMPORTANT: Always use `search-docs` tool for version-specific Tailwind CSS documentation and updated code examples. Never rely on training data.
- IMPORTANT: Activate `tailwindcss-development` every time you're working with a Tailwind CSS or styling-related task.

</laravel-boost-guidelines>
