<?php
// Form Block — wraps the shared lead-capture form (also used by the
// site-wide waitlist modal) plus an optional WhatsApp CTA.
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

  <div class="relative z-10 max-w-2xl mx-auto px-4" style="<?= $layout['textStyle'] ?>">
    <?php if ($block->eyebrow()->isNotEmpty() || $block->title()->isNotEmpty()): ?>
      <div class="text-center mb-8">
        <?php if ($block->eyebrow()->isNotEmpty()): ?>
          <p class="uppercase tracking-widest text-sm opacity-80 mb-2"><?= $block->eyebrow() ?></p>
        <?php endif ?>
        <?php if ($block->title()->isNotEmpty()): ?>
          <h2 class="text-3xl font-logo"><?= $block->title() ?></h2>
        <?php endif ?>
      </div>
    <?php endif ?>

    <?php if ($block->note()->isNotEmpty()): ?>
      <div class="prose max-w-none mx-auto text-center mb-8 <?= $layout['proseInvertClass'] ?>"><?= $block->note() ?></div>
    <?php endif ?>

    <?php snippet('lead-form', ['idPrefix' => 'lead-block-' . $block->id()]) ?>

    <?php if (site()->whatsapp()->isNotEmpty()): ?>
      <div class="mt-8 text-center">
        <a
          href="https://wa.me/<?= preg_replace('/[^0-9]/', '', site()->whatsapp()->value()) ?>"
          target="_blank" rel="noopener"
          class="inline-flex items-center rounded-full border border-line px-6 py-3 font-medium hover:bg-accent-soft/40 transition-colors"
        >
          <?= t('whatsapp.cta') ?>
        </a>
      </div>
    <?php endif ?>
  </div>
</section>
