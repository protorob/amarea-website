<?php
// Shared centered "peek" carousel (Swiper) — the active photo reads large
// and centered, with neighboring slides peeking in at the edges. Used by
// the Slider Block and directly by the Location template's own slider
// field. $images is a Structure of {image, alt} rows (see fields/buttons.yml
// for the analogous pattern — this one isn't extracted as a field preset
// since the block also needs its own Layout-tab wrapper around it).
$images ??= null;
if (!$images || $images->count() === 0) return;
?>
<?php // px-4 = container gutter — how close the carousel gets to the
// screen edges. Smaller value = more edge-to-edge. ?>
<div class="relative z-10 w-full px-4">
  <div class="js-slider js-peek-slider swiper">
    <div class="swiper-wrapper">
      <?php foreach ($images as $item): ?>
        <?php if (!$img = $item->image()->toFile()) continue ?>
        <?php // Slide width = min(1220px, 60vw): 60vw of the viewport,
        // capped at 1220px on wide screens. Raise 1220px for a bigger
        // cap; raise/lower 60vw to change how much width the active
        // slide takes at any screen size (lower vw = more neighbor
        // peek, higher vw = less). See src/main.js's `spaceBetween` for
        // the gap between slides. ?>
        <div class="swiper-slide w-[min(1000px,60vw)]">
          <?php // aspect-[16/9] = image frame ratio (width/height) —
          // swap for aspect-[4/3], aspect-[1/1], aspect-[3/4], etc. ?>
          <div class="rounded-sm overflow-hidden aspect-[16/9] shadow-lg">
            <img src="<?= $img->crop(1600, 900)->url() ?>" alt="<?= $item->alt()->esc() ?>" class="w-full h-full object-cover" loading="lazy">
          </div>
        </div>
      <?php endforeach ?>
    </div>
    <div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>
  </div>
</div>
