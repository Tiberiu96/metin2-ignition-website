# Phase 2 — Public Site

Complete all tasks in order. Mark with [x] when done.
Phase 1 must be fully complete before starting here.

---

## 1. Layout Principal — `resources/views/layouts/app.blade.php`

Dark fantasy theme inspired by Metin2 aesthetic.
- Navbar: logo, links (Home, Ranking, News, Download), login/register sau account/logout
- Footer: server name, links
- Font recomandat: Google Fonts (ex. Cinzel pentru titluri)
- Culori: dark background (#0a0a0f), gold accents (#c9a84c), text alb/gri

- [ ] `resources/views/layouts/app.blade.php` creat
- [ ] Navbar cu auth state (guest vs logged in)
- [ ] Footer

---

## 2. Home Page — `/`

Route: `GET /` → `HomeController@index`

Sectiuni:
- Hero: titlu server, tagline, butoane "Download" si "Register"
- Server info: rates (EXP, DROP, YANG), versiune (2014 Classic)
- Preview screenshots sau artwork

- [ ] `app/Http/Controllers/HomeController.php`
- [ ] `resources/views/home.blade.php`
- [ ] Route in `routes/web.php`

---

## 3. Auth — Login & Register

### Login — `/login`
- Guard: `metin2`
- Verifica `login` + `MysqlPasswordHasher->check($password, $account->password)` contra `account.accounts`
- Redirect dupa login: `/account`
- Rate limiting: max 5 incercari / minut per IP

- [ ] `app/Http/Controllers/Auth/LoginController.php`
- [ ] `app/Http/Requests/Auth/LoginRequest.php`
- [ ] `resources/views/auth/login.blade.php`

### Register — `/register`
- Scrie direct in `account.accounts`
- Campuri: `login` (unique), `password` (MySQL PASSWORD() via MysqlPasswordHasher), `email` (unique), `social_id` (generat automat)
- Status setat la `OK`, `create_time` = now()
- Rate limiting: max 3 incercari / minut per IP

- [ ] `app/Http/Controllers/Auth/RegisterController.php`
- [ ] `app/Http/Requests/Auth/RegisterRequest.php`
- [ ] `resources/views/auth/register.blade.php`

### Logout — `POST /logout`
- [ ] Logout route si controller method

---

## 4. Ranking Page — `/ranking`

Route: `GET /ranking` → `RankingController@index`

Afiseaza top 50 playeri din `player.player` sortati dupa level DESC, exp DESC.
Coloane: Rank, Nume, Clasa, Level, Empire, Guild (join cu player.guild).

Clase afisate ca text:
- 0 = Warrior, 1 = Assassin, 2 = Sura, 3 = Shaman

Empire afisate ca text + culoare:
- 1 = Red (Chunjo), 2 = Yellow (Jinno), 3 = Blue (Shinsoo)

- [ ] `app/Http/Controllers/RankingController.php`
- [ ] `resources/views/ranking.blade.php`
- [ ] Route in `routes/web.php`

---

## 5. News — `/news` si `/news/{news}`

### Index — `/news`
Afiseaza articole publicate (`is_published = true`) din `metin2_web.news`, sortate `published_at DESC`.
Paginare: 10 per pagina.

### Show — `/news/{news}`
Articol individual. Foloseste `slug` pentru URL.

- [ ] `app/Http/Controllers/NewsController.php`
- [ ] `resources/views/news/index.blade.php`
- [ ] `resources/views/news/show.blade.php`
- [ ] Routes in `routes/web.php`

---

## 6. Download Page — `/download`

Pagina simpla cu:
- Buton/link download client
- Instructiuni instalare (pasii din `serverinfo.py`)
- System requirements

- [ ] `app/Http/Controllers/DownloadController.php`
- [ ] `resources/views/download.blade.php`
- [ ] Route in `routes/web.php`

---

## 7. Account Page — `/account` (protected)

Middleware: `auth:metin2`

Afiseaza datele contului logat:
- Username, email, data creare cont
- Lista personaje (din player.player where account_id = auth id)
  - Nume, clasa, level, empire, last_play

- [ ] `app/Http/Controllers/AccountController.php`
- [ ] `resources/views/account/index.blade.php`
- [ ] Route protejata cu `middleware('auth:metin2')`

---

## Verification

```bash
php artisan route:list
php artisan test --compact
```

Verifica manual in browser:
- [ ] `/` se incarca
- [ ] `/login` functioneaza cu un cont din account.accounts
- [ ] `/register` creeaza cont nou in account.accounts
- [ ] `/ranking` afiseaza playerii
- [ ] `/news` afiseaza stiri
- [ ] `/download` se incarca
- [ ] `/account` redirecteaza la login daca nu esti autentificat
- [ ] `/account` afiseaza datele daca esti autentificat
