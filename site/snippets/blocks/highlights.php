<?php
// Highlights Block — photo+title+text card carousel (e.g. Home's "Why
// A'Marea"). Swiper-powered, 1 card per view on mobile / 3 on desktop.
$items = $block->items()->toStructure();
if ($items->count() === 0) return;

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

  <div class="relative z-10 max-w-site mx-auto px-4 <?= $layout['textColorClass'] ?>">
    <?php if ($block->eyebrow()->isNotEmpty() || $block->title()->isNotEmpty() || $block->description()->isNotEmpty()): ?>
      <div class="max-w-2xl mx-auto text-center mb-12">
        <?php if ($block->eyebrow()->isNotEmpty()): ?>
          <p class="uppercase tracking-widest text-sm opacity-80 mb-2"><?= $block->eyebrow() ?></p>
        <?php endif ?>
        <?php if ($block->title()->isNotEmpty()): ?>
          <h2 class="text-3xl font-logo mb-4"><?= $block->title() ?></h2>
        <?php endif ?>
        <?php if ($block->description()->isNotEmpty()): ?>
          <div class="prose max-w-none opacity-90 <?= $layout['proseInvertClass'] ?>"><?= $block->description() ?></div>
        <?php endif ?>
      </div>
    <?php endif ?>

    <div class="js-highlights-slider swiper">
      <div class="swiper-wrapper">
        <?php foreach ($items as $item): ?>
          <div class="swiper-slide h-auto">
            <?php if ($photo = $item->icon()->toFile()): ?>
              <img src="<?= $photo->url() ?>" alt="" class="w-full aspect-6/4 object-cover rounded-sm mb-5">
            <?php endif ?>
            <?php if ($item->title()->isNotEmpty()): ?>
              <h3 class="text-xl mb-2"><?= $item->title() ?></h3>
            <?php endif ?>
            <?php if ($item->text()->isNotEmpty()): ?>
              <p class="text-sm opacity-85"><?= $item->text() ?></p>
            <?php endif ?>
          </div>
        <?php endforeach ?>
      </div>
      <div class="flex justify-center items-center gap-4 mt-8">
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
      </div>
    </div>
  </div>
</section>
