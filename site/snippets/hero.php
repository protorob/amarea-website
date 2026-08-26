<?php
// Full-bleed hero: video background if $video is set, otherwise $image.
// Used on Home; any other page can opt in by setting hasHero: true in its
// blueprint and calling this snippet — the header already branches on it.
$eyebrow ??= null;
$title ??= $page->title()->value();
$text ??= null;
$video ??= null;
$image ??= null;
$buttons ??= null;
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

    <h1 class="text-4xl sm:text-5xl lg:text-7xl font-logo leading-tight max-w-5xl mx-auto"><?= $title ?></h1>

    <?php if ($text && $text->isNotEmpty()): ?>
      <div class="text-lg text-white/90 max-w-5xl mx-auto prose prose-invert prose-p:my-0"><?= $text ?></div>
    <?php endif ?>

    <?php snippet('partials/buttons', ['buttons' => $buttons]) ?>
  </div>

  <button
    type="button"
    data-scroll-next
    aria-label="Scroll down"
    class="absolute bottom-8 inset-x-0 z-10 flex justify-center text-white animate-bounce"
  >
    <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" aria-hidden="true">
      <path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
  </button>
</section>
