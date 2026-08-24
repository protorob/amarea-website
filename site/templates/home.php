<?php snippet('header') ?>

<?php if ($page->hasHero()->toBool()): ?>
  <?php snippet('hero', [
    'eyebrow'            => $page->heroEyebrow()->value(),
    'title'              => $page->heroTitle()->value(),
    'text'               => $page->heroText()->kt(),
    'video'              => $page->heroVideo()->toFile(),
    'image'              => $page->heroPoster()->toFile(),
    'ctaPrimaryLabel'    => t('cta.joinWaitlist'),
    'ctaSecondaryLabel'  => $page->ctaSecondaryLabel()->value(),
    'ctaSecondaryUrl'    => $page->ctaSecondaryTarget()->value(),
  ]) ?>
<?php endif ?>

<main class="flex-1 w-full">

  <section id="intro" class="max-w-4xl mx-auto px-4 py-20 text-center">
    <h2 class="text-3xl font-title mb-6"><?= $page->introTitle() ?></h2>
    <div class="prose max-w-none mx-auto text-ink-soft"><?= $page->introText()->kt() ?></div>
  </section>

  <?php if ($page->teaserText()->isNotEmpty()): ?>
    <section class="max-w-site mx-auto px-4 py-16 grid gap-10 lg:grid-cols-2 items-center">
      <?php if ($img = $page->teaserImage()->toFile()): ?>
        <img src="<?= $img->url() ?>" alt="" class="rounded-2xl w-full h-full object-cover aspect-[4/3]">
      <?php endif ?>
      <div>
        <h2 class="text-2xl font-title mb-4"><?= $page->teaserTitle() ?></h2>
        <div class="prose max-w-none text-ink-soft"><?= $page->teaserText()->kt() ?></div>
        <a href="<?= $site->find('locations')?->url() ?>" class="inline-flex items-center mt-6 text-primary font-medium hover:underline">
          <?= t('cta.discover') ?>
        </a>
      </div>
    </section>
  <?php endif ?>

  <?php if ($page->whyAmarea()->toStructure()->count()): ?>
    <section class="bg-accent-soft/40">
      <div class="max-w-site mx-auto px-4 py-20 grid gap-10 sm:grid-cols-2 lg:grid-cols-4">
        <?php foreach ($page->whyAmarea()->toStructure() as $feature): ?>
          <div>
            <h3 class="font-semibold text-lg mb-2"><?= $feature->title() ?></h3>
            <p class="text-sm text-ink-soft"><?= $feature->text() ?></p>
          </div>
        <?php endforeach ?>
      </div>
    </section>
  <?php endif ?>

  <?php if ($page->showUgcFeed()->toBool() && $site->elfsightWidgetClass()->isNotEmpty()): ?>
    <section class="max-w-site mx-auto px-4 py-16">
      <div class="<?= $site->elfsightWidgetClass() ?>" data-elfsight-app-lazy></div>
    </section>
  <?php endif ?>

  <?php if ($page->communityText()->isNotEmpty()): ?>
    <section class="max-w-site mx-auto px-4 py-16">
      <h2 class="text-2xl font-title mb-4"><?= $page->communityTitle() ?></h2>
      <div class="prose max-w-none text-ink-soft mb-8"><?= $page->communityText()->kt() ?></div>

      <?php if ($page->communityGallery()->toFiles()->count()): ?>
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mb-8">
          <?php foreach ($page->communityGallery()->toFiles() as $img): ?>
            <img src="<?= $img->url() ?>" alt="" class="rounded-xl aspect-square object-cover w-full">
          <?php endforeach ?>
        </div>
      <?php endif ?>

      <?php if ($site->instagramUrl()->isNotEmpty()): ?>
        <a href="<?= $site->instagramUrl() ?>" target="_blank" rel="noopener" class="inline-flex items-center text-primary font-medium hover:underline">
          <?= $page->instagramCtaLabel() ?>
        </a>
      <?php endif ?>
    </section>
  <?php endif ?>

  <section class="bg-ink text-white">
    <div class="max-w-4xl mx-auto px-4 py-20 text-center">
      <h2 class="text-2xl sm:text-3xl font-title mb-4"><?= $page->waitlistTitle() ?></h2>
      <?php if ($page->waitlistText()->isNotEmpty()): ?>
        <div class="prose prose-invert max-w-none mx-auto mb-8"><?= $page->waitlistText()->kt() ?></div>
      <?php endif ?>
      <button type="button" data-open-lead-modal class="inline-flex items-center rounded-full bg-primary px-8 py-3.5 text-white font-medium hover:bg-primary-strong transition-colors">
        <?= t('cta.joinWaitlist') ?>
      </button>
    </div>
  </section>

</main>

<?php snippet('footer') ?>
