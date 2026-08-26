<?php
// Slider Block — centered "peek" carousel (Swiper): the active photo reads
// large and centered, with neighboring slides peeking in at the edges.
// Each slide also opens in the shared GLightbox lightbox.
$images = $block->images()->toStructure();
if ($images->count() === 0) return;

$layout = block_layout($block);
?>
<section class="relative w-full overflow-hidden <?= $layout['sectionClass'] ?>">
  <?php if ($layout['video']): ?>
    <video class="absolute inset-0 h-full w-full object-cover" autoplay muted loop playsinline>
      <source src="<?= $layout['video']->url() ?>" type="<?= $layout['video']->mime() ?>">
    </video>
    <div class="absolute inset-0 <?= $layout['overlayClass'] ?>"></div>
  <?php elseif ($layout['image']): ?>
    <img src="<?= $layout['image']->url() ?>" alt="" class="absolute inset-0 h-full w-full object-cover">
    <div class="absolute inset-0 <?= $layout['overlayClass'] ?>"></div>
  <?php endif ?>

  <div class="relative z-10 w-full px-4">
    <div class="js-slider js-peek-slider swiper">
      <div class="swiper-wrapper">
        <?php foreach ($images as $item): ?>
          <?php if (!$img = $item->image()->toFile()) continue ?>
          <div class="swiper-slide w-[min(1220px,78vw)]">
            <a href="<?= $img->url() ?>" class="js-lightbox block rounded-2xl overflow-hidden aspect-[4/3] shadow-lg" data-glightbox="description: <?= $item->alt()->esc() ?>">
              <img src="<?= $img->url() ?>" alt="<?= $item->alt()->esc() ?>" class="w-full h-full object-cover">
            </a>
          </div>
        <?php endforeach ?>
      </div>
      <div class="swiper-button-prev"></div>
      <div class="swiper-button-next"></div>
    </div>
  </div>
</section>
