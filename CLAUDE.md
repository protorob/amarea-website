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
  unprefixed), `it`, `fr`, `es`, `de`, defined in `site/languages/*.php`.
  The files are named `1-en.php` … `5-de.php` — Kirby has no explicit
  sort field for languages, it just `glob()`s the directory alphabetically
  (see `Languages::load()`), so the numeric prefix is what keeps the
  language switcher in EN/IT/FR/ES/DE order (same trick as the numbered
  content folders). Content files must be suffixed with the language code
  (`home.en.txt`, not `home.txt`) — Kirby silently ignores unsuffixed
  content files once any language file exists. UI strings (nav, forms,
  buttons) are translated via `t('key')` and Kirby's `translations` array
  in each language file; page *copy* lives in per-language content files
  and is currently English-only — other languages fall back to English
  content until translated.
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
  languages/        ← 1-en.php (default) … 5-de.php — numeric prefixes
                       control the language-switcher order (EN/IT/FR/ES/DE)
  blueprints/
    site.yml         ← site-wide settings (contact info, footer, Elfsight widget class)
    pages/           ← home, about, locations, property, unit, community, workation, faq, contact, default
                        (property.yml is shared by Beach House + City Apartments;
                        unit.yml is shared by all 8 rooms + Zagara + Ibisco)
  config/
    config.php       ← SMTP transport (env vars, see "Lead-capture form" below) + the /lead route
  snippets/
    header.php       ← nav (uppercase, FAQ hidden, Locations dropdown) +
                        transparent/sticky header (see "Hero + header" below)
    footer.php       ← fixed, reveal-on-scroll footer with an ocean-waves
                        video bg + lead-form-modal + Elfsight platform.js
    hero.php         ← full-bleed video/image hero, used on Home only
    page-hero.php    ← banner hero (title bottom-aligned) for interior
                        pages — see "Hero + header pattern" below
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
- `header.php` and `footer.php` open/close the `<html>`/`<body>` tags (not split into separate `<head>` snippets) — every page template calls `snippet('header')` then `snippet('footer')`. Between them, `header.php` opens `#page-wrap` and `footer.php` closes it (see "Reveal footer" below) — don't insert page markup outside that pair.
- Nav is driven by `$site->children()->listed()` — add pages in the Panel and they appear automatically; no manual nav config. It's uppercase, FAQ is excluded (`->not($site->find('faq'))`, footer-only), and any item whose children include a `property`-template page (i.e. Locations) renders as a dropdown on desktop / accordion on mobile instead of a flat link — see the nav loop in `header.php`.
- **Hero + header pattern**: two hero treatments share one header behavior.
  Home opts into the full-bleed video/image hero via `hasHero: true`
  (`hero.php` — centered content, bouncing scroll chevron). The 7 interior
  templates (about, locations, community, workation, faq, contact,
  property — everything except home and unit/room pages, which are still
  TBD) always render a shorter banner hero via `page-hero.php`: the
  page's own `heroImage` field, or a fallback to
  `page('home')->file('hero-ocean-waves.jpg')` so there's never a flat
  hero, with the title bottom-aligned in the banner. `header.php` treats
  both cases as "has a hero" (`$hasHero`, see `$pageHeroTemplates`),
  rendering transparent-over-hero on load and going solid/sticky within
  ~24px of scroll (`src/main.js`) — the logo cross-fades white→blue on a
  slight delay rather than popping instantly. See `docs/v1-build-plan.md`
  §4 for the design reference this followed.
- **Reveal footer**: `footer.php` is `position: fixed` to the viewport
  bottom, with the same ocean-waves video as a background, and sits
  *behind* everything else (`z-0`). `#page-wrap` — opened at the end of
  `header.php`, closed at the start of `footer.php` — is the opaque,
  higher-stacked layer (`z-10`) that scrolls up to reveal the fixed
  footer underneath instead of it scrolling in normally. `main.js` keeps
  `#page-wrap`'s `margin-bottom` synced to the footer's real height on
  load/resize so there's always exactly enough scroll room.
- **Mobile menu collapses via `grid-template-rows` (`0fr` ↔ `1fr`), not
  `opacity`/`pointer-events`** — an opacity-only closed state still lays
  out at full height inside the fixed header, which then got painted
  (and blurred) as part of the header once scrolled solid. Keep any
  future mobile-nav additions (the menu itself and the Locations
  accordion) on this pattern rather than reintroducing an opacity-only
  toggle.
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
