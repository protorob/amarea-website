# amarea website — Claude Code context

## What this project is

The marketing/booking website for amarea, a four-room house in Syracuse.
Built on Kirby CMS (Plainkit) + Tailwind CSS v4, scaffolded from
[protorob/kirbycms-tailwind-base](https://github.com/protorob/kirbycms-tailwind-base).

The booking flow (search, availability, checkout) is **not** part of this
repo. It's an embeddable widget from the separate
[ballaraw-booking-engine](https://github.com/protorob/ballaraw-booking-engine)
repo, deployed as its own service on Coolify and dropped into a page here
via a script tag + custom element. This site never talks to PocketBase
server-side — all booking-engine API calls happen client-side, cross-origin,
from the browser straight to the booking engine's Coolify domain.

## Tech stack

- **Kirby CMS 5** (Plainkit) — flat-file CMS, no database
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
content/            ← pages and uploaded files
site/
  blueprints/       ← Panel field definitions (pages/default.yml, site.yml)
  config/           ← config.php (email, SMTP, plugin settings) — not created yet
  plugins/          ← custom and third-party plugins — none yet
  snippets/         ← header.php, footer.php (shared page chrome)
  templates/        ← default.php — one .php per page type as the project grows
src/
  main.js           ← JS entry (imports main.css, mobile menu toggle)
  main.css          ← @import "tailwindcss" + @plugin "@tailwindcss/typography" + @theme
assets/             ← Vite build output (gitignored, rebuilt via bun run build)
```

## Key conventions

- Layout container: `max-w-5xl mx-auto px-4` — used in header, footer, and `<main>` to keep everything aligned. Adjust the max-width per project as needed.
- `header.php` and `footer.php` open/close the `<html>`/`<body>` tags (not split into separate `<head>` snippets) — every page template calls `snippet('header')` then `snippet('footer')`.
- Nav is driven by `$site->children()->listed()` — add pages in the Panel and they appear automatically; no manual nav config.
- No SEO plugin, no email config, no custom blueprints beyond the Plainkit defaults yet — add these as needed.

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
