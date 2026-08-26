<?php snippet('header') ?>

<main class="flex-1 w-full pt-32">

  <section class="max-w-4xl mx-auto px-4 pb-10">
    <?php if ($parent = $page->parent()): ?>
      <a href="<?= $parent->url() ?>" class="text-sm text-ink-soft hover:text-ink transition-colors">&larr; <?= $parent->title() ?></a>
    <?php endif ?>

    <h1 class="text-3xl sm:text-4xl font-title mt-4 mb-2">
      <?= $page->title() ?>
      <?php if ($page->roomNumber()->isNotEmpty()): ?>
        <span class="text-ink-soft font-normal text-2xl">(<?= $page->roomNumber() ?>)</span>
      <?php endif ?>
    </h1>
    <?php if ($page->subtitle()->isNotEmpty()): ?>
      <p class="text-lg text-ink-soft"><?= $page->subtitle() ?></p>
    <?php endif ?>
  </section>

  <?php $mainImage = $page->mainImage()->toFile() ?>
  <?php $gallery = $page->gallery()->toFiles() ?>
  <?php if ($mainImage || $gallery->count()): ?>
    <section class="max-w-site mx-auto px-4 pb-10">
      <?php if ($mainImage): ?>
        <a href="<?= $mainImage->url() ?>" class="js-lightbox block rounded-2xl overflow-hidden aspect-[16/9] mb-4" data-glightbox>
          <img src="<?= $mainImage->url() ?>" alt="" class="w-full h-full object-cover">
        </a>
      <?php endif ?>
      <?php if ($gallery->count()): ?>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
          <?php foreach ($gallery as $img): ?>
            <a href="<?= $img->url() ?>" class="js-lightbox block" data-glightbox>
              <img src="<?= $img->url() ?>" alt="" class="rounded-xl aspect-[4/3] object-cover w-full">
            </a>
          <?php endforeach ?>
        </div>
      <?php endif ?>
    </section>
  <?php endif ?>

  <section class="max-w-4xl mx-auto px-4 py-10 grid gap-10 sm:grid-cols-3">
    <div class="sm:col-span-2 prose max-w-none text-ink-soft">
      <?= $page->description() ?>
    </div>

    <?php $featureLabels = $page->features()->split() ?>
    <?php if (count($featureLabels)): ?>
      <?php $featureCatalog = site()->unitFeatures()->toStructure() ?>
      <ul class="text-sm space-y-2 text-ink-soft">
        <?php foreach ($featureLabels as $label): ?>
          <?php $icon = $featureCatalog->filterBy('label', $label)->first()?->icon()->value() ?>
          <li class="flex items-center gap-2">
            <?php if ($icon): ?>
              <span><?= $icon ?></span>
            <?php else: ?>
              <span class="w-1.5 h-1.5 rounded-full bg-primary shrink-0"></span>
            <?php endif ?>
            <span><?= $label ?></span>
          </li>
        <?php endforeach ?>
      </ul>
    <?php endif ?>
  </section>

  <section class="bg-ink text-white">
    <div class="max-w-3xl mx-auto px-4 py-16 text-center">
      <button type="button" data-open-lead-modal class="inline-flex items-center rounded-full bg-primary px-8 py-3.5 text-white font-medium hover:bg-primary-strong transition-colors">
        <?= $page->ctaLabel()->or(t('form.submit')) ?>
      </button>
    </div>
  </section>

  <?php if ($parent && $parent->spaces()->toStructure()->count()): ?>
    <section class="bg-accent-soft/40">
      <div class="max-w-site mx-auto px-4 py-16">
        <?php if ($parent->spacesTitle()->isNotEmpty()): ?>
          <h2 class="text-2xl font-title mb-10 text-center"><?= $parent->spacesTitle() ?></h2>
        <?php endif ?>
        <?php
        $spaceItems = [];
        foreach ($parent->spaces()->toStructure() as $space) {
          $spaceItems[] = [
            'image' => $space->image()->toFile(),
            'title' => $space->title()->value(),
            'text'  => $space->description()->value(),
          ];
        }
        ?>
        <?php snippet('partials/hover-card-grid', ['items' => $spaceItems, 'columns' => 3]) ?>
      </div>
    </section>
  <?php endif ?>

  <?php if ($parent && $parent->amenities()->toStructure()->count()): ?>
    <section class="max-w-4xl mx-auto px-4 py-16">
      <?php if ($parent->amenitiesTitle()->isNotEmpty()): ?>
        <h2 class="text-2xl font-title mb-8 text-center"><?= $parent->amenitiesTitle() ?></h2>
      <?php endif ?>
      <ul class="grid gap-3 sm:grid-cols-2 text-sm">
        <?php foreach ($parent->amenities()->toStructure() as $amenity): ?>
          <li class="flex items-center gap-2">
            <span><?= $amenity->icon() ?></span>
            <span><?= $amenity->text() ?></span>
          </li>
        <?php endforeach ?>
      </ul>
    </section>
  <?php endif ?>

  <?php if ($parent): ?>
    <?php snippet('faq-widget', ['category' => $parent->slug()]) ?>
  <?php endif ?>

</main>

<?php snippet('footer') ?>
