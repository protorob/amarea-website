<?php
// Slider Block — see partials/photo-slider.php for the carousel itself;
// this just adds the Layout-tab background wrapper around it.
$images = $block->images()->toStructure();
if ($images->count() === 0) return;

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

  <?php snippet('partials/photo-slider', ['images' => $images]) ?>
</section>
