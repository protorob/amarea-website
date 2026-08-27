<?php snippet('header') ?>

<main class="flex-1 w-full">

  <?php snippet('page-hero') ?>

  <section class="max-w-3xl mx-auto px-4 pt-16 pb-10 text-center">
    <?php if ($page->subtitle()->isNotEmpty()): ?>
      <h2 class="text-2xl sm:text-3xl font-title uppercase mb-6"><?= $page->subtitle() ?></h2>
    <?php endif ?>
    <?php if ($page->description()->isNotEmpty()): ?>
      <div class="prose max-w-none mx-auto text-ink-soft"><?= $page->description() ?></div>
    <?php endif ?>
  </section>

  <?php snippet('partials/photo-slider', ['images' => $page->sliderImages()->toStructure()]) ?>

  <?php $units = $page->children()->listed()->filterBy('intendedTemplate', 'unit') ?>
  <?php if ($units->count()): ?>
    <section id="units" class="max-w-site mx-auto px-4 py-16">
      <?php if ($page->ctaLabel()->isNotEmpty()): ?>
        <h2 class="text-2xl font-title mb-10 text-center"><?= $page->ctaLabel() ?></h2>
      <?php endif ?>

      <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
        <?php foreach ($units as $unit): ?>
          <a href="<?= $unit->url() ?>" class="group block rounded-2xl overflow-hidden bg-white border border-line hover:shadow-md transition-shadow">
            <?php if ($img = $unit->mainImage()->toFile()): ?>
              <img src="<?= $img->url() ?>" alt="" class="w-full aspect-[4/3] object-cover">
            <?php endif ?>
            <div class="p-5">
              <h3 class="font-semibold mb-1">
                <?= $unit->title() ?>
                <?php if ($unit->roomNumber()->isNotEmpty()): ?>
                  <span class="text-ink-soft font-normal">(<?= $unit->roomNumber() ?>)</span>
                <?php endif ?>
              </h3>
              <?php if ($unit->subtitle()->isNotEmpty()): ?>
                <p class="text-sm text-ink-soft"><?= $unit->subtitle() ?></p>
              <?php endif ?>
            </div>
          </a>
        <?php endforeach ?>
      </div>
    </section>
  <?php endif ?>

  <?php if ($page->spaces()->toStructure()->count()): ?>
    <section class="bg-accent-soft/40">
      <div class="max-w-site mx-auto px-4 py-16">
        <?php if ($page->spacesEyebrow()->isNotEmpty() || $page->spacesTitle()->isNotEmpty() || $page->spacesDescription()->isNotEmpty()): ?>
          <div class="max-w-2xl mx-auto text-center mb-10">
            <?php if ($page->spacesEyebrow()->isNotEmpty()): ?>
              <p class="uppercase tracking-widest text-sm text-accent mb-2"><?= $page->spacesEyebrow() ?></p>
            <?php endif ?>
            <?php if ($page->spacesTitle()->isNotEmpty()): ?>
              <h2 class="text-2xl font-title mb-4"><?= $page->spacesTitle() ?></h2>
            <?php endif ?>
            <?php if ($page->spacesDescription()->isNotEmpty()): ?>
              <div class="prose max-w-none text-ink-soft"><?= $page->spacesDescription() ?></div>
            <?php endif ?>
          </div>
        <?php endif ?>

        <?php
        $spaceItems = [];
        foreach ($page->spaces()->toStructure() as $space) {
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

  <?php if ($page->gallery()->toFiles()->count()): ?>
    <section class="max-w-site mx-auto px-4 py-16">
      <?php if ($page->galleryEyebrow()->isNotEmpty() || $page->galleryTitle()->isNotEmpty() || $page->galleryDescription()->isNotEmpty()): ?>
        <div class="max-w-2xl mx-auto text-center mb-10">
          <?php if ($page->galleryEyebrow()->isNotEmpty()): ?>
            <p class="uppercase tracking-widest text-sm text-accent mb-2"><?= $page->galleryEyebrow() ?></p>
          <?php endif ?>
          <?php if ($page->galleryTitle()->isNotEmpty()): ?>
            <h2 class="text-2xl font-title mb-4"><?= $page->galleryTitle() ?></h2>
          <?php endif ?>
          <?php if ($page->galleryDescription()->isNotEmpty()): ?>
            <div class="prose max-w-none text-ink-soft"><?= $page->galleryDescription() ?></div>
          <?php endif ?>
        </div>
      <?php endif ?>

      <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <?php foreach ($page->gallery()->toFiles() as $img): ?>
          <a href="<?= $img->url() ?>" class="js-lightbox block" data-glightbox>
            <img src="<?= $img->url() ?>" alt="" class="rounded-xl aspect-square object-cover w-full">
          </a>
        <?php endforeach ?>
      </div>
    </section>
  <?php endif ?>

  <?php if ($page->amenities()->toStructure()->count()): ?>
    <section class="max-w-4xl mx-auto px-4 py-16">
      <?php if ($page->amenitiesEyebrow()->isNotEmpty() || $page->amenitiesTitle()->isNotEmpty() || $page->amenitiesDescription()->isNotEmpty()): ?>
        <div class="max-w-2xl mx-auto text-center mb-10">
          <?php if ($page->amenitiesEyebrow()->isNotEmpty()): ?>
            <p class="uppercase tracking-widest text-sm text-accent mb-2"><?= $page->amenitiesEyebrow() ?></p>
          <?php endif ?>
          <?php if ($page->amenitiesTitle()->isNotEmpty()): ?>
            <h2 class="text-2xl font-title"><?= $page->amenitiesTitle() ?></h2>
          <?php endif ?>
          <?php if ($page->amenitiesDescription()->isNotEmpty()): ?>
            <div class="prose max-w-none text-ink-soft mt-4"><?= $page->amenitiesDescription() ?></div>
          <?php endif ?>
        </div>
      <?php endif ?>
      <ul class="grid gap-3 sm:grid-cols-2 text-sm">
        <?php foreach ($page->amenities()->toStructure() as $amenity): ?>
          <li class="flex items-center gap-2">
            <span><?= $amenity->icon() ?></span>
            <span><?= $amenity->text() ?></span>
          </li>
        <?php endforeach ?>
      </ul>
    </section>
  <?php endif ?>

  <section class="bg-ink text-white">
    <div class="max-w-3xl mx-auto px-4 py-16 text-center">
      <h2 class="text-2xl font-title mb-6"><?= $page->ctaLabel()->or('Find your place') ?></h2>
      <a href="#units" class="inline-flex items-center rounded-full bg-primary px-8 py-3.5 text-white font-medium hover:bg-primary-strong transition-colors">
        <?= $page->ctaLabel()->or('Find your place') ?>
      </a>
    </div>
  </section>

</main>

<?php snippet('footer') ?>
