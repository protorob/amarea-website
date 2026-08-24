<?php snippet('header') ?>

<main class="flex-1 w-full pt-32">

  <section class="max-w-3xl mx-auto px-4 pb-10 text-center">
    <h1 class="text-3xl sm:text-4xl font-title mb-6"><?= $page->title() ?></h1>
    <?php if ($page->introText()->isNotEmpty()): ?>
      <div class="prose max-w-none mx-auto text-ink-soft"><?= $page->introText()->kt() ?></div>
    <?php endif ?>
  </section>

  <section class="max-w-3xl mx-auto px-4 pb-20">
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
