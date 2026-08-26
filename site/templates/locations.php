<?php snippet('header') ?>

<main class="flex-1 w-full">

  <?php snippet('page-hero') ?>

  <?php if ($page->subTitle()->isNotEmpty() || $page->description()->isNotEmpty()): ?>
    <section class="max-w-3xl mx-auto px-4 pt-16 pb-16 text-center">
      <?php if ($page->subTitle()->isNotEmpty()): ?>
        <h2 class="text-2xl sm:text-3xl font-title mb-6"><?= $page->subTitle() ?></h2>
      <?php endif ?>
      <?php if ($page->description()->isNotEmpty()): ?>
        <div class="prose max-w-none mx-auto text-ink-soft"><?= $page->description() ?></div>
      <?php endif ?>
    </section>
  <?php endif ?>

  <?php
  $locations = $page->children()->listed()->filterBy('intendedTemplate', 'location');
  $items = [];
  foreach ($locations as $location) {
    $items[] = [
      'image'       => $location->heroImage()->toFile(),
      'title'       => $location->title()->value(),
      'text'        => $location->shortDescription()->or($location->subtitle())->value(),
      'url'         => $location->url(),
      'buttonLabel' => t('cta.discover'),
    ];
  }
  ?>
  <?php if (count($items)): ?>
    <section class="max-w-site mx-auto px-4 pb-20">
      <?php snippet('partials/hover-card-grid', ['items' => $items, 'columns' => 2]) ?>
    </section>
  <?php endif ?>

</main>

<?php snippet('footer') ?>
