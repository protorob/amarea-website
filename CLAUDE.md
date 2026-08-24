# amarea website — Claude Code context

## What this project is

The marketing/booking website for A'Marea, a Mediterranean co-living
business in Fontane Bianche/Siracusa with two properties: **Beach House**
(8 individually bookable rooms) and **City Apartments** (two independent
apartments, Zagara and Ibisco — City Apartments itself is just an
intro/grouping page, not bookable). See `docs/site-structure-notes.md`
for the full structure discovery and `docs/v1-build-plan.md` for the
content model and build sequence behind the current page tree.

Built on Kirby CMS (Plainkit) + Tailwind CSS v4, scaffolded from
[protorob/kirbycms-tailwind-base](https://github.com/protorob/kirbycms-tailwind-base).

**v1 ships waitlist-only** — every "Start Booking" CTA opens a
lead-capture form (see "Lead-capture form" below), not a live booking
flow. The real booking flow (search, availability, checkout) is **not**
part of this repo — it's an embeddable widget from the separate
[ballaraw-booking-engine](https://github.com/protorob/ballaraw-booking-engine)
repo, deployed as its own service on Coolify and dropped into a page here
via a script tag + custom element, once it's ready to swap in. This site
never talks to PocketBase server-side — all booking-engine API calls
happen client-side, cross-origin, from the browser straight to the
booking engine's Coolify domain.

## Tech stack

- **Kirby CMS 5** (Plainkit) — flat-file CMS, no database
- **Multi-language** (Kirby's built-in i18n) — 5 languages: `en` (default,
  unprefixed), `it`, `es`, `de`, `fr`, defined in `site/languages/*.php`.
  Content files must be suffixed with the language code (`home.en.txt`,
  not `home.txt`) — Kirby silently ignores unsuffixed content files once
  any language file exists. UI strings (nav, forms, buttons) are
  translated via `t('key')` and Kirby's `translations` array in each
  language file; page *copy* lives in per-language content files and is
  currently English-only — other languages fall back to English content
  until translated.
- **Composer** — installs `kirby/` and `vendor/`, both gitignored and restored on `composer install`
- **Tailwind CSS v4** via `@tailwindcss/vite` — no `tailwind.config.js`; theme customization happens in `src/main.css` via `@theme`
- **`@tailwindcss/typography`** — loaded via `@plugin` in `main.css`; used for `.prose` blocks
- **Vite** for asset bundling (entry: `src/main.js`, output: `assets/`, gitignored)
- **Bun** as package manager and script runner

## Running locally

```bash
# Terminal 1 — PHP dev server
composer start

# Terminal 2 — CSS/JS watch
bun run dev
```

Site: `http://localhost:8000`
Panel: `http://localhost:8000/panel` (prompts to create the first admin account)

Always run `bun run build` (or keep `bun run dev` running) after changing CSS classes or JS — templates reference `assets/css/main.css` and `assets/js/main.js` directly, not the `src/` files.

## Project structure

```
content/
  home/                           ← home.en.txt
  1_about-us/                     ← about.en.txt (+ team structure field)
  2_locations/                    ← locations.en.txt (hub + area-guide sections)
    1_beach-house/                ← property.en.txt
      1_sole … 8_isola/           ← unit.en.txt × 8 rooms
    2_city-apartments/            ← property.en.txt (not itself bookable)
      1_zagara, 2_ibisco/         ← unit.en.txt × 2 independent apartments
  3_community-experiences/        ← community.en.txt
  4_workation-and-retreats/       ← workation.en.txt
  5_faq/                          ← faq.en.txt
  6_contact/                      ← contact.en.txt
site/
  languages/        ← en.php (default), it.php, es.php, de.php, fr.php
  blueprints/
    site.yml         ← site-wide settings (contact info, footer, Elfsight widget class)
    pages/           ← home, about, locations, property, unit, community, workation, faq, contact, default
                        (property.yml is shared by Beach House + City Apartments;
                        unit.yml is shared by all 8 rooms + Zagara + Ibisco)
  config/
    config.php       ← SMTP transport (env vars, see "Lead-capture form" below) + the /lead route
  snippets/
    header.php       ← nav + transparent/sticky header (see "Hero + header" below)
    footer.php       ← footer + lead-form-modal + Elfsight platform.js
    hero.php         ← full-bleed video/image hero, used on Home
    lead-form.php    ← shared form fields (used by both the modal and Contact page)
    lead-form-modal.php
    faq-widget.php   ← "FAQs about this page" widget, filters faq.yml's structure by category
  templates/         ← one .php per blueprint above
src/
  main.js           ← mobile menu, header scroll behavior, lead-modal + form submit logic
  main.css          ← @import "tailwindcss" + @plugin "@tailwindcss/typography" + @theme
assets/             ← Vite build output (gitignored, rebuilt via bun run build)
```

## Key conventions

- Layout container: `max-w-6xl mx-auto px-4` (main content), `max-w-3xl`/`max-w-4xl` for prose-heavy sections — used across templates to keep things aligned.
- `header.php` and `footer.php` open/close the `<html>`/`<body>` tags (not split into separate `<head>` snippets) — every page template calls `snippet('header')` then `snippet('footer')`.
- Nav is driven by `$site->children()->listed()` — add pages in the Panel and they appear automatically; no manual nav config.
- **Hero + header pattern**: a page opts into the video-hero treatment via a `hasHero: true` toggle field (only `home.yml` has it right now). `header.php` renders transparent-over-hero when `hasHero` is true, solid from the start otherwise. `src/main.js` adds a scroll listener that swaps the header to solid/sticky (with logo swap) once you scroll past the hero — see `docs/v1-build-plan.md` §4 for the design reference this followed.
- **Property/Unit blueprints are shared on purpose**: `property.yml` powers both Beach House and City Apartments; `unit.yml` powers every room and both apartments. Don't fork these per-page — add fields to the shared blueprint if a new property/unit type needs them, so future properties (more villas, more apartments) don't need new content models.
- **Blueprint gotcha**: `structure` and `tags` are *field* types, not *section* types. A section can only be `fields`, `files`, `info`, `pages`, or `stats` — wrap any structure/tags field inside a `fields`-type section (see any `pages/*.yml` for the pattern). Nesting a structure field directly as a section produces "Invalid section type" in the Panel.
- **FAQ categorization**: `faq.yml`'s `faqs` structure has an optional `category` tag field. `snippet('faq-widget', ['category' => $page->slug()])` filters to that category; omit the param for the flat list. Works with zero categories filled in.

## Lead-capture form

One canonical form (`site/snippets/lead-form.php`) is used two ways: as a
site-wide modal (`lead-form-modal.php`, opened by any
`[data-open-lead-modal]` trigger) and embedded directly on the Contact
page. Both submit via `fetch()` to the `POST /lead` route defined in
`site/config/config.php`, which validates required fields, has a
honeypot field (`website`) for basic spam filtering, and sends email via
Kirby's `email()` helper to `$site->email()`.

SMTP transport reads from environment variables — set these on the
deploy target (Coolify), never commit them:

```
SMTP_HOST, SMTP_PORT, SMTP_USERNAME, SMTP_PASSWORD, SMTP_FROM
```

Without them, the route still responds with a clean JSON error
(`{"ok":false,"error":"send_failed"}`) instead of crashing.

## Deploying

`deploy-example.sh` is the committed template — copy it to `deploy.sh`
(gitignored, holds real server credentials) and fill in the target server's
SSH/PHP/Composer details. See the README's "Deploying to the shared server"
section for the full walkthrough.

`vendor/` and `kirby/` are never uploaded — Composer runs on the server
after each deploy so dependencies build against the server's own PHP
version.

## Brand assets

Logos, fonts, and brand guidelines are vendored from the `amarea` repo
(source of truth: `protorob/amarea`, `brand-assets/`) — copy updates in
manually when the brand assets change; there's no live link between the
repos.

Design tokens (`--color-*`, `--font-*` in `src/main.css`) belong to this
site only and are not shared with `ballaraw-booking-engine`. The booking
engine is meant to be embeddable on other sites too, so it should style
itself to match whatever site it's dropped into, rather than this site
tracking the booking engine's tokens.
