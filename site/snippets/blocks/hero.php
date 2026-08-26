<?php
// Hero Block — full-bleed section reused across Home + the Default
// blueprint's Blocks field. Empty background + no content behaves as a
// plain spacer between other blocks, per the v2 build notes.

$iconSizeClasses = [
  'small'  => 'h-8',
  'medium' => 'h-12',
  'large'  => 'h-20',
];

$layout     = block_layout($block);
$icon       = $block->icon()->toFile();
$buttons    = $block->buttons()->toStructure();
$hasContent = $block->eyebrow()->isNotEmpty() || $block->title()->isNotEmpty() || $block->description()->isNotEmpty() || $buttons->count() || $icon;
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

  <?php if ($hasContent): ?>
    <div class="relative z-10 w-full max-w-4xl mx-auto px-4 flex flex-col items-center gap-6 text-center <?= $layout['textColorClass'] ?>">
      <?php if ($icon): ?>
        <img src="<?= $icon->url() ?>" alt="" class="w-auto <?= $iconSizeClasses[$block->iconSize()->value()] ?? $iconSizeClasses['medium'] ?>">
      <?php endif ?>

      <?php if ($block->eyebrow()->isNotEmpty()): ?>
        <p class="uppercase tracking-widest text-sm opacity-80"><?= $block->eyebrow() ?></p>
      <?php endif ?>

      <?php if ($block->title()->isNotEmpty()): ?>
        <h2 class="text-3xl sm:text-4xl lg:text-5xl font-logo leading-tight"><?= $block->title() ?></h2>
      <?php endif ?>

      <?php if ($block->description()->isNotEmpty()): ?>
        <div class="text-lg opacity-90 max-w-2xl prose prose-p:my-0 <?= $layout['proseInvertClass'] ?>"><?= $block->description() ?></div>
      <?php endif ?>

      <?php snippet('partials/buttons', ['buttons' => $buttons]) ?>
    </div>
  <?php endif ?>
</section>
