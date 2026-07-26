# Deploying Azur Clean Tinati

A standard Laravel 13 app: Blade + Alpine + Tailwind, MySQL. The repo ships a
production `Dockerfile` (multi-stage: composer deps + built Vite assets +
`serversideup/php` nginx/php-fpm runtime on port **8080**), so Coolify builds
everything — no Node or Composer needed on the server.

## Deploy to Coolify (Hetzner) — the short path

1. **Push the code to a Git repo Coolify can read** (GitHub/GitLab, or Coolify's
   built-in Git). This project has no remote yet:

   ```bash
   git remote add origin <your-repo-url>
   git push -u origin main
   ```

2. In Coolify: **+ New → Resource → your repo**, branch `main`.
   - **Build pack: Dockerfile** (the repo's `Dockerfile` is auto-detected).
   - **Port: 8080**. Health check path: `/up`.

3. **Add a MySQL database** in Coolify (or point at an existing one) and note its
   host/user/password/db.

4. **Environment variables** (Coolify → the app → Environment). At minimum:

   ```env
   APP_NAME="Azur Clean Tinati"
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://your-domain.fr
   APP_KEY=            # generate: php artisan key:generate --show  (locally) and paste
   APP_LOCALE=fr
   APP_FALLBACK_LOCALE=en

   DB_CONNECTION=mysql
   DB_HOST=...  DB_PORT=3306  DB_DATABASE=...  DB_USERNAME=...  DB_PASSWORD=...

   SESSION_DRIVER=database
   CACHE_STORE=database
   QUEUE_CONNECTION=sync        # sync = no separate worker needed (see Mail below)

   # Boot-time tasks, run automatically by the serversideup image:
   AUTORUN_ENABLED=true
   AUTORUN_LARAVEL_MIGRATION=true
   AUTORUN_LARAVEL_STORAGE_LINK=true
   AUTORUN_LARAVEL_OPTIMIZE=true

   # Mail — a real SMTP provider (see section 5)
   MAIL_MAILER=smtp
   MAIL_HOST=...  MAIL_PORT=587  MAIL_USERNAME=...  MAIL_PASSWORD=...  MAIL_SCHEME=tls
   MAIL_FROM_ADDRESS="contact@your-domain.fr"
   AGENCY_EMAIL="tinababayan99@gmail.com"

   AGENDA_FEED_TOKEN=          # long random string
   ```

5. **Deploy.** On boot the image auto-runs `migrate --force`, `storage:link` and
   `optimize` (config/route/view cache) because of the `AUTORUN_*` flags — no
   manual post-deploy commands needed.

6. **Domain + HTTPS**: set your domain on the resource; Coolify issues Let's
   Encrypt automatically.

### Notes for Coolify

- **`APP_KEY`** must be set or the app won't boot. Generate it once locally
  (`php artisan key:generate --show`) and paste the `base64:...` value.
- **`QUEUE_CONNECTION=sync`** keeps it to a single container: booking/contact
  emails send during the request. Fine for this traffic. To use a real queue,
  set `QUEUE_CONNECTION=database` and add a second Coolify resource running
  `php artisan queue:work` off the same image/repo.
- **Gallery uploads** (admin-uploaded photos) land in `storage/app/public`. The
  homepage/gallery use bundled `public/images/**` which are in the image, so
  nothing extra is needed unless Tina uploads real job photos — then add a
  Coolify **persistent volume** mounted at `/var/www/html/storage/app/public`.
- The seeded demo galleries are inserted by `AUTORUN_LARAVEL_MIGRATION` only if
  you also seed; migrations alone don't seed. To load the demo cards once, run
  `php artisan db:seed --class=GallerySeeder --force` via Coolify's terminal, or
  just create real galleries at `/login`.

---

## Manual / non-Coolify deploy

The rest of this file covers a plain server deploy without Docker.

## 1. Server requirements

- PHP 8.2+ with the usual Laravel extensions (`pdo_mysql`, `mbstring`, `gd`
  for image resizing, `intl`, `zip`, `bcmath`)
- MySQL 8 (or MariaDB 10.6+)
- Composer
- A process supervisor for the queue worker (systemd, Supervisor, or your
  host's equivalent)

## 2. First deploy

```bash
# Install PHP deps only (no dev packages on the server)
composer install --no-dev --optimize-autoloader

# Environment
cp .env.example .env          # then edit it — see section 3
php artisan key:generate

# Database
php artisan migrate --force

# Public storage symlink (gallery uploads are served from here)
php artisan storage:link
```

Build the front-end assets **once** (on your machine or in CI, wherever Node is
available) and deploy the resulting `public/build` directory:

```bash
npm ci
npm run build
```

## 3. Configure `.env`

Copy from `.env.example` and set, at minimum:

- `APP_KEY` — `php artisan key:generate` fills this
- `APP_URL` — the real HTTPS domain
- `APP_ENV=production`, `APP_DEBUG=false`
- `DB_*` — database credentials
- `MAIL_*` — a real SMTP provider (see section 5)
- `AGENCY_EMAIL` — where booking requests are delivered
- `AGENDA_FEED_TOKEN` — a long random string for the private calendar feed

## 4. Cache config for production

Run after every deploy that changes code or `.env`:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

If you change `.env` later, re-run `php artisan config:cache` (cached config
ignores `.env` until you do).

## 5. Mail

Booking notifications are **queued** (`ShouldQueue`), so a worker must be
running or no email is sent:

```bash
php artisan queue:work --tries=3 --max-time=3600
```

Run it under a supervisor so it restarts automatically. After each deploy:

```bash
php artisan queue:restart
```

Two messages go out per booking: a notification to `AGENCY_EMAIL` and a
confirmation to the customer. Test the pipeline before launch:

```bash
php artisan tinker --execute="Mail::raw('test', fn (\$m) => \$m->to('you@example.com')->subject('SMTP test'));"
```

`MAIL_FROM_ADDRESS` must be on a domain you control, with SPF/DKIM configured at
the provider, or messages will be marked as spam.

## 6. Seed demo gallery (optional)

The gallery ships empty. `GallerySeeder` inserts placeholder projects for a
first look; skip it in production and add real jobs through `/login` →
Gallery instead:

```bash
php artisan db:seed --class=GallerySeeder --force   # optional placeholders
```

## 7. Create the admin login

```bash
php artisan tinker
>>> \App\Models\User::factory()->create(['name' => 'Tina', 'email' => 'tinababayan99@gmail.com', 'password' => bcrypt('CHANGE_ME')]);
```

Log in at `/login` to view booking requests and manage the gallery. The private
`.ics` agenda link is shown on the bookings page.

## Before-launch checklist

- [ ] Real hourly rates in `config/azurclean.php` (the `25`/`28` are placeholders)
- [ ] Verify the map pin sits on the right door (`config/azurclean.php` lat/lng)
- [ ] Real gallery photos, or remove the demo seed
- [ ] SMTP credentials tested, `AGENCY_EMAIL` set to a monitored inbox
- [ ] HTTPS enabled, `APP_DEBUG=false`
- [ ] A cookie-consent gate on the Google Maps embed (GDPR/CNIL) if required
