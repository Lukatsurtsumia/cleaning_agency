# Cleaning Agency

A Laravel 13 marketing/lead-gen site for a cleaning company. Blade + Alpine.js + Tailwind CSS (no SPA framework). Core flows: landing page with services and an instant quote calculator, a project gallery, and a contact form — all backed by real database records and email notifications.

## Stack

- Laravel 13, Blade, Alpine.js, Tailwind CSS
- Laravel Breeze (auth scaffolding)
- MySQL via Laravel Sail (Docker)
- Mailpit for local email testing
- `barryvdh/laravel-dompdf` for downloadable quote PDFs
- `intervention/image` for gallery image resizing
- Pest for tests, Pint for code style

## Running locally (Docker / Sail)

```bash
./vendor/bin/sail up -d          # start app, mysql, mailpit
./vendor/bin/sail artisan migrate --seed
./vendor/bin/sail npm install
./vendor/bin/sail npm run dev    # Vite with hot reload, or `npm run build` for production assets
```

The app is served at the port set by `APP_PORT` in `.env` (default here: `http://localhost:8110`).

- Mailpit UI (catches all outgoing email): `http://localhost:8135`
- MySQL is exposed on `FORWARD_DB_PORT` if you want to connect a GUI client.

An admin user is seeded from the `ADMIN_EMAIL` / `ADMIN_PASSWORD` env vars (defaults: `admin@example.com` / `password`) — log in at `/login` to reach the dashboard, manage gallery projects, and view booking requests.

Queued mail (booking/contact notifications) is processed by the `queue:listen` process — already included if you run:

```bash
./vendor/bin/sail composer run dev
```

which runs `serve`, `queue:listen`, `pail` (log tailing), and `vite` together.

## Key routes

| Route | Purpose |
| --- | --- |
| `GET /` | Landing page: hero, services, instant quote calculator, gallery preview, testimonials, contact form |
| `GET /gallery` | Full project gallery, filterable by category |
| `POST /booking` | Submits a quote request — computes price server-side, emails customer + agency, stores to DB |
| `GET /booking/{booking}/pdf` | Downloadable quote PDF (signed URL for guests, or any logged-in admin) |
| `POST /contact` | Contact form — emails customer + agency, stores to DB |
| `/dashboard`, `/admin/bookings`, `/gallery-admin/*` | Auth-gated admin area (Breeze "logged in or not", no roles) |

## Pricing logic

Quote estimates are computed in `app/Services/CleaningQuoteCalculator.php` from a base price per service type, a per-bedroom/bathroom charge, a property-type multiplier, and flat add-ons for extras. The Alpine.js calculator on the landing page mirrors this formula for a live preview, but the price that's actually saved and emailed is always computed server-side in `BookingController::store`.

## Tests

```bash
./vendor/bin/sail artisan test
./vendor/bin/sail pint          # code style
```
