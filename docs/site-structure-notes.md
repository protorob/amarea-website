# Site structure — reading of `amarea-structure.png`

This is my understanding of the flowmap, written up so you can correct it
before anything gets turned into Kirby blueprints/templates. Nothing has
been built yet — this is purely "did I read the diagram right."

Legend from the image:
- ⚪ black border — static page / landing
- 🟠 orange border — location intro page that's *also* a room container
- 🔵 blue border — room container (property, or individual room)
- 🟣 purple border — external link
- 🟢 green — booking-related (CTA, or data pulled from the booking engine)
- 🔴 red — third-party embedded tool

## 1. Top-level nav (from Home)

Home links to six static pages: **About Us/Team**, **Locations**,
**Community & Experiences**, **Workation and Retreats**, **Categorized
FAQs**, **Contact**.

Home also:
- has a **Start Booking** CTA (direct link to the booking flow) This might be as well the booking widget as in ./docs/booking-home-widget.jpg
- embeds the **UGC + Social Feed** block directly on the page (not just
  linked from a subpage). Social/UCG Widget code is this from elfsight:

  ```
  <!-- Elfsight Instagram Feed | Untitled Instagram Feed -->
  <script src="https://elfsightcdn.com/platform.js" async></script>
  <div class="elfsight-app-b9732c5e-fb14-494a-bebd-847bf5025bed" data-elfsight-app-lazy></div>
  ```

### Categorized FAQS

The idea is to have both a "FAQs" page and the possibility to "categorize by page" so we can have a widget that when placed on a specific page will display only the FAQs related to that content

## 2. Locations → properties → rooms

```
Locations ──┬──> Fontane Bianche (intro) ──> Villa (property) ──> Camera 1, Camera 2, Camera 3, …
            └──> Siracusa (intro)  ──> Casa di Olga (property) ──> (no per-room subpages, as it is a full house but will be managed also from booking engine)
About Us/Team ──┬──> Fontane Bianche
                └──> Siracusa
```

Two things stand out here that I want to flag rather than assume:

- **Both "Locations" and "About Us/Team" link to both location intros**
  (Fontane Bianche and Siracusa). About Us just link to the
  Locations hub.
- **This implies two separate bookable properties** The villa comprises multiple rooms, while the "Casa di Olga" is a full property that can be booked only the entire home

Every room-level page (Camera 1/2/3…, and Casa di Olga's combined page)
carries a **"Show availability & start booking"** CTA, fed by live
**pricing/availability pulled client-side from the booking engine** — this
matches the architecture already described in `CLAUDE.md` (booking widget
is embedded, cross-origin, no server-side coupling).

Room-level pages also each link out to **Categorized FAQs** (drawn once
from "Camera 3…" and once from Casa di Olga's combined page, presumably
representing "every room/property page links to relevant FAQs," not just
those two specifically).

## 3. Community & Experiences / Workation and Retreats

Both of these pages, plus Home, embed the same **UGC + Social Feed**
block, which is powered by the third-party **Elfsight** widget.

Workation and Retreats additionally has one **outbound external link** to
**TerradiSiciliaDMC** (a purple "link esterni" node — presumably a partner
DMC site, opens off-site).

Workation and Retreats also has its own **Start Booking** CTA, same as
Home.

## 4. Contact

Contact has a **Start Booking** CTA too (all three — Home, Workation and
Retreats, Contact — point at the same booking flow).


## 6. Booking flow itself

"Start Booking" is drawn as a distinct green box — not a page in this
site, but the entry point into the booking engine widget (per
`ballaraw-booking-engine`, embedded client-side).

## Open questions before we move to blueprints

1. Is amarea one property or two (Villa + Casa di Olga)? This changes the
   whole content model? Amarea is two properties but the services are organized for both properties. So you can book one of the rooms in the villa or if you prefer you can book an entire home "Casa di olga" but the hosts are always the same and both properties (which are fairly close together) share the same activities organized by the host. 
2. Should About Us/Team really deep-link to both location intros, or just
   to the Locations hub? Just the location hubs.
3. Is the room-vs-no-rooms asymmetry between Villa and Casa di Olga
   intentional and permanent, or should both eventually support per-room
   pages? i will need per room pages on the villa and casa di olga is a single whole home and for the forseeable future is going to remain like this. Keep open the possibility that we might add other multiroom properties or single villas to our co-living business
4. For "Categorized FAQs" — do you already have the categories in mind
   (e.g. booking, house rules, location), or is that TBD? This is TBD really
5. Anything the diagram doesn't capture that should exist (e.g. a
   Privacy/Terms page, blog/journal, language switcher)? Yes the website needs to be in 5 languages (English defaut, italian, spanish, german, french)

---

## 7. Reading `amarea-website-copy.md` — what it changes

The copy doc is much more concrete than the flowmap, and it changes the
picture in a few real ways, not just fills in text. Going section by
section:

### The two properties now have names, and both look unit-based

- **Fontane Bianche property → "A'Marea - Beach House"**. Has **8
  individually named rooms**, not generic "Camera 1/2/3": *Sole (1), Onda
  (2), Luna (3), Cala (4), Terra (5), Zefiro (6), Azzurra, Isola*. Each
  room has its own gallery + feature list (bed, bathroom, view, desk,
  A/C). Rooms fall into **5 repeating "types"** that share description
  copy:
  - Sea View & Private Terrace → Sole, Onda
  - Garden View & Private Balcony → Terra, Zefiro
  - Garden View → Luna, Cala
  - Shared Bathroom & Sea View → Azzurra
  - Shared Bathroom & Garden View → Isola
  - The FAQ confirms "all 8 bedrooms, up to 18 people" for a full-house
    exclusive booking — matches 8 named rooms.

- **Siracusa property → "A'Marea — City Apartments"**. The copy doc
  describes **two separately-detailed apartments**, Zagara (2 bedrooms)
  and Ibisco (1 bedroom) — each with its own description, feature list
  and gallery, styled exactly like the Beach House room cards.

  ⚠️ **This conflicts with the answer to Q1/Q3 above** ("Casa di Olga is
  a single whole-home booking, no per-room pages, will stay that way").
  "Casa di Olga" doesn't appear anywhere in the copy doc — it's possible
  it was renamed to "City Apartments" and, in the process, went from one
  bookable unit to two (Zagara + Ibisco booked individually), similar to
  how the Beach House works. Or "Casa di Olga" could still be the single
  bookable building, and Zagara/Ibisco are just descriptive sub-sections
  on that one page (like the Beach House's "The Spaces" — Living Room /
  Kitchen / Garden — which are shown as cards but aren't separately
  bookable). **Need your call here** — see Q6 below, this is the one
  that most changes the content model.

### "Our Location" looks like it might be one nav item, not a hub of two

The footer nav in the copy doc lists **"Our Location"** (singular) as one
link, alongside About us / Community & experiences / Workation & Retreat
/ FAQ / Contatti. That's 6 links total — matching Home's 6 outgoing edges
in the flowmap one-for-one, with "Locations" relabeled "Our Location."
Doesn't necessarily mean it's a single page (Kirby nav items can still be
section hubs), but worth confirming the nav *label* at least.

The copy doc also has a chunk of "Fontane Bianche & Syracuse" area-guide
content (beaches, Ortigia, baroque towns, nature, food, interactive map —
"Your Mediterranean playground") that isn't tied to either property
specifically. This reads like a third thing living under
Locations/Our Location: general area info, separate from the two
property pages.

### Pre-launch / waitlist state

Nearly every CTA in the copy doc is **"Join the waitlist"**, and there's
a full waitlist form (name, email, phone, country, billing type, VAT ID,
LinkedIn, free-text, referral source) appearing on Home, Community &
Experiences, and Workation and Retreat. "Opening 2027" is called out
explicitly. This sits alongside the flowmap's live booking-engine
integration (pricing/availability pulled client-side, "Show availability
& start booking" CTAs).

**Q7 below**: is v1 of this site waitlist-only (booking engine wired up
later, closer to the 2027 opening), or do both need to coexist from the
start?

### Concrete assets now available

- Elfsight embed snippet for the UGC/social feed (Home + Community &
  Experiences + Workation and Retreats) — captured above in §1.
- `docs/booking-home-widget.jpg` — a simple check-in/check-out ·
  guests & rooms · "Search availability" bar. This is presumably what
  "Start Booking" on Home renders as.
- Full FAQ copy (currently one flat list, category system TBD per your
  answer to Q4).
- Full Contact form spec: First name, Last name, Email, Question, Send
  request — plus a WhatsApp button.
- Team bios for About Us (Nicholas, Diane, Mara) — clean repeater
  candidate (photo, name, role, bio).

## New open questions (from the copy doc)

6. **The big one**: is "City Apartments" one bookable property (with
   Zagara/Ibisco as descriptive sub-sections, no separate booking), or
   are Zagara and Ibisco two independently bookable units — i.e. City
   Apartments works exactly like the Beach House, just with 2 named
   apartments instead of 8 named rooms? This decides whether apartments
   get their own pages/booking-engine IDs like rooms do.
   ----
   Answer: Zagara and Ibisco are two independent properties.
7. Is the live booking engine in scope for v1, or does this build ship
   waitlist-only first (booking engine swapped in later without a
   content-model change, since CLAUDE.md's architecture already keeps it
   decoupled)?
   ----
   Answer: This build ship waitlist only (then we swap for booking engine)
8. Should room *types* (the 5 repeating descriptions) be their own
   content structure that individual rooms inherit from, or should each
   room page just carry its own copy independently (duplicated where two
   rooms share a type)? Independent is simpler and more Kirby-idiomatic
   given only 8 rooms total, but means editing the same paragraph twice
   if it changes.
   ----
   Answer: Each room/property have its own copy independently.
9. Is the "Fontane Bianche & Syracuse" area-guide content its own page
   under Locations/Our Location, or a section on the Locations hub page
   itself?
   ----
   Answer: We can do sections in the locations hub/page 
10. Nav label: "Locations" or "Our Location"?
   ----
   Answer: "Locations" is good

---

## 8. Final structure, synthesized from your answers

Putting Q6–Q10 together, this is what I now think the site actually is —
this is a proposal, not a re-ask, but flag anything that's still off
before I start on blueprints.

The Q6 answer ("Zagara and Ibisco are two independent properties") is
the piece that makes everything else click into place: **"City
Apartments" isn't a bookable property at all** — it's the same kind of
intro/grouping role that "Fontane Bianche" played for the Villa in the
original flowmap, just for Siracusa. Zagara and Ibisco sit underneath it
as two fully independent, single-unit bookable properties (each is its
own "Casa di Olga" in the old terms — the earlier answer that a
whole-home property has no per-room pages still holds, there are just
two of them now instead of one).

Combined with Q9 (area-guide content becomes sections on the Locations
page, not a separate page) and Q8 (no shared "room type" content
structure — every room/apartment page is independent copy), the
Locations branch collapses one level from the original flowmap:

```
Locations (hub — intro copy + "Fontane Bianche & Syracuse" area-guide
            sections: beaches, Ortigia, baroque towns, nature, food,
            interactive map)
 │
 ├── Beach House            (Fontane Bianche — multi-room property)
 │     ├── Sole      (room 1 — sea view & private terrace)
 │     ├── Onda      (room 2 — sea view & private terrace)
 │     ├── Luna      (room 3 — garden view)
 │     ├── Cala      (room 4 — garden view)
 │     ├── Terra     (room 5 — garden view & private balcony)
 │     ├── Zefiro    (room 6 — garden view & private balcony)
 │     ├── Azzurra   (shared bathroom & sea view)
 │     └── Isola     (shared bathroom & garden view)
 │
 └── City Apartments        (Siracusa — intro/grouping page only,
       │                      not itself bookable)
       ├── Zagara            (independent property — 2 bedrooms)
       └── Ibisco            (independent property — 1 bedroom)
```

Each of the 10 leaf pages (8 rooms + Zagara + Ibisco) is an independent,
fully-authored page — own description, own feature list, own gallery,
own "Show availability & start booking" CTA — with no shared "type"
content behind them (Q8). Structurally rooms and the two apartments are
the same kind of page (a bookable unit); Beach House and City Apartments
are the same kind of page (an intro/grouping page for one or more
bookable units) — this is also what keeps the door open for future
properties (Q3: more multi-room villas or single units down the line)
without a new content model each time.

Given Q7 (v1 ships waitlist-only), every "Show availability & start
booking" / "Start Booking" CTA in v1 renders as a waitlist form instead
of the live booking widget — same page structure either way, so this
doesn't change anything above, just what the CTA button does for now.

### Full site tree (all sections combined)

```
Home
 ├── Start Booking CTA → waitlist form (v1) / booking-home-widget.jpg (later)
 ├── UGC + Social Feed (Elfsight)
 └── "Join the waitlist" form

About Us/Team
 ├── Symbol / Philosophy (Home, Flow, Belonging, The Mediterranean Way)
 └── Meet the Team (Nicholas, Diane, Mara — repeater: photo, name, role, bio)

Locations                              [see tree above]
 ├── Beach House → 8 rooms
 └── City Apartments → Zagara, Ibisco

Community & Experiences
 ├── UGC + Social Feed (Elfsight)
 ├── Weekly Activity Ideas (list) + calendar
 └── "Join the waitlist" form

Workation and Retreats
 ├── UGC + Social Feed (Elfsight)
 ├── Exclusive full-house booking details (8 bedrooms / up to 18 people)
 ├── Outbound link → TerradiSiciliaDMC
 ├── Start Booking CTA
 └── "Join the waitlist" form

Categorized FAQs
 └── flat list for now; category-by-page widget is TBD (Q4)

Contact
 ├── Start Booking CTA (when clicking these CTAs we can just point to contact form)
 └── Contact form (first/last name, email, question) + WhatsApp button
Both of these "Start booking CTA" (opens in modal) and Contact Form should reflect: 
----
**CTA:** **Form fields:****
- First Name (Required)
- Last Name (Required)
- Email (Required)
- Phone (Required)
- Country of residence (Required)
- Do you want professional billing? (Required)
  - Select billing type: professional billing / personal billing
- VAT ID (If professional Billing)
- Your linkedin profile
- Tell us more about yourself (required)
- How did you hear from us (select between: Facebook, Instagram, Search engine (google, bing...),  Friend or family recommandation, Website referral (coliving.com, nomadico...), Other )
-----
```

Footer (site-wide, not a page): logo + "Mediterranean Co-Living" claim,
nav (About us / Locations / Community & experiences / Workation & Retreat
/ FAQ / Contact), Instagram icon, contact email + address.

Site-wide: 5 languages (EN default, IT, ES, DE, FR) — this is a Kirby
multi-language setup decision (URL structure, translatable fields) to
handle at blueprint/config time, not something that changes the page
tree above.

If this matches what you had in mind, next step is turning this into
actual page structure (blueprints + `content/` skeleton) — say the word
and I'll start with the Locations branch since that's the one we just
settled.
