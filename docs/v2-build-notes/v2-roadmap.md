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
  A'Marea highlight cards have no icons/photos. *(Filled in via the Panel
  shortly after — see the block-polish pass below.)*

### Block polish pass (post-Step-3, pre-Step-4) ✅ done
Review of Step 1-3 while testing in the Panel surfaced 5 fixes, applied
before starting Step 4 so every later block built on Step 4+ inherits
them:
- Extracted Hero's background/height/text-color fields into
  `site/blueprints/tabs/layout.yml`, added to all 5 blocks via
  `extends: tabs/layout` (Slider, Locations, Elfsight, Highlights didn't
  have any background control before).
- Added a `block_layout()` helper (`site/config/helpers.php`) — one place
  for the color/height/prose-invert class maps instead of copy-pasting
  per block, which is what caused the text-color bug below.
- Hero Block: icon now has a size select (small/medium/large).
- Highlights Block: redesigned to photo-first cards (image on top, no
  icon glyph/box), arrows moved to a static row below, dots dropped —
  matches the "Why A'Marea" reference look.
- Slider Block: rebuilt as a centered "peek" carousel (Swiper
  `centeredSlides` + fractional `slidesPerView`) instead of a plain
  one-at-a-time slider, matching the tidescape.framer.ai reference.
- Fixed a bug where a block's own description text didn't always respect
  its chosen text color — `@tailwindcss/typography`'s `.prose` hardcodes
  its own dark palette unless told to invert, and the old per-block check
  only added `prose-invert` for `textColor === 'white'`, missing the
  `bg` (cream) option. The shared helper now inverts for any non-`ink`
  text color.

### Step 4 — Default blueprint + template (About, Community, Workation, Contact) ✅ done
- Built `default.yml` (`heroImage` + `blocks` field) and `default.php`
  (`page-hero.php` + blocks render + the FAQ widget, harmless no-op until
  v2.1 since no FAQ entries have categories yet).
- Two new block types, both with the standard Layout tab like every other
  block: **Team** (photo/name/role/bio grid — About) and **Form** (wraps
  `lead-form.php` + the WhatsApp CTA — Contact).
- Repointed About, Community, Workation, and Contact onto Default:
  deleted their bespoke blueprints/templates, renamed each content file
  to `default.en.txt`. Content that didn't fit an existing block folded
  into what was already there rather than growing the block library
  further — About's philosophy pillars and Community/Workation's
  bold-headed sub-sections became Highlights blocks; Community's flat
  activity list and Workation's exclusive-booking inclusions became
  bullet lists inside a Hero block's rich-text description; Workation's
  outbound link became a secondary button on its intro Hero block.
- FAQ stays on its bespoke `faq.yml` blueprint for now, per the v2.1 plan
  below — not touched in this step.
- Verified every page on the site (Home, About, Locations archive, Beach
  House, a room, City Apartments, Community, Workation, FAQ, Contact)
  with a full render pass — no errors or warnings — plus a direct
  blueprint-resolution check for all 4 migrated pages.

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
- Block polish pass landed (commit `c24aa65`): shared Layout tab on all
  5 blocks, icon sizing, redesigned Highlights/Slider carousels, and the
  text-color/prose bug fix.
- Follow-up fixes: Home's hero-header regression (`348f450`), Slider
  peek/scale + full-width redesign (`7cb9d4d`, `f7cfac8`), a Tailwind/
  Swiper CSS cascade-layer bug (`78f0606`), lightbox removed from the
  Slider block (`b9349dd`), background/text color fields switched to
  Kirby's native `color` field with theme-preset swatches (`9e0d739`).
- Step 4 landed (commits `976fd8a`, `fccba1f`, `f7235aa`, `76423e3`):
  Default blueprint + Team/Form blocks built, About/Community/Workation/
  Contact all migrated off their bespoke blueprints.

**The "5 blueprints total" goal from the v2 notes is now met**: Home,
Locations Archive (still on its v1 shape), Location Page/`property`
(still v1), Room/Apartment/`unit` (still v1), and Default. Steps 5-7
still need to rebuild Locations/Location Page/Unit's *fields* per the
v2 notes — they're just not blocking the blueprint count anymore.

Next up: **Step 5 — Locations Archive blueprint + template**, including
its own open item (today's `areaGuide` section isn't in the v2 notes at
all — confirm keep/drop/relocate before touching it).
