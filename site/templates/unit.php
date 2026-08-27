<?php snippet('header') ?>

<main class="flex-1 w-full pt-32">

  <?php $parent = $page->parent() ?>
  <?php $mainImage = $page->mainImage()->toFile() ?>
  <?php $gallery = $page->gallery()->toFiles() ?>
  <?php $thumbnails = $gallery->limit(4) ?>
  <?php $featureLabels = $page->features()->split() ?>
  <?php $featureCatalog = site()->unitFeatures()->toStructure() ?>

  <section class="max-w-site mx-auto px-4 pb-16">
    <?php if ($parent): ?>
      <a href="<?= $parent->url() ?>" class="inline-block mb-6 text-sm text-ink-soft hover:text-ink transition-colors">&larr; Back to <?= $parent->title() ?></a>
    <?php endif ?>

    <div class="grid gap-10 lg:grid-cols-2 items-start">
      <?php if ($mainImage || $thumbnails->count()): ?>
        <div>
          <?php if ($mainImage): ?>
            <a href="<?= $mainImage->resize(2400)->url() ?>" class="js-lightbox block rounded-2xl overflow-hidden aspect-[4/3] mb-4" data-glightbox>
              <img src="<?= $mainImage->crop(1400, 1050)->url() ?>" alt="" class="w-full h-full object-cover">
            </a>
          <?php endif ?>
          <?php if ($thumbnails->count()): ?>
            <div class="grid grid-cols-4 gap-4">
              <?php foreach ($thumbnails as $img): ?>
                <a href="<?= $img->resize(2400)->url() ?>" class="js-lightbox block" data-glightbox>
                  <img src="<?= $img->crop(400, 400)->url() ?>" alt="" class="rounded-xl aspect-square object-cover w-full" loading="lazy">
                </a>
              <?php endforeach ?>
            </div>
          <?php endif ?>
        </div>
      <?php endif ?>

      <div>
        <h1 class="text-3xl sm:text-4xl font-title mb-2">
          <?= $page->title() ?>
          <?php if ($page->roomNumber()->isNotEmpty()): ?>
            <span class="text-ink-soft font-normal text-2xl">(<?= $page->roomNumber() ?>)</span>
          <?php endif ?>
        </h1>
        <?php if ($page->subtitle()->isNotEmpty()): ?>
          <p class="text-lg text-ink-soft mb-4"><?= $page->subtitle() ?></p>
        <?php endif ?>
        <?php if ($page->description()->isNotEmpty()): ?>
          <div class="prose max-w-none text-ink-soft mb-6"><?= $page->description() ?></div>
        <?php endif ?>

        <?php if (count($featureLabels)): ?>
          <h2 class="font-title text-xl mb-3">Room features</h2>
          <div class="flex flex-wrap gap-3 mb-8">
            <?php foreach ($featureLabels as $label): ?>
              <?php $icon = $featureCatalog->filterBy('label', $label)->first()?->icon()->value() ?>
              <span class="inline-flex items-center gap-2 rounded-full border border-line px-4 py-2 text-sm">
                <?php if ($icon): ?><span><?= $icon ?></span><?php endif ?>
                <span><?= $label ?></span>
              </span>
            <?php endforeach ?>
          </div>
        <?php endif ?>

        <button type="button" data-open-lead-modal class="inline-flex items-center gap-2 rounded-full bg-primary px-8 py-3.5 text-white font-medium hover:bg-primary-strong transition-colors">
          <?= $page->ctaLabel()->or(t('form.submit')) ?>
        </button>
      </div>
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
