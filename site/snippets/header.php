<?php
// Every one of these templates always renders a hero (Home's full-bleed
// video/image hero, or the page-hero.php banner on interior pages) — they
// get the transparent-over-hero / solid-on-scroll header treatment.
// Templates without a hero (currently none) render solid from the start.
$pageHeroTemplates = ['home', 'about', 'locations', 'community', 'workation', 'faq', 'contact', 'property'];
$navItems = $site->children()->listed()->not($site->find('faq'));
$hasHero  = in_array((string)$page->intendedTemplate(), $pageHeroTemplates);
$langCode = $kirby->language()?->code() ?? 'en';
?>
<!DOCTYPE html>
<html lang="<?= $langCode ?>" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $page->title() ?> — <?= $site->title() ?></title>
  <link rel="stylesheet" href="<?= url('assets/css/main.css') ?>">
  <script>
    // Anti-FOUC: only opt into the scroll-reveal hidden state (see main.css'
    // .js-reveal rules + main.js's IntersectionObserver) when JS actually
    // runs and the visitor hasn't asked for reduced motion — so content
    // never gets stuck invisible if the script fails to load or execute.
    if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
      document.documentElement.classList.add('js-reveal')
    }
  </script>
</head>
<body class="min-h-screen flex flex-col bg-bg font-sans text-ink antialiased<?= $hasHero ? ' has-hero' : '' ?>">

<header
  id="site-header"
  data-has-hero="<?= $hasHero ? 'true' : 'false' ?>"
  class="fixed top-0 inset-x-0 z-50 transition-colors duration-300 <?= $hasHero ? 'bg-transparent text-white' : 'bg-bg text-ink shadow-sm' ?>"
>
  <div id="site-header-inner" class="w-full px-6 lg:px-10 h-20 flex items-center justify-between gap-6 transition-[height] duration-300">

    <?php if ($hasHero): ?>
      <?php // Two stacked logos cross-fade on scroll — the blue one fades in
      // on a delay (see main.js) so it never shows blue while the header is
      // still mostly transparent behind it. ?>
      <a href="<?= $site->url() ?>" class="shrink-0 relative block h-7 w-auto">
        <img
          src="<?= url('assets/images/amarea-logo-textonly-white.svg') ?>"
          alt="<?= $site->title() ?>"
          data-logo-light
          class="h-7 w-auto transition-opacity duration-200"
        >
        <img
          src="<?= url('assets/images/amarea-logo-textonly-blue.svg') ?>"
          alt=""
          aria-hidden="true"
          data-logo-dark
          class="h-7 w-auto absolute inset-0 opacity-0 transition-opacity duration-200 delay-200"
        >
      </a>
    <?php else: ?>
      <a href="<?= $site->url() ?>" class="shrink-0">
        <img
          src="<?= url('assets/images/amarea-logo-textonly-blue.svg') ?>"
          alt="<?= $site->title() ?>"
          class="h-7 w-auto"
        >
      </a>
    <?php endif ?>

    <nav class="hidden lg:flex items-center gap-8 text-sm font-medium uppercase tracking-wide">
      <?php foreach ($navItems as $item): ?>
        <?php $subItems = $item->intendedTemplate() == 'locations' ? $item->children()->listed()->filterBy('intendedTemplate', 'property') : null ?>
        <?php if ($subItems && $subItems->count()): ?>
          <div class="relative group">
            <a href="<?= $item->url() ?>" class="inline-flex items-center gap-1 hover:opacity-70 transition-opacity <?= $item->isActive() ? '' : 'opacity-90' ?>">
              <?= $item->title() ?>
              <svg class="w-3 h-3" viewBox="0 0 12 12" fill="none" aria-hidden="true"><path d="M2.5 4.5l3.5 3 3.5-3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </a>
            <div class="absolute left-0 top-full pt-3 opacity-0 invisible translate-y-1 group-hover:opacity-100 group-hover:visible group-hover:translate-y-0 transition-all duration-150 z-50">
              <div class="min-w-[200px] rounded-xl bg-white text-ink shadow-lg border border-line py-2">
                <?php foreach ($subItems as $sub): ?>
                  <a href="<?= $sub->url() ?>" class="block px-4 py-2 text-sm hover:bg-accent-soft/40 transition-colors"><?= $sub->title() ?></a>
                <?php endforeach ?>
              </div>
            </div>
          </div>
        <?php else: ?>
          <a href="<?= $item->url() ?>" class="hover:opacity-70 transition-opacity <?= $item->isActive() ? '' : 'opacity-90' ?>">
            <?= $item->title() ?>
          </a>
        <?php endif ?>
      <?php endforeach ?>

      <?php snippet('lang-switcher', ['id' => 'lang-desktop']) ?>

      <button type="button" data-open-lead-modal class="inline-flex items-center rounded-full bg-primary px-5 py-2.5 text-white hover:bg-primary-strong transition-colors">
        <?= t('nav.startBooking') ?>
      </button>
    </nav>

    <div class="flex lg:hidden items-center gap-2">
      <?php snippet('lang-switcher', ['id' => 'lang-mobile']) ?>

      <button type="button" data-open-lead-modal class="inline-flex items-center rounded-full bg-primary px-4 py-2 text-sm text-white hover:bg-primary-strong transition-colors">
        <?= t('nav.startBooking') ?>
      </button>

      <button id="menu-toggle" class="p-2" aria-label="Toggle menu" aria-expanded="false" aria-controls="mobile-menu">
        <span class="block w-5 h-px bg-current mb-1.5"></span>
        <span class="block w-5 h-px bg-current mb-1.5"></span>
        <span class="block w-5 h-px bg-current"></span>
      </button>
    </div>
  </div>

  <?php // Collapses via grid-template-rows (0fr -> 1fr), not opacity — an
  // opacity-only "closed" state still lays out at full height, which was
  // inflating this fixed header's box and getting caught by its
  // backdrop-blur once scrolled. See main.js. ?>
  <div id="mobile-menu" class="lg:hidden grid grid-rows-[0fr] opacity-0 transition-all duration-300 ease-out bg-white text-ink border-t border-line">
    <nav class="overflow-hidden">
      <div class="px-4 py-4 flex flex-col gap-1 max-h-[calc(100vh-5rem)] overflow-y-auto">
        <?php foreach ($navItems as $item): ?>
          <?php $subItems = $item->intendedTemplate() == 'locations' ? $item->children()->listed()->filterBy('intendedTemplate', 'property') : null ?>
          <?php if ($subItems && $subItems->count()): ?>
            <div data-mobile-accordion>
              <div class="flex items-center">
                <a href="<?= $item->url() ?>" class="flex-1 py-3 text-lg font-semibold uppercase tracking-wide <?= $item->isActive() ? 'text-primary' : '' ?>">
                  <?= $item->title() ?>
                </a>
                <button
                  type="button"
                  data-accordion-toggle
                  aria-expanded="false"
                  aria-label="<?= t('nav.toggleSubmenu', 'Toggle submenu') ?>"
                  class="p-3 -mr-3"
                >
                  <svg data-accordion-chevron class="w-4 h-4 transition-transform duration-200" viewBox="0 0 12 12" fill="none" aria-hidden="true">
                    <path d="M2.5 4.5l3.5 3 3.5-3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                  </svg>
                </button>
              </div>
              <div data-accordion-panel class="grid grid-rows-[0fr] transition-all duration-200 ease-out">
                <div class="overflow-hidden">
                  <div class="flex flex-col pb-2">
                    <?php foreach ($subItems as $sub): ?>
                      <a href="<?= $sub->url() ?>" class="py-2.5 pl-4 text-base uppercase tracking-wide text-ink-soft <?= $sub->isActive() ? 'font-semibold text-ink' : '' ?>">
                        <?= $sub->title() ?>
                      </a>
                    <?php endforeach ?>
                  </div>
                </div>
              </div>
            </div>
          <?php else: ?>
            <a href="<?= $item->url() ?>" class="py-3 text-lg font-semibold uppercase tracking-wide <?= $item->isActive() ? 'text-primary' : '' ?>">
              <?= $item->title() ?>
            </a>
          <?php endif ?>
        <?php endforeach ?>
      </div>
    </nav>
  </div>
</header>

<?php // Everything up to the footer lives in this wrapper: it's the opaque,
// higher-stacked layer that scrolls up over the fixed footer to "reveal"
// it — see footer.php, which closes this div and pins the footer to the
// viewport bottom. main.js keeps its margin-bottom in sync with the
// footer's actual height. ?>
<div id="page-wrap" class="relative z-10 flex-1 flex flex-col bg-bg">
