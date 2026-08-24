<?php snippet('header') ?>

<main class="flex-1 w-full">

  <?php snippet('page-hero') ?>

  <section class="max-w-2xl mx-auto px-4 pt-16 pb-20">
    <h2 class="text-3xl sm:text-4xl font-title mb-4 text-center"><?= $page->introTitle() ?></h2>
    <?php if ($page->introText()->isNotEmpty()): ?>
      <div class="prose max-w-none mx-auto text-ink-soft text-center mb-10"><?= $page->introText()->kt() ?></div>
    <?php endif ?>

    <?php snippet('lead-form', ['idPrefix' => 'lead-page']) ?>

    <?php if ($site->whatsapp()->isNotEmpty()): ?>
      <div class="mt-8 text-center">
        <a
          href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $site->whatsapp()->value()) ?>"
          target="_blank" rel="noopener"
          class="inline-flex items-center rounded-full border border-line px-6 py-3 font-medium hover:bg-accent-soft/40 transition-colors"
        >
          <?= t('whatsapp.cta') ?>
        </a>
      </div>
    <?php endif ?>
  </section>

</main>

<?php snippet('footer') ?>
