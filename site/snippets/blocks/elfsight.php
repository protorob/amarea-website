<?php
// Elfsight Block — thin wrapper around the site-wide Elfsight widget class
// (Settings → Integrations). Renders nothing if that's not configured.
if (site()->elfsightWidgetClass()->isEmpty()) return;
?>
<section class="max-w-site mx-auto px-4 py-16">
  <?php if ($block->eyebrow()->isNotEmpty() || $block->title()->isNotEmpty()): ?>
    <div class="max-w-2xl mx-auto text-center mb-10">
      <?php if ($block->eyebrow()->isNotEmpty()): ?>
        <p class="uppercase tracking-widest text-sm text-accent mb-2"><?= $block->eyebrow() ?></p>
      <?php endif ?>
      <?php if ($block->title()->isNotEmpty()): ?>
        <h2 class="text-3xl font-logo"><?= $block->title() ?></h2>
      <?php endif ?>
    </div>
  <?php endif ?>

  <div class="<?= site()->elfsightWidgetClass() ?>" data-elfsight-app-lazy></div>
</section>
