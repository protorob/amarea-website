<?php snippet('header') ?>

<main class="flex-1 w-full pt-32">

  <section class="max-w-3xl mx-auto px-4 pb-16 text-center">
    <h1 class="text-3xl sm:text-4xl font-title mb-6"><?= $page->introTitle() ?></h1>
    <div class="prose max-w-none mx-auto text-ink-soft"><?= $page->introText()->kt() ?></div>
  </section>

  <?php if ($page->gallery()->toFiles()->count()): ?>
    <section class="max-w-6xl mx-auto px-4 pb-16">
      <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <?php foreach ($page->gallery()->toFiles() as $img): ?>
          <img src="<?= $img->url() ?>" alt="" class="rounded-xl aspect-square object-cover w-full">
        <?php endforeach ?>
      </div>
    </section>
  <?php endif ?>

  <?php if ($page->showUgcFeed()->toBool() && $site->elfsightWidgetClass()->isNotEmpty()): ?>
    <section class="max-w-6xl mx-auto px-4 py-16">
      <div class="<?= $site->elfsightWidgetClass() ?>" data-elfsight-app-lazy></div>
    </section>
  <?php endif ?>

  <?php if ($page->activities()->toStructure()->count()): ?>
    <section class="bg-accent-soft/40">
      <div class="max-w-4xl mx-auto px-4 py-16">
        <h2 class="text-2xl font-title mb-2 text-center">Weekly Activity Ideas</h2>
        <p class="text-sm text-ink-soft text-center mb-8">
          Not included in the stay — selected together based on availability.
        </p>
        <ul class="grid gap-3 sm:grid-cols-2 text-sm">
          <?php foreach ($page->activities()->toStructure() as $activity): ?>
            <li class="flex items-center gap-2">
              <span class="w-1.5 h-1.5 rounded-full bg-primary shrink-0"></span>
              <span><?= $activity->name() ?></span>
            </li>
          <?php endforeach ?>
        </ul>
      </div>
    </section>
  <?php endif ?>

  <?php if ($page->calendarEmbed()->isNotEmpty()): ?>
    <section class="max-w-4xl mx-auto px-4 py-16">
      <?= $page->calendarEmbed()->value() ?>
    </section>
  <?php endif ?>

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
