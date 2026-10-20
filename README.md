# Web System – Secure PHP Login & Tools Dashboard

A small web application written in **plain PHP and MySQL** (no framework): users can register, log in and use a dashboard of small tools. It started as my first project for learning Git and PHP, and I've since revisited it to apply the security and deployment practices I've learned. It's a learning project, but it's built the way I'd build production code: secure defaults, configuration via environment variables, and a one-command Docker setup.

## Features

- **User registration** with server-side validation and friendly error messages (e.g. "username already taken")
- **Login / logout** with hardened PHP sessions
- **Dashboard** (Bootstrap 5) with a card for each tool, available only to logged-in users
- **Tools**
  - *Calculator*: a button-based calculator in JavaScript, using a small recursive-descent expression parser instead of `eval()`
  - *Basic Calculator*: a form-based calculator processed in PHP, with input validation
- **Docker setup**: `docker compose up` starts the app and a MySQL database with the schema already imported

## Tech Stack

| Layer      | Technology                                 |
|------------|--------------------------------------------|
| Backend    | PHP 8.3 (plain PHP, PDO)                   |
| Database   | MySQL 8.4 (also works with MariaDB)        |
| Frontend   | HTML, CSS, Bootstrap 5.3, vanilla JavaScript |
| Web server | Apache (`php:8.3-apache` image)            |
| Tooling    | Docker, Docker Compose, Git                |

## Security Measures

| Threat | Mitigation |
|--------|------------|
| SQL injection | All queries use **PDO prepared statements** with native (non-emulated) prepares |
| Password theft | Passwords are hashed with **`password_hash()`** (bcrypt) and checked with `password_verify()`. Outdated hashes are re-hashed automatically on login |
| Cross-site scripting (XSS) | Raw data is stored in the DB and **escaped with `htmlspecialchars()` on output** (`e()` helper). Forms post to `action=""` instead of echoing `PHP_SELF` |
| Cross-site request forgery (CSRF) | Login, register and logout forms carry a per-session **CSRF token** checked with `hash_equals()` |
| Session fixation / hijacking | `session_regenerate_id(true)` after login. Session cookies are `HttpOnly`, `SameSite=Lax` and `Secure` over HTTPS. Strict mode and cookie-only sessions are enabled |
| Username enumeration | One generic "invalid username or password" message, with a password hash computed for unknown users too, so response times are similar |
| Duplicate accounts | `UNIQUE` constraint on the username column; the duplicate-key error is turned into a friendly message |
| Information leakage | Database/PHP errors are **logged, never shown** to the user. Production `php.ini` is used, and PHP/Apache versions are hidden |
| Clickjacking & MIME sniffing | `X-Frame-Options: DENY`, `X-Content-Type-Options: nosniff`, `Referrer-Policy` headers |
| Exposed files | `.htaccess` disables directory listing and blocks dotfiles (`.git`, `.env`), config files and the `helpers/` folder (SQL schema) |
| Secrets in Git | Credentials come from environment variables. `config.php` and `.env` are git-ignored, and only `*.example` templates are committed |

## Getting Started (Docker)

**Requirements:** [Docker Desktop](https://www.docker.com/products/docker-desktop/) (or Docker Engine with the Compose plugin).

```bash
git clone https://github.com/KriMaDev/web_system.git
cd web_system

# Optional: set your own passwords/port (defaults work for local testing)
cp .env.example .env

docker compose up --build
```

Then open **http://localhost:8080**, create an account with **Register** and log in.

- On the first start, MySQL imports [`helpers/database/krima_rep.sql`](helpers/database/krima_rep.sql) automatically.
- Logs (including PHP errors): `docker compose logs -f app`
- Stop: `docker compose down`. Reset the database: `docker compose down -v`.
- After changing code, rebuild with `docker compose up --build`.

### Running without Docker (XAMPP / WAMP / MAMP)

1. Copy the project into your web root (e.g. `htdocs/web_system`).
2. Create a database (e.g. `krima_rep`) and import `helpers/database/krima_rep.sql`.
3. Copy `config.example.php` to `config.php` and adjust the DB settings (or set the `DB_*` environment variables).
4. Open `http://localhost/web_system/`.

## Project Structure

```
web_system/
├── index.php                # Entry point → dashboard / login
├── km_login.php             # Login form + authentication
├── km_register.php          # Registration form + validation
├── km_dashboard.php         # Protected dashboard + logout
├── km_conn.php              # PDO connection (errors are logged)
├── km_func.php              # Helpers: config, session, CSRF, escaping, text utils
├── config.example.php       # Config template (reads environment variables)
├── styles.css
├── test_scripts/
│   ├── calculator.html      # JS calculator (safe expression parser)
│   └── basic_calculator.php # PHP form calculator
├── helpers/database/
│   └── krima_rep.sql        # Database schema
├── Dockerfile
├── docker-compose.yml
└── .env.example
```

## Screenshots

> _Screenshots coming soon._

| Login | Register | Dashboard |
|-------|----------|-----------|
| _placeholder_ `docs/screenshots/login.png` | _placeholder_ `docs/screenshots/register.png` | _placeholder_ `docs/screenshots/dashboard.png` |

<!--
Once the images are added, replace the table above with:
| ![Login](docs/screenshots/login.png) | ![Register](docs/screenshots/register.png) | ![Dashboard](docs/screenshots/dashboard.png) |
-->

## What I'd Add Next

- **Login rate limiting / account lockout** against brute-force attempts
- **Automated tests** (PHPUnit for the helpers, plus browser tests for the auth flow) running in **GitHub Actions**
- **Content Security Policy**: move the inline scripts and styles into files so a strict CSP can be used
- **Static analysis & code style** with PHPStan and PHP_CodeSniffer (PSR-12)
- **Password reset** via email, and an account settings page (change alias/password)
- **More tools** on the dashboard (e.g. the Greek-to-Latin slug generator already in `km_func.php`)
- A simple **front controller and autoloading** to replace the `km_*.php` page scripts as the project grows

## Upgrading an Existing Database

If you imported an older version of the schema, add the unique constraint manually:

```sql
ALTER TABLE km_users ADD UNIQUE KEY uniq_km_users_user (user);
```

Older versions of the app stored HTML-escaped usernames (e.g. `&amp;`). Users whose names contain special characters may need to re-register.

---

Created by **KriMaDev**, started December 2023 as a Git/PHP learning project and refactored in 2026.
