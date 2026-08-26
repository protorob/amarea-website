<?php
// Elfsight Block — thin wrapper around the site-wide Elfsight widget class
// (Settings → Integrations). Renders nothing if that's not configured.
if (site()->elfsightWidgetClass()->isEmpty()) return;

$layout = block_layout($block);
?>
<section class="relative w-full overflow-hidden <?= $layout['heightClass'] ?>" style="<?= $layout['backgroundStyle'] ?>">
  <?php if ($layout['video']): ?>
    <video class="absolute inset-0 h-full w-full object-cover" autoplay muted loop playsinline>
      <source src="<?= $layout['video']->url() ?>" type="<?= $layout['video']->mime() ?>">
    </video>
    <div class="absolute inset-0 <?= $layout['overlayClass'] ?>"></div>
  <?php elseif ($layout['image']): ?>
    <img src="<?= $layout['image']->url() ?>" alt="" class="absolute inset-0 h-full w-full object-cover">
    <div class="absolute inset-0 <?= $layout['overlayClass'] ?>"></div>
  <?php endif ?>

  <div class="relative z-10 max-w-site mx-auto px-4" style="<?= $layout['textStyle'] ?>">
    <?php if ($block->eyebrow()->isNotEmpty() || $block->title()->isNotEmpty() || $block->description()->isNotEmpty()): ?>
      <div class="max-w-2xl mx-auto text-center mb-10">
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

    <div class="<?= site()->elfsightWidgetClass() ?>" data-elfsight-app-lazy></div>
  </div>
</section>
