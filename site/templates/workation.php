<?php snippet('header') ?>

<main class="flex-1 w-full pt-32">

  <section class="max-w-3xl mx-auto px-4 pb-16 text-center">
    <h1 class="text-3xl sm:text-4xl font-title mb-4"><?= $page->introTitle() ?></h1>
    <?php if ($page->introSubtitle()->isNotEmpty()): ?>
      <p class="text-lg text-ink-soft mb-6"><?= $page->introSubtitle() ?></p>
    <?php endif ?>
    <div class="prose max-w-none mx-auto text-ink-soft"><?= $page->introText()->kt() ?></div>

    <?php if ($page->eventManagerText()->isNotEmpty()): ?>
      <p class="mt-6 text-sm text-ink-soft"><?= $page->eventManagerText() ?></p>
    <?php endif ?>

    <?php if ($page->outboundUrl()->isNotEmpty()): ?>
      <a href="<?= $page->outboundUrl() ?>" target="_blank" rel="noopener" class="inline-flex items-center mt-4 text-primary font-medium hover:underline">
        <?= $page->outboundLabel() ?> &rarr;
      </a>
    <?php endif ?>
  </section>

  <?php if ($page->showUgcFeed()->toBool() && $site->elfsightWidgetClass()->isNotEmpty()): ?>
    <section class="max-w-6xl mx-auto px-4 py-16">
      <div class="<?= $site->elfsightWidgetClass() ?>" data-elfsight-app-lazy></div>
    </section>
  <?php endif ?>

  <section class="bg-accent-soft/40">
    <div class="max-w-5xl mx-auto px-4 py-16">
      <h2 class="text-2xl font-title mb-4 text-center"><?= $page->exclusiveTitle() ?></h2>
      <div class="prose max-w-none text-ink-soft text-center mx-auto mb-10"><?= $page->exclusiveIntro()->kt() ?></div>

      <p class="text-center font-medium mb-8">
        Exclusive use of all <?= $page->maxBedrooms() ?> bedrooms — up to <?= $page->maxGuests() ?> people
      </p>

      <?php if ($page->inclusions()->toStructure()->count()): ?>
        <ul class="grid gap-3 sm:grid-cols-2 text-sm max-w-2xl mx-auto">
          <?php foreach ($page->inclusions()->toStructure() as $inclusion): ?>
            <li class="flex items-center gap-2">
              <span class="w-1.5 h-1.5 rounded-full bg-primary shrink-0"></span>
              <span><?= $inclusion->label() ?></span>
            </li>
          <?php endforeach ?>
        </ul>
      <?php endif ?>

      <?php if ($page->gallery()->toFiles()->count()): ?>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-10">
          <?php foreach ($page->gallery()->toFiles() as $img): ?>
            <img src="<?= $img->url() ?>" alt="" class="rounded-xl aspect-square object-cover w-full">
          <?php endforeach ?>
        </div>
      <?php endif ?>
    </div>
  </section>

  <section class="bg-ink text-white">
    <div class="max-w-3xl mx-auto px-4 py-16 text-center">
      <h2 class="text-2xl font-title mb-6"><?= $page->waitlistTitle() ?></h2>
      <button type="button" data-open-lead-modal class="inline-flex items-center rounded-full bg-primary px-8 py-3.5 text-white font-medium hover:bg-primary-strong transition-colors">
        <?= t('cta.joinWaitlist') ?>
      </button>
    </div>
  </section>

  <?php snippet('faq-widget', ['category' => $page->slug()]) ?>

</main>

<?php snippet('footer') ?>
