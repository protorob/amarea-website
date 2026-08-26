<?php
// Hero Block — full-bleed section reused across Home + the Default
// blueprint's Blocks field. Empty background + no content behaves as a
// plain spacer between other blocks, per the v2 build notes.

$bgColorClasses = [
  'ink'     => 'bg-ink',
  'bg'      => 'bg-bg',
  'primary' => 'bg-primary',
  'accent'  => 'bg-accent',
];
$overlayClasses = [
  'ink'    => 'bg-gradient-to-t from-ink/70 via-ink/20 to-ink/40',
  'accent' => 'bg-gradient-to-t from-accent/70 via-accent/20 to-accent/40',
  'none'   => '',
];
$textColorClasses = [
  'white' => 'text-white',
  'ink'   => 'text-ink',
  'bg'    => 'text-bg',
];
$heightClasses = [
  'normal' => 'py-20 sm:py-24',
  'half'   => 'min-h-[50vh] py-20 flex items-center',
  'full'   => 'min-h-screen py-24 flex items-center',
];
$backgroundType = $block->backgroundType()->value() ?: 'color';
$image          = $backgroundType === 'image' ? $block->backgroundImage()->toFile() : null;
$video          = $backgroundType === 'video' ? $block->backgroundVideo()->toFile() : null;
$icon           = $block->icon()->toFile();
$textColor      = $block->textColor()->value() ?: 'white';
$buttons        = $block->buttons()->toStructure();
$hasContent     = $block->eyebrow()->isNotEmpty() || $block->title()->isNotEmpty() || $block->description()->isNotEmpty() || $buttons->count() || $icon;
?>
<section class="relative w-full overflow-hidden <?= $backgroundType === 'color' ? ($bgColorClasses[$block->backgroundColor()->value()] ?? $bgColorClasses['ink']) : 'bg-ink' ?> <?= $heightClasses[$block->height()->value()] ?? $heightClasses['normal'] ?>">
  <?php if ($video): ?>
    <video class="absolute inset-0 h-full w-full object-cover" autoplay muted loop playsinline>
      <source src="<?= $video->url() ?>" type="<?= $video->mime() ?>">
    </video>
    <div class="absolute inset-0 <?= $overlayClasses[$block->overlayColor()->value() ?: 'ink'] ?>"></div>
  <?php elseif ($image): ?>
    <img src="<?= $image->url() ?>" alt="" class="absolute inset-0 h-full w-full object-cover">
    <div class="absolute inset-0 <?= $overlayClasses[$block->overlayColor()->value() ?: 'ink'] ?>"></div>
  <?php endif ?>

  <?php if ($hasContent): ?>
    <div class="relative z-10 w-full max-w-4xl mx-auto px-4 flex flex-col items-center gap-6 text-center <?= $textColorClasses[$textColor] ?? $textColorClasses['white'] ?>">
      <?php if ($icon): ?>
        <img src="<?= $icon->url() ?>" alt="" class="h-10 w-auto">
      <?php endif ?>

      <?php if ($block->eyebrow()->isNotEmpty()): ?>
        <p class="uppercase tracking-widest text-sm opacity-80"><?= $block->eyebrow() ?></p>
      <?php endif ?>

      <?php if ($block->title()->isNotEmpty()): ?>
        <h2 class="text-3xl sm:text-4xl lg:text-5xl font-logo leading-tight"><?= $block->title() ?></h2>
      <?php endif ?>

      <?php if ($block->description()->isNotEmpty()): ?>
        <div class="text-lg opacity-90 max-w-2xl prose prose-p:my-0 <?= $textColor === 'white' ? 'prose-invert' : '' ?>"><?= $block->description() ?></div>
      <?php endif ?>

      <?php snippet('partials/buttons', ['buttons' => $buttons]) ?>
    </div>
  <?php endif ?>
</section>
