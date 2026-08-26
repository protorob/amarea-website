<?php
// Highlights Block — icon+title+text card carousel (e.g. Home's "Why
// A'Marea"). Swiper-powered, 1 card per view on mobile / 3 on desktop.
$bgColorClasses = [
  'none'   => '',
  'ink'    => 'bg-ink',
  'accent' => 'bg-accent',
  'bg'     => 'bg-bg',
];
$textColorClasses = [
  'none'   => 'text-ink',
  'ink'    => 'text-white',
  'accent' => 'text-white',
  'bg'     => 'text-ink',
];
$bgKey  = $block->backgroundColor()->value() ?: 'none';
$items  = $block->items()->toStructure();
if ($items->count() === 0) return;
?>
<section class="w-full py-16 <?= $bgColorClasses[$bgKey] ?> <?= $textColorClasses[$bgKey] ?>">
  <div class="max-w-site mx-auto px-4">
    <?php if ($block->eyebrow()->isNotEmpty() || $block->title()->isNotEmpty() || $block->description()->isNotEmpty()): ?>
      <div class="max-w-2xl mx-auto text-center mb-12">
        <?php if ($block->eyebrow()->isNotEmpty()): ?>
          <p class="uppercase tracking-widest text-sm opacity-80 mb-2"><?= $block->eyebrow() ?></p>
        <?php endif ?>
        <?php if ($block->title()->isNotEmpty()): ?>
          <h2 class="text-3xl font-logo mb-4"><?= $block->title() ?></h2>
        <?php endif ?>
        <?php if ($block->description()->isNotEmpty()): ?>
          <div class="prose max-w-none opacity-90 <?= $textColorClasses[$bgKey] === 'text-white' ? 'prose-invert' : '' ?>"><?= $block->description() ?></div>
        <?php endif ?>
      </div>
    <?php endif ?>

    <div class="js-highlights-slider swiper">
      <div class="swiper-wrapper">
        <?php foreach ($items as $item): ?>
          <div class="swiper-slide h-auto">
            <div class="h-full rounded-2xl bg-white/10 border border-current/10 p-6">
              <?php if ($icon = $item->icon()->toFile()): ?>
                <img src="<?= $icon->url() ?>" alt="" class="h-10 w-auto mb-4">
              <?php endif ?>
              <?php if ($item->title()->isNotEmpty()): ?>
                <h3 class="font-semibold text-lg mb-2"><?= $item->title() ?></h3>
              <?php endif ?>
              <?php if ($item->text()->isNotEmpty()): ?>
                <p class="text-sm opacity-85"><?= $item->text() ?></p>
              <?php endif ?>
            </div>
          </div>
        <?php endforeach ?>
      </div>
      <div class="flex justify-center items-center gap-4 mt-8">
        <div class="swiper-button-prev !static !w-8 !h-8 !mt-0"></div>
        <div class="swiper-pagination !static !w-auto"></div>
        <div class="swiper-button-next !static !w-8 !h-8 !mt-0"></div>
      </div>
    </div>
  </div>
</section>
