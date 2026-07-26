# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project

Laravel 13 marketing/lead-gen site for **Azur Clean Tinati**, a professional cleaning company in Nice (Côte d'Azur) run by Tina Babayan. Blade + Alpine.js + Tailwind CSS (no SPA framework, no API layer). Core flows: a one-page landing site with a hero carousel, a project gallery, and a contact form (see below — there is no dedicated booking flow anymore, every "Réserver"/"Book" CTA just scrolls to the contact form), all backed by real database records and email notifications where a form is still involved. Auth scaffolding is Laravel Breeze (Blade stack).

The site is **bilingual, French-first**. Company facts (address, phones, pricing tiers, booking window) live in `config/azurclean.php`; all user-facing copy lives in `lang/fr/site.php` and `lang/en/site.php`. Do not hardcode either into views.

## Running locally (Docker / Sail)

```bash
./vendor/bin/sail up -d          # start app, mysql, mailpit
./vendor/bin/sail artisan migrate --seed
./vendor/bin/sail npm install
./vendor/bin/sail npm run dev    # Vite with hot reload, or `npm run build` for production
```

- App: `http://localhost:8110` (port set by `APP_PORT` in `.env`)
- Mailpit UI (catches all outgoing email): `http://localhost:8135`
- An admin user is seeded from `ADMIN_EMAIL` / `ADMIN_PASSWORD` (defaults: `admin@example.com` / `password`) — log in at `/login` to reach the dashboard, manage gallery projects, and view booking requests.

To run app server + queue worker + log tailing + Vite together:

```bash
./vendor/bin/sail composer run dev
```

Queued mail (booking/contact notifications) requires the `queue:listen` process — included in the command above, or run separately with `./vendor/bin/sail artisan queue:listen`.

## Tests & linting

```bash
./vendor/bin/sail artisan test                             # full suite (Pest)
./vendor/bin/sail artisan test --filter=AvailabilityTest    # single file/test
./vendor/bin/sail pint                                      # code style (Laravel Pint)
```

Tests are Pest, living under `tests/Feature` (`RefreshDatabase` applied globally via `tests/Pest.php`) and `tests/Unit`. Feature tests hit routes directly (e.g. `$this->post(route('booking.store'), [...])`) rather than mocking controllers. The suite needs MySQL, so run it through Sail — the PHP CLI in WSL has no sqlite driver.

## Architecture

**Localisation:** `App\Http\Middleware\SetLocale` (appended to the `web` group in `bootstrap/app.php`) reads a `locale` session key and falls back to French. Browser `Accept-Language` is deliberately **ignored** — the clientele is local, so everyone lands on French until they use the switcher (`GET /locale/{locale}` → `LocaleController`). Supported locales are `SetLocale::SUPPORTED`.

**Public flow, one page:** `MainController::index` pulls service copy straight out of the language files (`__('site.services.items')`), merges `config('azurclean.pricing')` with its translated tier names, and loads the latest 4 galleries (the homepage preview is always exactly 4 cards — see Gallery flow below). It renders `resources/views/page/index.blade.php`, composed of section partials in `resources/views/website/` (`hero`, `services`, `pricing`, `gallery-preview`, `about`, `contact`, `location`), passing `$services` into `hero` too since the carousel reuses that data (see Hero carousel below). There used to be a `trust` strip (icon/photo + title/body cards) between `hero` and `services`; it was removed at the client's request along with `site.trust` and `MainController`'s `$trust` — don't reintroduce it without asking. **Map and contact are separate sections** (the client asked for this): `contact` is the reach-us panel + form only; `location` (`id="localisation"`) is its own section that `@include`s `website.map` under its own heading. `map.blade.php` is a dark info panel beside a Google embed, mirroring the location block on the client's other site (`~/projects/pcn_boxing`). It uses Google's classic `maps.google.com/maps?q=<lat>,<lng>&output=embed` endpoint, which needs no API key or billing account. It pins **coordinates**, not the business name: querying by name made Google run a search and drop pins on rival cleaners, since the company is not on Google yet. Opening hours are static copy in `site.contact.hours_value`.

**Gallery photos & flow:** real client photos come from `Gallery::cover_image`/`images`; until those exist, each card falls back to a per-category stock set in `public/images/gallery/` — `<category>.jpg` (cover) plus `<category>-2.jpg`/`-3.jpg` (extra shots). The homepage `gallery-preview` shows one image-forward card per category (no "view all" link — the card *is* the entry point). Clicking a card opens `gallery.show`, which displays that category's whole photo set (lead image + grid) and a single "Retour à l'accueil" button back to `welcome#realisations`. `MainController::index` curates the preview to one gallery per service family via `unique('category')` so the four cards never repeat a category. Keep gallery images at ~1400×1050 (4:3), optimized JPEG.

**Service taxonomy:** the four families from the company presentation — `hotelier`, `immeubles`, `bureaux`, `specifiques`. These keys are shared by `CleaningQuoteCalculator::SERVICE_BASE_PRICES`, `GalleryController::CATEGORIES`, and the `site.services.items` language entries, so gallery categories can be labelled by looking the key up in the service list. Change all of them together.

**There is no booking section on the public page at all anymore.** It went through two iterations — first a full reservation form (dates, service/property type, address, notes), then a single-button `mailto:` CTA (`resources/views/website/booking.blade.php`, section `id="reserver"`) — before the client asked to remove it entirely. Every "Réserver"/"Book" button on the site (nav, hero, services cards, pricing cards, footer) now points at `#contact` instead of `#reserver`, landing on the contact form (`ContactController::send`, see below) rather than any booking-specific flow. `site.booking.*` in the lang files is now just `property_types` (kept only because the retained email templates below still read it) — the rest of that key (eyebrow/title/lead/mail_cta/mail_subject/mail_body) was removed as dead weight along with `booking.blade.php` itself.

**The old booking backend still exists but is now fully orphaned from the public site.** `POST /booking` → `BookingController::store`, `CleaningQuoteCalculator`, the `Booking` model/migrations, `App\Mail\BookingReceived`/`BookingConfirmation`, the quote PDF (`GET /booking/{booking}/pdf`), the admin bookings dashboard (`/admin/bookings/*`), and the `.ics` agenda feed are all untouched and still fully functional — they just no longer receive new entries from the public page, since nothing links to `route('booking.store')` anymore (nothing ever did even in the `mailto:` iteration; that was already true before this removal). Feature tests (`BookingTest`, `BookingRangeTest`) still post to the route directly and pass. If the client ever wants bookings to populate the admin dashboard/PDF/`.ics` feed again, that means either building a form that posts to `booking.store`, or building a new way to create `Booking` rows — ask before doing either.

There is **no availability/capacity apparatus** — it was removed once the client chose phone management. The `availability_rules` and `blocked_dates` tables, their migrations, and `Booking::occupiedDates()` have all been dropped. Do not reintroduce availability gating without the client asking.

**Agenda feed:** `GET /agenda/{token}.ics` (`AgendaController`) publishes non-cancelled bookings as all-day VEVENTs spanning each job's full run, for the agency to subscribe to. Guarded by `AGENDA_FEED_TOKEN` via `hash_equals`; 404s when blank. The subscribe URL is surfaced on the admin bookings index (`BookingController::index`). Since the public form is gone, this feed only reflects whatever `Booking` rows already exist (seeded/test data or manual creation) until someone rebuilds a way to create them from the retained `BookingController::store`.

**Pricing is advertised, not calculated.** `config('azurclean.pricing')` drives three "à partir de" cards; a `null` `from` renders as "sur devis". `CleaningQuoteCalculator` survives only to stamp an internal starting figure on each booking — it is not shown as a promise to the customer, and (per the booking change above) is now only ever invoked via the orphaned `BookingController::store`, not from the public page. **The rates in the config are placeholders and must be replaced with Tina's real ones before launch.**

**Gallery flow:** `Gallery` has many `GalleryImage`. Uploaded images are resized/re-encoded via `intervention/image` (`GalleryController::storeResizedImage` — scales down to 1600px wide, JPEG quality 82, stored under `storage/app/public/gallery/{galleryId}/{uuid}.jpg`) rather than stored as-is. Categories are the fixed const array above, not a DB table. The homepage preview (`gallery-preview.blade.php`) always shows exactly 4 cards; a gallery without a `cover_image` (true of all seeded data — no real jobs have been photographed yet) falls back to a category-keyed icon+gradient placeholder rather than a generic tile, so the four cards read as distinct. The full `/gallery` page is untouched by this and still shows every gallery with the old generic fallback.

**Auth model:** Breeze's `auth` + `verified` middleware gates `/dashboard`, `/profile`, `/gallery-admin/*`, and `/admin/bookings/*`. There are no roles/permissions — any authenticated user is treated as an admin.

**Contact section** (`resources/views/website/contact.blade.php`) pairs a call-to-action panel (phone + the two people + the map) with a real message form. `POST /contact` → `ContactController::send` validates `name`/`email`/`message` (`phone`/`subject` optional), checks a hidden honeypot field (`website`) — a filled value silently no-ops with the normal success message instead of validating or emailing, since only bots fill it — then stores a `ContactMessage` and sends two queued emails: `ContactReceived` to the agency and `ContactAutoReply` back to the customer.

**Mail** goes out on booking and contact: `BookingReceived`/`BookingConfirmation` for bookings, `ContactReceived`/`ContactAutoReply` for the contact form. All four are French, `ShouldQueue`, so production needs a `queue:work` worker (see `DEPLOY.md`). The markdown-table date cell in the booking mail is a single-line inline expression, not the `<x-booking-dates>` component — a multi-line component breaks the Markdown table row.

**Mail config:** `config('mail.agency_address')` (env `AGENCY_EMAIL`, falls back to `MAIL_FROM_ADDRESS`) is where agency-facing notifications (`BookingReceived`, `ContactReceived`) are sent — use this instead of hardcoding an address.

**Routes:** all defined in `routes/web.php`; auth-only routes are grouped under `Route::middleware('auth')`. No API routes exist.

## Front-end conventions

- Brand palette is `azur-*` (teal, sampled from the visit card) and `sand-*` (the card stock) in `tailwind.config.js`. Headings use `font-display` (Cormorant Garamond); body text uses Figtree.
- The logo is Tina's own painted double-crested wave from the visit card, keyed off the paper into `public/images/logo-wave.webp`. `<x-brand.mark tone="light">` swaps in `logo-wave-light.webp`, the same artwork re-inked pale — the original dark teal is invisible on dark panels, so always pass `tone="light"` over the footer or any azur-800/900 surface. `<x-brand.logo>` stacks the mark above the wordmark because the artwork is wide (2.85:1); pass `:show-tagline="false"` where vertical space is tight, as the nav does.
- Shared button/section styles are `@layer components` classes in `resources/css/app.css` (`.btn-primary`, `.btn-ghost`, `.section-title`, `.card`, `.wave-band`).
- **The hero is the one deliberately animated thing on the site; nothing else is.** The client's original ask was a static wave photograph with no motion (the keyframes were removed from `tailwind.config.js` entirely), but the client later asked specifically for an auto-advancing hero carousel — that's `Alpine.data('slideshow', ...)` in `resources/js/app.js` plus the markup in `hero.blade.php`. It autoplays (6.5s interval), skips autoplay entirely under `prefers-reduced-motion: reduce`, pauses on hover/focus, and supports touch swipe. Elsewhere on the site the no-ambient-animation rule still holds — don't add auto-playing motion to any other section without asking.
- **Hero carousel:** `hero.blade.php` builds a `$slides` array in a `@php` block — slide 0 is the real wave photo (`public/images/hero-wave.webp`) with the general pitch (`site.hero.*`), slides 1–4 reuse `$services` (passed in from `page/index.blade.php`) so each service family gets a slide with its own photo at `public/images/hero/{service_key}.jpg` (`hotelier.jpg`, `immeubles.jpg`, `bureaux.jpg`, `specifiques.jpg`). Every slide is a real photo now — an earlier version used a flat brand-gradient background with a large faint icon watermark instead of photos for slides 1–4; the client asked for real photos there too, so that gradient/icon branch was removed. The five hero photos are free-license stock photos (Pexels — no attribution required), not photos of Tina's actual work; swap them for real jobsite photos whenever the client provides them. This reuse of `$services` means the carousel needs zero dedicated lang keys beyond `site.hero.prev`/`next`/`goto` (nav labels for the controls) — adding a slide is just adding an item to `$services` or extending the array in the view. All slides share one skeleton (eyebrow/title/lead/CTA row) so switching slides doesn't jank; a flat wash plus a directional scrim (bottom-heavy on mobile/tablet, side-heavy on `lg`) keeps text at contrast regardless of how dark or bright a given slide's photo is. Slides crossfade via Tailwind `transition-opacity` classes bound with `:class`, not the `x-transition` directive (see the mobile-drawer note below for why that's avoided).
- The hero background photo itself is `public/images/hero-wave.webp`, cropped from the client's reference mockup: the baked-in headline, buttons and scroll icon were painted out, so the file is water only. Regenerate it from the source mockup rather than editing it in place.
- `<x-waves>` is a small static decorative swell, reused on dark panels for texture — currently the pricing section and the map's info panel. It is motionless by design (the carousel is the site's one moving element).
- Forms use `<x-form.field>`, which handles labels, old input, select/textarea variants and inline error text.
- **Do not put `x-transition` on the mobile nav drawer.** Both the class-based and modifier forms desynced `x-show` and left the drawer stuck open; a plain toggle is what works.
- **The phone number is deliberately not repeated everywhere.** `site-nav.blade.php` shows a click-to-call icon only (no visible digits) in both the desktop bar and the mobile drawer — the full number is printed only in the footer and the contact section's call card. Keep new call-to-action buttons icon/label-only (reuse `site.common.call`) rather than printing the number again.
