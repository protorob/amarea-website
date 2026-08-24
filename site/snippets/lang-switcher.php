<?php
// Language dropdown: shows the current language code when closed, full
// language names when open. $id keeps two instances (desktop/mobile nav)
// from colliding. Toggle/close logic lives in src/main.js.
$id ??= 'lang-switcher';
$langCode = $kirby->language()?->code() ?? 'en';
?>
<div class="relative" data-lang-switcher>
  <button
    type="button"
    data-lang-toggle
    aria-haspopup="true"
    aria-expanded="false"
    aria-controls="<?= $id ?>-menu"
    class="inline-flex items-center gap-1.5 rounded-full border border-current/30 px-3 py-1.5 text-xs font-medium uppercase tracking-wide hover:border-current/60 transition-colors"
  >
    <?= $langCode ?>
    <svg width="10" height="6" viewBox="0 0 10 6" fill="none" data-lang-chevron class="transition-transform duration-200" aria-hidden="true">
      <path d="M1 1l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
  </button>

  <div
    id="<?= $id ?>-menu"
    data-lang-menu
    class="hidden absolute left-0 mt-2 w-40 rounded-xl bg-white text-ink shadow-lg border border-line py-2 z-50"
  >
    <?php foreach ($kirby->languages() as $language): ?>
      <a
        href="<?= $page->url($language->code()) ?>"
        class="block px-4 py-2 text-sm hover:bg-accent-soft/40 transition-colors <?= $language->code() === $langCode ? 'font-semibold text-primary' : '' ?>"
      ><?= $language->name() ?></a>
    <?php endforeach ?>
  </div>
</div>
