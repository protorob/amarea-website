# amarea — website

Marketing/booking site for amarea (a four-room house in Syracuse), built on
[Kirby CMS](https://getkirby.com) (Plainkit) + Tailwind CSS v4, managed with
Composer. Scaffolded from
[protorob/kirbycms-tailwind-base](https://github.com/protorob/kirbycms-tailwind-base).

The booking flow itself is not part of this repo — it's embedded from
[ballaraw-booking-engine](https://github.com/protorob/ballaraw-booking-engine),
a separately deployed widget served from its own Coolify service.

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
- `src/main.js` — entry point for JS behavior (mobile menu, etc.)
- `site/snippets/header.php` / `site/snippets/footer.php` — shared page chrome
- `site/templates/default.php` — default page template

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
content/        ← pages and uploaded files
src/            ← Tailwind CSS + JS source (compiles to assets/)
site/
  blueprints/   ← Panel field definitions
  config/       ← config.php (email, SMTP, plugin settings)
  plugins/      ← custom and third-party plugins
  templates/    ← PHP templates
  snippets/     ← reusable template partials (header, footer)
```

## Notes

- `vendor/` and `kirby/` are not committed — restored by `composer install`
- Never commit `site/accounts/`, `site/sessions/`, or `site/cache/`
- `deploy.sh` contains server credentials and is gitignored — never commit it
