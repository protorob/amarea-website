# amarea website — v1 build plan

Synthesizes `site-structure-notes.md` (structure discovery, now settled)
into an actionable plan. This is the document to review before I start
writing blueprints/templates — once you sign off, I'll work through
§5 in order.

## 1. Scope for v1

- **Waitlist-first.** Every booking touchpoint (Home hero, room/apartment
  pages, Workation, Contact) opens the same lead-capture form. No live
  booking engine, no pricing/availability data in v1 — that's a later
  swap-in against `ballaraw-booking-engine`, and the architecture already
  keeps that decoupled (client-side, cross-origin), so it's a drop-in
  later, not a rebuild.
- **5 languages**: English (default), Italian, Spanish, German, French.
- **Design direction**: fgsglobal.com's hero pattern — full-bleed video
  background hero, header overlaid transparent on top of it, header
  becomes a solid/sticky bar once you scroll past the hero. Detailed in
  §4.

## 2. Final sitemap

```
Home
About Us/Team
Locations
 ├── Beach House (Fontane Bianche)
 │     ├── Sole · Onda · Luna · Cala · Terra · Zefiro · Azzurra · Isola
 └── City Apartments (Siracusa, intro/grouping page only — not bookable itself)
       ├── Zagara
       └── Ibisco
Community & Experiences
Workation and Retreats
FAQ
Contact
```

Rooms and the two apartments are the same *kind* of page (an
independently-authored bookable unit — own copy, gallery, amenities, CTA).
Beach House and City Apartments are the same kind of page (an
intro/grouping page for one or more bookable units below it). This is
also what leaves room for future properties without a new content model.

## 3. Content model per page type

Not final blueprint YAML yet — just what fields/sections each type needs,
so the blueprint work in §5 is mechanical once you've confirmed this.

**Home** — hero (video bg, headline, primary/secondary CTA), intro text,
Fontane Bianche teaser block, "Why A'Marea" (4 fixed feature blocks), UGC
feed embed, Community & Experiences teaser + gallery + Instagram CTA,
lead-capture form section, footer nav.

**About Us/Team** — intro text, "The Symbol" text block, "Our Philosophy"
(4 fixed sub-blocks: Home / Flow / Belonging / The Mediterranean Way),
team members as a **structure field** (photo, name, role, bio) — repeater,
not separate pages, so Nicholas/Diane/Mara-style additions don't need a
template change.

**Locations (hub)** — intro text, then the "Fontane Bianche & Syracuse"
area-guide as **sections on this page** (not subpages): gallery + 6 fixed
blocks (beach days, Syracuse & Ortigia, baroque Sicily, nature &
adventure, food & wine, slow days) + interactive map placeholder. Below
that, a summary/link card for each of Beach House and City Apartments.

**Property (Beach House, City Apartments)** — shared blueprint. Intro
text, gallery, amenities list, and a **subpages-as-children** section
listing its bookable units (rooms or apartments) — Kirby page list, not a
structure field, since each unit is its own real page with its own URL
and content.

**Unit (room or apartment)** — shared blueprint for both room pages and
Zagara/Ibisco. Name, room number (optional — rooms have it, apartments
don't), description, feature list (bed/bathroom/view/desk/etc. — a tags
or checkboxes field), gallery, "Show availability & start booking" CTA
(→ lead-capture modal in v1). No shared "type" content structure per your
answer — each unit page is independently authored, even where copy
happens to be similar across two rooms.

**Community & Experiences** — intro text, UGC feed embed, "Weekly
Activity Ideas" (structure field — plain list of activity names, marked
as not-included/optional), calendar-of-events placeholder, lead-capture
form section.

**Workation and Retreats** — intro text, UGC feed embed, exclusive
full-house booking details (8 bedrooms / up to 18 people, bullet list of
inclusions), outbound link to TerradiSiciliaDMC, gallery, Start Booking
CTA, lead-capture form section.

**FAQ** — flat list of Q&A entries for v1 (structure field: question,
answer). Categories are TBD (your answer to Q4) — see §6 for the
mechanism I'd build so categorization can be turned on later without
restructuring existing FAQ content.

**Contact** — the lead-capture form, embedded directly on the page (same
form as the modal — see §4), plus a WhatsApp button.

## 4. Shared components

**Header** — overlaid transparent on the hero (light text, no
background) at scroll position 0. On scroll past the hero, a small JS
scroll listener toggles a class that switches it to `position: sticky`
with a solid background, normal text color and a shadow. Implemented as
plain JS in `src/main.js` (no new dependency needed) + Tailwind classes
for the two states.

**Hero** — full-bleed `<video autoplay muted loop playsinline>` background
(matches your "video di sfondo: mare, acqua, onde" note in the copy doc),
headline + primary/secondary CTA positioned over it with a dark
gradient overlay for text contrast. Used on Home; other pages likely get
a simpler static-image variant of the same header-overlay treatment
(worth confirming per-page during build, not a blocker now).

**Lead-capture form** — one canonical form, used two ways: as a modal
(triggered by any "Start Booking"/"Join the waitlist" CTA across the
site) and embedded directly on the Contact page. Fields, per your answer:

- First Name, Last Name, Email, Phone, Country of residence (all required)
- Billing type: professional / personal (required) → VAT ID field shown
  only if professional
- LinkedIn profile (optional)
- Free-text "tell us more about yourself" (required)
- Referral source (select: Facebook, Instagram, Search engine, Friend/
  family, Website referral, Other)

**Open decision — where do submissions go?** Not specified yet: email
notification (needs SMTP config, not yet set up per `CLAUDE.md`), a Kirby
plugin storing entries as content, or an external form/CRM service. This
blocks wiring the form even though the page/UI can be built without it —
flag your preference before §5 step 9.

**UGC/Social feed** — Elfsight embed (snippet already captured in
`site-structure-notes.md` §1), used on Home, Community & Experiences, and
Workation and Retreats.

**Footer** — logo + "Mediterranean Co-Living" claim, nav (About us /
Locations / Community & experiences / Workation & Retreat / FAQ /
Contact), Instagram icon (@amarea_coliving), contact email + address
(Fontane Bianche, Siracusa, Sicilia).

## 5. Build sequence

1. Kirby multi-language config (`en` default, `it`, `es`, `de`, `fr`) +
   base site blueprint (nav structure from the sitemap in §2).
2. Global chrome: header (transparent→sticky pattern), hero component,
   footer, lead-capture form + modal shell (UI only, submission target
   pending §4's open decision).
3. Home page.
4. About Us/Team page + team structure field.
5. Locations hub (intro + area-guide sections).
6. Property blueprint (Beach House, City Apartments) + Unit blueprint
   (8 rooms + Zagara + Ibisco) — build the two shared blueprints once,
   then author all 10 unit pages + 2 property pages from them.
7. Community & Experiences page.
8. Workation and Retreats page.
9. FAQ page + category mechanism (see §6).
10. Contact page, wire the lead-capture form once §4's open decision is
    resolved.
11. Content pass: enter English copy from `amarea-website-copy.md`
    everywhere; confirm with you whether IT/ES/DE/FR copy ships at launch
    or gets added after (translations aren't in the copy doc yet).
12. Responsive/cross-browser QA pass, then deploy to staging
    (`deploy.sh`, per `CLAUDE.md`).

## 6. FAQ categorization mechanism (proposal)

Since categories are TBD, build the *mechanism* now without needing the
final category list: FAQ entries live as a structure field (question,
answer, **category tag** — free-text or select, your call once
categories exist) on the FAQ page. A snippet reads that structure,
filtered by a `category` param, so any page can drop in "FAQs about this
page" by passing its own category — same idea as the flowmap's room→FAQ
links. Works with zero categories filled in (falls back to the flat
list on the FAQ page itself); categorizing later is just filling in the
tag field, no template change.

## 7. Open questions before/while building

- Lead-capture form submissions: where do they go? (§4) 
  We can use simple smtp based email delivery
- Do IT/ES/DE/FR translations ship at v1 launch, or English-only first
  with other languages added after? Affects whether step 11 blocks
  deploy or not.
  ----
  Translations since the beginning
- FAQ categories: still TBD, mechanism doesn't block on this (§6).

Everything else in this doc reflects decisions already made in
`site-structure-notes.md`. Confirm/edit, then say go and I'll start on
§5 step 1.
