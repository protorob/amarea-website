<?php
$navItems = $site->children()->listed();
$hasHero  = $page->hasHero()->toBool();
$langCode = $kirby->language()?->code() ?? 'en';
?>
<!DOCTYPE html>
<html lang="<?= $langCode ?>">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $page->title() ?> — <?= $site->title() ?></title>
  <link rel="stylesheet" href="<?= url('assets/css/main.css') ?>">
</head>
<body class="min-h-screen flex flex-col bg-white font-sans text-ink antialiased<?= $hasHero ? ' has-hero' : '' ?>">

<header
  id="site-header"
  data-has-hero="<?= $hasHero ? 'true' : 'false' ?>"
  class="fixed top-0 inset-x-0 z-50 transition-colors duration-300 <?= $hasHero ? 'bg-transparent text-white' : 'bg-white text-ink shadow-sm' ?>"
>
  <div class="max-w-6xl mx-auto px-4 h-20 flex items-center justify-between gap-6">

    <a href="<?= $site->url() ?>" class="shrink-0">
      <img
        src="<?= url('assets/images/amarea-logo-textonly-' . ($hasHero ? 'white' : 'blue') . '.svg') ?>"
        data-logo-light="<?= url('assets/images/amarea-logo-textonly-white.svg') ?>"
        data-logo-dark="<?= url('assets/images/amarea-logo-textonly-blue.svg') ?>"
        alt="<?= $site->title() ?>"
        class="h-7 w-auto"
      >
    </a>

    <nav class="hidden lg:flex items-center gap-8 text-sm font-medium">
      <?php foreach ($navItems as $item): ?>
        <a href="<?= $item->url() ?>" class="hover:opacity-70 transition-opacity <?= $item->isActive() ? '' : 'opacity-90' ?>">
          <?= $item->title() ?>
        </a>
      <?php endforeach ?>

      <div class="flex items-center gap-1 text-xs uppercase tracking-wide opacity-80">
        <?php foreach ($kirby->languages() as $language): ?>
          <a
            href="<?= $page->url($language->code()) ?>"
            class="px-1.5 py-1 hover:opacity-100 transition-opacity <?= $language->code() === $langCode ? 'font-semibold underline underline-offset-4' : '' ?>"
          ><?= $language->code() ?></a>
        <?php endforeach ?>
      </div>

      <button type="button" data-open-lead-modal class="inline-flex items-center rounded-full bg-primary px-5 py-2.5 text-white hover:bg-primary-strong transition-colors">
        <?= t('nav.startBooking') ?>
      </button>
    </nav>

    <button id="menu-toggle" class="lg:hidden p-2" aria-label="Toggle menu" aria-expanded="false" aria-controls="mobile-menu">
      <span class="block w-5 h-px bg-current mb-1.5"></span>
      <span class="block w-5 h-px bg-current mb-1.5"></span>
      <span class="block w-5 h-px bg-current"></span>
    </button>
  </div>

  <nav id="mobile-menu" class="lg:hidden bg-white text-ink border-t border-line opacity-0 -translate-y-1 pointer-events-none transition-all duration-200">
    <div class="px-4 py-4 flex flex-col gap-4 text-sm">
      <?php foreach ($navItems as $item): ?>
        <a href="<?= $item->url() ?>" class="<?= $item->isActive() ? 'font-semibold' : '' ?>">
          <?= $item->title() ?>
        </a>
      <?php endforeach ?>

      <div class="flex items-center gap-3 text-xs uppercase tracking-wide text-ink-soft">
        <?php foreach ($kirby->languages() as $language): ?>
          <a href="<?= $page->url($language->code()) ?>" class="<?= $language->code() === $langCode ? 'font-semibold underline underline-offset-4' : '' ?>">
            <?= $language->code() ?>
          </a>
        <?php endforeach ?>
      </div>

      <button type="button" data-open-lead-modal class="inline-flex justify-center rounded-full bg-primary px-5 py-2.5 text-white">
        <?= t('nav.startBooking') ?>
      </button>
    </div>
  </nav>
</header>
