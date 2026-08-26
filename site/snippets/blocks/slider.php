<?php
// Slider Block — Swiper-powered image carousel, initialized in main.js.
// Each slide also opens in the shared GLightbox lightbox.
$images = $block->images()->toStructure();
if ($images->count() === 0) return;
?>
<section class="max-w-site mx-auto px-4 py-12">
  <div class="js-slider swiper">
    <div class="swiper-wrapper">
      <?php foreach ($images as $item): ?>
        <?php if (!$img = $item->image()->toFile()) continue ?>
        <div class="swiper-slide">
          <a href="<?= $img->url() ?>" class="js-lightbox block" data-glightbox="description: <?= $item->alt()->esc() ?>">
            <img src="<?= $img->url() ?>" alt="<?= $item->alt()->esc() ?>" class="w-full h-full object-cover">
          </a>
        </div>
      <?php endforeach ?>
    </div>
    <div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>
  </div>
</section>
