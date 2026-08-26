<?php
// Banner hero for interior pages: page title over a hero image (the page's
// own heroImage, or A'Marea's ocean-waves shot as a shared fallback so
// there's never a blank/flat hero). Sits flush at the top of the page —
// the fixed header renders transparent over it and turns solid on scroll,
// same as Home (see header.php's $pageHeroTemplates).
$image = $page->heroImage()->toFile() ?? page('home')->file('hero-ocean-waves.jpg');
$title ??= $page->title()->value();
?>
<section data-no-reveal class="relative h-64 sm:h-72 lg:h-80 w-full overflow-hidden bg-ink">
  <?php if ($image): ?>
    <img src="<?= $image->url() ?>" alt="" class="absolute inset-0 h-full w-full object-cover">
  <?php endif ?>
  <div class="absolute inset-0 bg-linear-to-t from-ink/60 via-ink/20 to-ink/40"></div>

  <div class="relative z-10 h-full max-w-site mx-auto px-4 flex items-end justify-center pb-8 sm:pb-10">
    <h1 class="text-5xl sm:text-6xl lg:text-7xl font-logo tracking-wide text-accent-soft text-center leading-none">
      <?= $title ?>
    </h1>
  </div>
</section>
