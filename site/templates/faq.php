<?php snippet('header') ?>

<main class="flex-1 w-full">

  <?php snippet('page-hero') ?>

  <?php if ($page->introText()->isNotEmpty()): ?>
    <section class="max-w-3xl mx-auto px-4 pt-16 pb-10 text-center">
      <div class="prose max-w-none mx-auto text-ink-soft"><?= $page->introText()->kt() ?></div>
    </section>
  <?php endif ?>

  <section class="max-w-3xl mx-auto px-4 <?= $page->introText()->isNotEmpty() ? '' : 'pt-16 ' ?>pb-20">
    <?php snippet('faq-widget') ?>
  </section>

  <section class="bg-ink text-white">
    <div class="max-w-3xl mx-auto px-4 py-16 text-center">
      <h2 class="text-2xl font-title mb-6">Have another question?</h2>
      <a href="<?= $site->find('contact')?->url() ?>" class="inline-flex items-center rounded-full bg-primary px-8 py-3.5 text-white font-medium hover:bg-primary-strong transition-colors">
        <?= t('cta.sendMessage') ?>
      </a>
    </div>
  </section>

</main>

<?php snippet('footer') ?>
