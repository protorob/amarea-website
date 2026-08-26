# A'Marea v2: blueprint/block rewrite

## Context

v1 shipped with one bespoke blueprint + template per page (`about.yml`,
`community.yml`, `workation.yml`, `faq.yml`, `contact.yml`, `home.yml`,
`locations.yml`, `property.yml`, `unit.yml`) — each with its own fixed set
of content fields. That's now judged too complicated to maintain: every new
page type means a new blueprint, a new template, and duplicated hero/CTA/
gallery boilerplate.

v2's goal (`docs/v2-build-notes/v2-build-notes.md` + the 4 mockups in
`docs/v2-build-notes/mockups/`) is to collapse this down to **5
blueprints**: Home, Locations Archive, Location Page (today's `property`),
Room/Apartment (today's `unit`), and a generic **Default** blueprint that
covers every other page (About, Community, Workation, FAQ, Contact, and
anything added later) via a reusable content-block system instead of
per-page fields.

Confirmed decisions from our discussion:
- **Blocks engine**: native Kirby 5 `blocks` field (custom fieldsets +
  matching `site/snippets/blocks/*.php` renderers) — not a structure-based
  pseudo-block hack.
- **Legacy pages** (About/Community/Workation/FAQ/Contact): keep their
  current functionality by extending the block library with a few more
  block types as we reach each page, rather than dropping features or
  keeping them as bespoke blueprints.
- **Room features**: centralize as a `structure` field (icon + label) on
  the **Site** blueprint; the Unit blueprint selects from it via a
  `query`-driven multiselect instead of today's free-text `tags` field.
- **Workflow**: directly on `main`, step by step, committing as we land
  each step (no v2 branch).
- **Sliders/carousels/lightboxes**: use maintained npm packages rather than
  hand-rolling this JS — see Step 2.
- **FAQs**: a centralized, site-wide FAQ system (Site-blueprint panel + FAQ
  Block + wiring into Location/Unit "FAQs about this page") is real scope
  but explicitly parked as **v2.1**, tackled after the rest of this
  rewrite lands — see the v2.1 section at the end of this roadmap.

This plan lays out the full roadmap so we both have the shape of the whole
rewrite, but — as agreed — only the next 1-2 steps are pinned down in
detail. Later steps have known open questions flagged rather than guessed
at; we'll resolve those when we get there.

## Roadmap

### Step 1 — Site-wide foundations (`site.yml`) ✅ done
- Add `socialLinks`: `structure` field (icon/platform + url + label),
  replacing the current flat `instagramUrl` / `instagramHandle` fields —
  supports multiple platforms (mockup footer shows Facebook/LinkedIn/
  Instagram).
- Add `unitFeatures`: `structure` field (icon + label) — the global
  catalog of room/apartment features, source of truth for Step 7.
- Keep `email`, `whatsapp`, `address`, `footerClaim`, `elfsightWidgetClass`
  as-is.
- Update `site/snippets/footer.php` (currently reads `$site->instagramUrl()`
  /`instagramHandle()`) to render the new `socialLinks` structure instead.

### Step 2 — Blocks engine (the reusable foundation everything else builds on) ✅ done
Add native-Kirby block support:
- `site/blueprints/blocks/hero.yml` + `site/snippets/blocks/hero.php` —
  **Hero Block**: background (color / full image / video, + gradient
  overlay color when image), height (normal/half/full), text color (select
  from the theme palette in `main.css`'s `@theme`), icon (single file,
  optional), eyebrow, title, description (rich text), buttons (`structure`:
  label, link, style primary/secondary/ghost). Empty background + no
  content = spacer, per the notes. Reuses the visual language already in
  `site/snippets/hero.php`.
- `site/blueprints/blocks/slider.yml` + `.../slider.php` — **Slider Block**:
  `structure` of {image, alt}. Use **Swiper** (`bun add swiper`) for the
  carousel behavior (touch/drag, arrows, matches the mockups) instead of
  hand-rolling one — initialized per-instance in `src/main.js`.
- Add **GLightbox** (`bun add glightbox`) as the shared lightbox for gallery
  grids site-wide (Common Gallery on the Location page, unit gallery,
  community gallery, and any Slider Block used as a plain gallery) — one
  shared init in `main.js` keyed off a data attribute, not reimplemented
  per block/page.
- `site/blueprints/blocks/locations.yml` + `.../locations.php` —
  **Locations Block**: data-driven off `page('locations')->children()->listed()`,
  little to no configurable fields. Renders the hover-zoom/reveal card grid
  from the mockups (2 cols desktop / 1 mobile).
- `site/blueprints/blocks/elfsight.yml` + `.../elfsight.php` — **Elfsight
  Block**: thin wrapper around the existing `$site->elfsightWidgetClass()`
  pattern (currently inlined in `home.php`).
- Build a **shared "hover-reveal card grid" partial** (e.g.
  `site/snippets/partials/hover-card-grid.php`) used by the Locations
  Block now, and reused unchanged by the Location Page's "Common Spaces
  Highlights" grid in Step 6 — same visual pattern in both mockups, so
  it's worth extracting once rather than duplicating.

### Step 3 — Home blueprint + template ✅ done
- Rebuilt `home.yml`: top-level Hero fields (eyebrow, title, description
  rich text, buttons `structure`) — separate from the `blocks` field, per
  the notes' "Hero:" vs "Blocks:" split — plus a `blocks` field for
  everything below the fold. Extracted the buttons structure into a
  reusable `site/blueprints/fields/buttons.yml` preset (also used by the
  Hero Block) and a shared `partials/buttons.php` renderer, since both the
  page-level hero and the Hero Block needed identical button behavior.
- Rebuilt `home.php`: `hero.php` snippet now takes a `buttons` structure
  instead of fixed primary/secondary CTA fields, followed by
  `$page->blocks()->toBlocks()->toHtml()`.
- Hand-migrated `content/home/home.en.txt` into the new hero + blocks
  shape (scripted via `$page->update()` for the tricky nested
  structure/JSON serialization, then hand-cleaned of leftover v1 fields).
  Verified with a script-driven render of the real content plus a full
  front-end request — no errors, Locations block correctly resolves the
  Beach House/City Apartments child pages, `page://` link resolution works
  for the secondary hero CTA.
- **Resolved open item**: added a 5th block type, **Highlights**
  (`site/blueprints/blocks/highlights.yml` + `.../highlights.php`) — an
  icon+title+text card carousel (Swiper, 1 card/view mobile → 3
  desktop+pagination dots) to cover "Why A'Marea", since none of the 4
  named blocks fit icon/title/text cards. The Fontane-Bianche section
  composes a Hero Block (olive bg, heading) → Slider Block (photos) →
  another Hero Block (olive bg, "Opening 2027" + button) as three stacked
  blocks sharing one background color, rather than a bespoke
  slider-over-hero coupling — simpler and more robust, small visual
  difference from the mockup's overlap effect.
- **Known content gaps** (no photo assets in the repo yet, left for the
  Panel): the Fontane Bianche Slider block has no images, and the Why
  A'Marea highlight cards have no icons/photos.

### Step 4 — Default blueprint + template (About, Community, Workation, FAQ, Contact)
- Rebuild `default.yml`: `heroImage` (single file) + `blocks` field.
- Rebuild `default.php`: existing `page-hero.php` snippet + blocks render.
- Repoint the 5 legacy pages onto it: delete `about.yml`/`community.yml`/
  `workation.yml`/`faq.yml`/`contact.yml` and their templates, rename each
  page's content file (e.g. `content/1_about-us/about.en.txt` →
  `default.en.txt`) so Kirby resolves them to the new template. Folder
  names/slugs/URLs are untouched.
- Extend the block library only as needed, page by page — expected
  additions: a **Team Block** (About), a **Form Block** (Contact — wraps
  the existing `lead-form.php` snippet). Community/Workation's needs
  (activity list, exclusive-booking inclusions checklist, calendar embed)
  will likely reuse Hero/Slider/a Highlights block rather than needing
  bespoke ones — confirm per page.
- The FAQ page itself stays on today's bespoke `faq.yml` structure for now
  (not migrated in this step) — it gets folded into the Default blueprint
  as part of the FAQ Block work in **v2.1**, below, so it isn't rebuilt
  twice.

### Step 5 — Locations Archive blueprint + template
- Rebuild `locations.yml`: `subTitle`, `description` (rich text),
  `headerImage` (single file), and a `pages` section for the Location
  children (renaming today's `properties` section).
- Rebuild `locations.php`: render the child Location pages through the
  Step 2 shared hover-card-grid partial (matches the mockup, which does
  show a Beach House / City Apartments card grid on this page despite the
  notes calling it "just a container").
- **Open item**: today's `areaGuide` section (beach days / Ortigia /
  baroque Sicily / etc. six-block area guide + map embed) isn't in the v2
  notes for this blueprint at all. Confirm whether it's dropped, or moves
  elsewhere (e.g. into an About/Default page as blocks), before deleting
  that content.

### Step 6 — Location Page blueprint + template (today's `property`)
- Rebuild fields per the notes: `heroImage`, `subtitle`, `description`
  (rich text), `shortDescription`, `address`, slider images, Space
  Highlights eyebrow/title/description, a `structure` for "Common Spaces
  Highlights" (image/title/description, rendered via the Step 2 shared
  card-grid partial), then a second tab with Common Gallery
  (eyebrow/title/description/files) and Location Amenities
  (eyebrow/title/description/`structure` of icon+text), and a `pages`
  section for the unit children.
- Rebuild the template to match the Beach House mockup's section order:
  intro → image slider → rooms grid (units, existing pattern kept) → "The
  Spaces" (via shared partial) → amenities checklist → CTA.
- **Naming**: consider renaming the blueprint/template file from
  `property` to `location` for clarity, since "property" language is gone
  from the notes/mockups/nav ("Locations"). Needs `header.php`'s
  `$pageHeroTemplates` array and its `intendedTemplate() == 'property'`
  dropdown/grid checks updated wherever the name changes — decide and
  apply consistently in this step.

### Step 7 — Room/Apartment (Unit) blueprint + template
- Rebuild `unit.yml`: main image (also used in the Location page's room
  cards), keep existing title/subtitle/description fields, `roomId`
  (rename of `bookingEngineId`), gallery files.
- Replace the `features` `tags` field with a `multiselect`/`checkboxes`
  field whose `options` come from `site.unitFeatures.toStructure()` (Step
  1's global catalog) instead of free text.
- Update `unit.php` to resolve each selected feature back to its icon via
  the site-level structure, and to keep pulling the parent Location's
  Spaces/Amenities sections for display underneath the room's own details
  (already how `faq-widget` finds its parent today — same pattern).

### Step 8 — Cross-cutting cleanup
- `header.php`'s `$pageHeroTemplates` array and the nav dropdown's
  `intendedTemplate() == 'property'`/`'locations'` checks: reconcile with
  whatever templates exist after Steps 3-7 (Default pages always carry a
  `heroImage` now, so the "has a hero" check likely simplifies).
- Re-check `footer.php`, `lead-form.php`/`lead-form-modal.php` for any
  other now-stale field references (`$site->instagramUrl()` etc.).
- Superseding note in `docs/v1-build-plan.md`/`docs/site-structure-notes.md`
  pointing at the v2 docs, if still worth keeping around.

### Step 9 — QA pass
- `composer start` + `bun run dev`, walk every page in a browser: Panel
  editing for each rebuilt blueprint, hero/header scroll behavior, mobile
  menu + Locations dropdown/accordion, Slider Block carousel, hover-reveal
  card grids (desktop hover vs. mobile always-visible), lead-form modal
  and embedded Contact form submission, language switcher on at least one
  non-English page.

## v2.1 — Centralized FAQ system (deferred, after the rest of this roadmap)

- New `faqs` section/panel on the **Site** blueprint: a `structure` field
  (question, answer, category) — the single source of truth for every FAQ,
  replacing today's per-page `faq.yml` structure field.
- New **FAQ Block** (`site/blueprints/blocks/faq.yml` +
  `site/snippets/blocks/faq.php`): reads from the site-level list,
  optionally filtered by category, rendered as an accordion. Used both
  standalone on the FAQ page (full flat list) and embedded on Location/Unit
  pages for "FAQs about this page" — replacing today's
  `site/snippets/faq-widget.php` pattern, which goes away once this lands.
- The FAQ page itself moves onto the Default blueprint (from Step 4) with
  a single FAQ Block, rather than needing its own template — "FAQ
  template" here means this Block+snippet pairing, not a separate page
  template.

## Progress / next step

- Steps 1–2 landed (commit `b558569`): site-wide foundations
  (`socialLinks`, `unitFeatures`) and the block engine (Hero, Slider,
  Locations, Elfsight blocks + shared card-grid partial).
- Step 3 landed (commit `69201e4`): Home rebuilt on the blocks engine,
  5th block type (Highlights) added, content migrated and verified.

Next up: **Step 4 — Default blueprint + template**, repointing About,
Community, Workation, FAQ, and Contact onto it (FAQ content itself stays
put until v2.1).
