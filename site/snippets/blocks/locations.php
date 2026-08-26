<?php
// Locations Block — auto-lists every Location page (Beach House, City
// Apartments…) as a hover-reveal card grid. Nothing here is per-block
// configurable beyond the optional heading above the grid.
// NOTE: still filters by the 'property' template — rename alongside the
// Location Page blueprint rename in v2 Step 6.
$locations = page('locations')?->children()->listed()->filterBy('intendedTemplate', 'property');
if (!$locations || $locations->count() === 0) return;

$items = [];
foreach ($locations as $location) {
  $items[] = [
    'image'       => $location->heroImage()->toFile(),
    'title'       => $location->title()->value(),
    'text'        => $location->subtitle()->value(),
    'url'         => $location->url(),
    'buttonLabel' => t('cta.discover'),
  ];
}
?>
<section class="max-w-site mx-auto px-4 py-16">
  <?php if ($block->eyebrow()->isNotEmpty() || $block->title()->isNotEmpty() || $block->description()->isNotEmpty()): ?>
    <div class="max-w-2xl mx-auto text-center mb-10">
      <?php if ($block->eyebrow()->isNotEmpty()): ?>
        <p class="uppercase tracking-widest text-sm text-accent mb-2"><?= $block->eyebrow() ?></p>
      <?php endif ?>
      <?php if ($block->title()->isNotEmpty()): ?>
        <h2 class="text-3xl font-logo mb-4"><?= $block->title() ?></h2>
      <?php endif ?>
      <?php if ($block->description()->isNotEmpty()): ?>
        <div class="prose max-w-none text-ink-soft"><?= $block->description() ?></div>
      <?php endif ?>
    </div>
  <?php endif ?>

  <?php snippet('partials/hover-card-grid', ['items' => $items, 'columns' => 2]) ?>
</section>
