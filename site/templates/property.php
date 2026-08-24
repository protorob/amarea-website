<?php snippet('header') ?>

<main class="flex-1 w-full">

  <?php snippet('page-hero') ?>

  <section class="max-w-3xl mx-auto px-4 pt-16 pb-10 text-center">
    <?php if ($page->subtitle()->isNotEmpty()): ?>
      <h2 class="text-lg text-ink-soft mb-6"><?= $page->subtitle() ?></h2>
    <?php endif ?>
    <div class="prose max-w-none mx-auto text-ink-soft"><?= $page->introText()->kt() ?></div>
  </section>

  <?php if ($page->gallery()->toFiles()->count()): ?>
    <section class="max-w-site mx-auto px-4 pb-16">
      <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <?php foreach ($page->gallery()->toFiles() as $img): ?>
          <img src="<?= $img->url() ?>" alt="" class="rounded-xl aspect-square object-cover w-full">
        <?php endforeach ?>
      </div>
    </section>
  <?php endif ?>

  <?php if ($units = $page->children()->listed()->filterBy('intendedTemplate', 'unit')): ?>
    <?php if ($units->count()): ?>
      <section id="units" class="max-w-site mx-auto px-4 py-16">
        <?php if ($page->ctaLabel()->isNotEmpty()): ?>
          <h2 class="text-2xl font-title mb-10 text-center"><?= $page->ctaLabel() ?></h2>
        <?php endif ?>

        <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
          <?php foreach ($units as $unit): ?>
            <a href="<?= $unit->url() ?>" class="group block rounded-2xl overflow-hidden bg-white border border-line hover:shadow-md transition-shadow">
              <?php if ($img = $unit->gallery()->toFiles()->first()): ?>
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
  <?php endif ?>

  <?php if ($page->spaces()->toStructure()->count()): ?>
    <section class="bg-accent-soft/40">
      <div class="max-w-site mx-auto px-4 py-16">
        <h2 class="text-2xl font-title mb-10 text-center">The Spaces</h2>
        <div class="grid gap-10 sm:grid-cols-2 lg:grid-cols-3">
          <?php foreach ($page->spaces()->toStructure() as $space): ?>
            <div>
              <?php if ($img = $space->image()->toFile()): ?>
                <img src="<?= $img->url() ?>" alt="" class="rounded-xl aspect-[4/3] object-cover w-full mb-4">
              <?php endif ?>
              <h3 class="font-semibold mb-2"><?= $space->title() ?></h3>
              <p class="text-sm text-ink-soft"><?= $space->text() ?></p>
            </div>
          <?php endforeach ?>
        </div>
      </div>
    </section>
  <?php endif ?>

  <?php if ($page->amenities()->toStructure()->count()): ?>
    <section class="max-w-4xl mx-auto px-4 py-16">
      <h2 class="text-2xl font-title mb-8 text-center">Everything you need, already here</h2>
      <ul class="grid gap-3 sm:grid-cols-2 text-sm">
        <?php foreach ($page->amenities()->toStructure() as $amenity): ?>
          <li class="flex items-center gap-2">
            <span><?= $amenity->icon() ?></span>
            <span><?= $amenity->label() ?></span>
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

  <?php snippet('faq-widget', ['category' => $page->slug()]) ?>

</main>

<?php snippet('footer') ?>
