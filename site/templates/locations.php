<?php snippet('header') ?>

<main class="flex-1 w-full">

  <?php snippet('page-hero') ?>

  <section class="max-w-3xl mx-auto px-4 pt-16 pb-16 text-center">
    <h2 class="text-3xl sm:text-4xl font-title mb-6"><?= $page->introTitle() ?></h2>
    <div class="prose max-w-none mx-auto text-ink-soft"><?= $page->introText()->kt() ?></div>
  </section>

  <?php if ($page->introGallery()->toFiles()->count()): ?>
    <section class="max-w-site mx-auto px-4 pb-16">
      <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <?php foreach ($page->introGallery()->toFiles() as $img): ?>
          <img src="<?= $img->url() ?>" alt="" class="rounded-xl aspect-square object-cover w-full">
        <?php endforeach ?>
      </div>
    </section>
  <?php endif ?>

  <section class="max-w-site mx-auto px-4 py-20">
    <h2 class="text-2xl font-title mb-4 text-center"><?= $page->areaGuideTitle() ?></h2>
    <div class="grid gap-10 sm:grid-cols-2 lg:grid-cols-3 mt-10">
      <?php
      $areaBlocks = [
        'Beach days & hidden coves' => $page->beachDaysText(),
        'Syracuse & Ortigia' => $page->syracuseOrtigiaText(),
        'Baroque Sicily' => $page->baroqueSicilyText(),
        'Nature & adventure' => $page->natureAdventureText(),
        'Food, wine & local life' => $page->foodWineText(),
        'Slow days by the sea' => $page->slowDaysText(),
      ];
      ?>
      <?php foreach ($areaBlocks as $label => $text): ?>
        <?php if ($text->isNotEmpty()): ?>
          <div>
            <h3 class="font-semibold mb-2"><?= $label ?></h3>
            <p class="text-sm text-ink-soft"><?= $text ?></p>
          </div>
        <?php endif ?>
      <?php endforeach ?>
    </div>

    <?php if ($page->mapEmbed()->isNotEmpty()): ?>
      <div class="mt-12"><?= $page->mapEmbed()->value() ?></div>
    <?php endif ?>
  </section>

  <section class="bg-accent-soft/40">
    <div class="max-w-site mx-auto px-4 py-20 grid gap-8 sm:grid-cols-2">
      <?php foreach ($page->children()->listed()->filterBy('intendedTemplate', 'property') as $property): ?>
        <a href="<?= $property->url() ?>" class="group block rounded-2xl overflow-hidden bg-white shadow-sm hover:shadow-md transition-shadow">
          <?php if ($img = $property->gallery()->toFiles()->first()): ?>
            <img src="<?= $img->url() ?>" alt="" class="w-full aspect-[4/3] object-cover">
          <?php endif ?>
          <div class="p-6">
            <h3 class="text-xl font-title mb-2"><?= $property->title() ?></h3>
            <?php if ($property->subtitle()->isNotEmpty()): ?>
              <p class="text-sm text-ink-soft"><?= $property->subtitle() ?></p>
            <?php endif ?>
          </div>
        </a>
      <?php endforeach ?>
    </div>
  </section>

</main>

<?php snippet('footer') ?>
