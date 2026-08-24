<?php snippet('header') ?>

<main class="flex-1 w-full pt-32">

  <section class="max-w-4xl mx-auto px-4 pb-10">
    <?php if ($parent = $page->parent()): ?>
      <a href="<?= $parent->url() ?>" class="text-sm text-ink-soft hover:text-ink transition-colors">&larr; <?= $parent->title() ?></a>
    <?php endif ?>

    <h1 class="text-3xl sm:text-4xl font-title mt-4 mb-2">
      <?= $page->title() ?>
      <?php if ($page->roomNumber()->isNotEmpty()): ?>
        <span class="text-ink-soft font-normal text-2xl">(<?= $page->roomNumber() ?>)</span>
      <?php endif ?>
    </h1>
    <?php if ($page->subtitle()->isNotEmpty()): ?>
      <p class="text-lg text-ink-soft"><?= $page->subtitle() ?></p>
    <?php endif ?>
  </section>

  <?php if ($page->gallery()->toFiles()->count()): ?>
    <section class="max-w-site mx-auto px-4 pb-10">
      <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
        <?php foreach ($page->gallery()->toFiles() as $img): ?>
          <img src="<?= $img->url() ?>" alt="" class="rounded-xl aspect-[4/3] object-cover w-full">
        <?php endforeach ?>
      </div>
    </section>
  <?php endif ?>

  <section class="max-w-4xl mx-auto px-4 py-10 grid gap-10 sm:grid-cols-3">
    <div class="sm:col-span-2 prose max-w-none text-ink-soft">
      <?= $page->description()->kt() ?>
    </div>

    <?php if ($page->features()->isNotEmpty()): ?>
      <ul class="text-sm space-y-2 text-ink-soft">
        <?php foreach ($page->features()->split() as $feature): ?>
          <li class="flex items-center gap-2">
            <span class="w-1.5 h-1.5 rounded-full bg-primary shrink-0"></span>
            <span><?= $feature ?></span>
          </li>
        <?php endforeach ?>
      </ul>
    <?php endif ?>
  </section>

  <section class="bg-ink text-white">
    <div class="max-w-3xl mx-auto px-4 py-16 text-center">
      <button type="button" data-open-lead-modal class="inline-flex items-center rounded-full bg-primary px-8 py-3.5 text-white font-medium hover:bg-primary-strong transition-colors">
        <?= $page->ctaLabel()->or(t('form.submit')) ?>
      </button>
    </div>
  </section>

  <?php if ($parent = $page->parent()): ?>
    <?php snippet('faq-widget', ['category' => $parent->slug()]) ?>
  <?php endif ?>

</main>

<?php snippet('footer') ?>
