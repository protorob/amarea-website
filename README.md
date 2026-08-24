# amarea — website

Marketing site for A'Marea, a Mediterranean co-living business in Fontane
Bianche/Siracusa with two properties — **Beach House** (8 bookable rooms)
and **City Apartments** (two independent apartments, Zagara and Ibisco).
Built on [Kirby CMS](https://getkirby.com) (Plainkit) + Tailwind CSS v4,
managed with Composer. Scaffolded from
[protorob/kirbycms-tailwind-base](https://github.com/protorob/kirbycms-tailwind-base).

**v1 ships waitlist-only.** The booking flow itself is not part of this
repo — it will eventually be embedded from
[ballaraw-booking-engine](https://github.com/protorob/ballaraw-booking-engine),
a separately deployed widget served from its own Coolify service. Until
then, every "Start Booking" CTA opens the lead-capture form instead (see
"Lead-capture form" below).

Site is multi-language: English (default), Italian, Spanish, German,
French. See "Languages" below.

## Requirements

- PHP 8.2+ with extensions: `mbstring`, `xml`, `gd`, `curl`, `zip`, `intl`
- [Composer](https://getcomposer.org)
- [Bun](https://bun.sh)

## Setup

```bash
git clone <this-repo-url>
cd amarea-website
composer install
bun install
```

## Run locally

In two separate terminals:

```bash
# Terminal 1 — PHP dev server
composer start

# Terminal 2 — CSS/JS watch mode
bun run dev
```

Then open `http://localhost:8000`. The Kirby Panel is at
`http://localhost:8000/panel` (prompts to create an admin account on first
visit).

## Frontend build

[Tailwind CSS v4](https://tailwindcss.com) via the `@tailwindcss/vite`
plugin. Source files live in `src/` and compile to `assets/` (gitignored,
rebuilt on every deploy).

```bash
bun run dev     # watch mode, rebuilds on changes to src/, templates, snippets
bun run build   # production build → assets/css/ and assets/js/
```

- `src/main.css` — Tailwind entry point, `@theme` customizations, custom CSS
- `src/main.js` — mobile menu, header scroll behavior (transparent → sticky/solid past the hero), lead-capture modal + form submit
- `site/snippets/header.php` / `site/snippets/footer.php` — shared page chrome
- `site/templates/` — one PHP template per blueprint (home, about, locations, property, unit, community, workation, faq, contact)

## Languages

Defined in `site/languages/` — `en` (default, unprefixed URLs), `it`,
`es`, `de`, `fr` (prefixed, e.g. `/it/locations`). UI strings (nav,
buttons, form labels/errors) are translated in all 5 via each language
file's `translations` array and read in templates with `t('key')`. Page
copy lives in per-language content files (`home.en.txt`, `home.it.txt`,
…) — only English content exists right now, so other languages currently
fall back to showing the English copy until translated.

**Gotcha**: once any `site/languages/*.php` file exists, Kirby requires
content files to carry the language suffix (`home.en.txt`) — a plain
`home.txt` is silently ignored, not read as a fallback.

## Lead-capture form

v1's single lead-capture form (`site/snippets/lead-form.php`) is used as
both a site-wide modal and the embedded Contact page form. It posts to
`POST /lead` (a route in `site/config/config.php`), which validates
required fields and emails the submission via Kirby's `email()` helper.

SMTP transport is configured via environment variables — set on the
deploy target, never committed:

```
SMTP_HOST
SMTP_PORT
SMTP_USERNAME
SMTP_PASSWORD
SMTP_FROM
```

Without these set, submissions fail gracefully with a JSON error instead
of crashing.

## Deploying to the shared server

A deploy script pushes the site to the target server via SSH/rsync.

### First-time setup (local)

```bash
cp deploy-example.sh deploy.sh
chmod +x deploy.sh
```

Open `deploy.sh` and fill in the real server details (`SSH_USER`,
`SSH_HOST`, `REMOTE_PATH`, `SSH_PORT`, `PHP_BIN`, `COMPOSER_BIN`).
`deploy.sh` is gitignored — credentials never get committed.

### First-time setup (server)

`vendor/` and `kirby/` are never uploaded — Composer runs on the server
after each deploy so dependencies are built for the server's own PHP
version. Install Composer on the server once:

```bash
ssh your-user@your-server.com
curl -sS https://getcomposer.org/installer | php
mv composer.phar ~/composer
```

Confirm the CLI `php` binary matches the web PHP version configured for the
domain (e.g. `ls /usr/local/php*/bin/php` on DreamHost-style hosts), and set
`PHP_BIN` in `deploy.sh` accordingly.

### Running a deploy

```bash
./deploy.sh
```

This runs `bun run build`, rsyncs only the changed files needed on the
server, runs `composer install --no-dev` remotely, and fixes permissions on
Kirby's writable directories (`content/`, `media/`, `site/cache`,
`site/sessions`, `site/accounts`).

## Embedding the booking widget

The booking engine is built as a standalone bundle and hosted from its own
Coolify domain (see `ballaraw-booking-engine`). Once it's packaged as a
custom element, embedding here is a snippet or template loading its script
tag and dropping in the element — no build-time coupling between the two
repos. Cross-origin calls go straight from the browser to the booking
engine's PocketBase API; this site never talks to PocketBase server-side.

## Project structure

```
content/
  home/                        ← home.en.txt
  1_about-us/                  ← about.en.txt
  2_locations/                 ← locations.en.txt (hub)
    1_beach-house/              ← property.en.txt
      1_sole … 8_isola/          ← unit.en.txt × 8 rooms
    2_city-apartments/          ← property.en.txt (intro/grouping only)
      1_zagara, 2_ibisco/        ← unit.en.txt × 2 independent apartments
  3_community-experiences/
  4_workation-and-retreats/
  5_faq/
  6_contact/
src/            ← Tailwind CSS + JS source (compiles to assets/)
site/
  languages/    ← en (default), it, es, de, fr
  blueprints/   ← site.yml + pages/ (property.yml and unit.yml are each
                  shared by multiple pages — see docs/v1-build-plan.md §3)
  config/       ← config.php (SMTP transport + the /lead route)
  plugins/      ← custom and third-party plugins — none yet
  templates/    ← PHP templates, one per blueprint
  snippets/     ← header, footer, hero, lead-form(-modal), faq-widget
```

Planning docs, kept up to date as the site evolves:
- `docs/site-structure-notes.md` — sitemap discovery and decisions
- `docs/v1-build-plan.md` — content model, shared components, build sequence

## Notes

- `vendor/` and `kirby/` are not committed — restored by `composer install`
- Never commit `site/accounts/`, `site/sessions/`, or `site/cache/`
- `deploy.sh` contains server credentials and is gitignored — never commit it
- SMTP credentials for the lead-capture form are environment variables, never committed (see "Lead-capture form" above)
