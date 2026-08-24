<?php
// Full-bleed hero: video background if $video is set, otherwise $image.
// Used on Home; any other page can opt in by setting hasHero: true in its
// blueprint and calling this snippet — the header already branches on it.
$eyebrow ??= null;
$title ??= $page->title()->value();
$text ??= null;
$video ??= null;
$image ??= null;
$ctaPrimaryLabel ??= t('cta.joinWaitlist');
$ctaSecondaryLabel ??= null;
$ctaSecondaryUrl ??= null;
?>
<section class="relative h-screen min-h-[560px] w-full overflow-hidden bg-ink">
  <?php if ($video): ?>
    <video
      class="absolute inset-0 h-full w-full object-cover"
      autoplay muted loop playsinline
      <?= $image ? 'poster="' . $image->url() . '"' : '' ?>
    >
      <source src="<?= $video->url() ?>" type="<?= $video->mime() ?>">
    </video>
  <?php elseif ($image): ?>
    <img src="<?= $image->url() ?>" alt="" class="absolute inset-0 h-full w-full object-cover">
  <?php endif ?>

  <div class="absolute inset-0 bg-gradient-to-t from-ink/70 via-ink/20 to-ink/40"></div>

  <div class="relative z-10 h-full max-w-site mx-auto px-4 flex flex-col justify-center items-center gap-6 text-white text-center">
    <?php if ($eyebrow): ?>
      <p class="uppercase tracking-widest text-sm text-white/80"><?= $eyebrow ?></p>
    <?php endif ?>

    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-title leading-tight max-w-5xl mx-auto"><?= $title ?></h1>

    <?php if ($text): ?>
      <div class="text-lg text-white/90 max-w-5xl mx-auto prose prose-invert prose-p:my-0"><?= $text ?></div>
    <?php endif ?>

    <div class="flex flex-wrap justify-center gap-4 mt-2">
      <button type="button" data-open-lead-modal class="inline-flex items-center rounded-full bg-primary px-6 py-3 text-white font-medium hover:bg-primary-strong transition-colors">
        <?= $ctaPrimaryLabel ?>
      </button>
      <?php if ($ctaSecondaryLabel && $ctaSecondaryUrl): ?>
        <a href="<?= $ctaSecondaryUrl ?>" class="inline-flex items-center rounded-full border border-white/60 px-6 py-3 text-white font-medium hover:bg-white/10 transition-colors">
          <?= $ctaSecondaryLabel ?>
        </a>
      <?php endif ?>
    </div>
  </div>

  <a
    href="#intro"
    aria-label="Scroll down"
    class="absolute bottom-8 inset-x-0 z-10 flex justify-center text-white animate-bounce"
  >
    <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" aria-hidden="true">
      <path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
  </a>
</section>
