<?php
// Team Block — photo/name/role/bio grid (e.g. About's "Meet the Team").
$members = $block->members()->toStructure();
if ($members->count() === 0) return;

$layout = block_layout($block);
?>
<section class="relative w-full overflow-hidden <?= $layout['heightClass'] ?>" style="<?= $layout['backgroundStyle'] ?>">
  <?php if ($layout['video']): ?>
    <video class="absolute inset-0 h-full w-full object-cover" autoplay muted loop playsinline>
      <source src="<?= $layout['video']->url() ?>" type="<?= $layout['video']->mime() ?>">
    </video>
    <div class="absolute inset-0 <?= $layout['overlayClass'] ?>"></div>
  <?php elseif ($layout['image']): ?>
    <img src="<?= $layout['image']->url() ?>" alt="" class="absolute inset-0 h-full w-full object-cover">
    <div class="absolute inset-0 <?= $layout['overlayClass'] ?>"></div>
  <?php endif ?>

  <div class="relative z-10 max-w-site mx-auto px-4" style="<?= $layout['textStyle'] ?>">
    <?php if ($block->eyebrow()->isNotEmpty() || $block->title()->isNotEmpty()): ?>
      <div class="max-w-2xl mx-auto text-center mb-12">
        <?php if ($block->eyebrow()->isNotEmpty()): ?>
          <p class="uppercase tracking-widest text-sm opacity-80 mb-2"><?= $block->eyebrow() ?></p>
        <?php endif ?>
        <?php if ($block->title()->isNotEmpty()): ?>
          <h2 class="text-3xl font-logo"><?= $block->title() ?></h2>
        <?php endif ?>
      </div>
    <?php endif ?>

    <div class="grid gap-10 sm:grid-cols-2 lg:grid-cols-3">
      <?php foreach ($members as $member): ?>
        <div class="text-center">
          <?php if ($photo = $member->photo()->toFile()): ?>
            <img src="<?= $photo->crop(256, 256)->url() ?>" alt="<?= $member->name()->esc() ?>" class="w-32 h-32 rounded-full object-cover mx-auto mb-4" loading="lazy">
          <?php endif ?>
          <?php if ($member->name()->isNotEmpty()): ?>
            <h3 class="font-semibold"><?= $member->name() ?></h3>
          <?php endif ?>
          <?php if ($member->role()->isNotEmpty()): ?>
            <p class="text-sm opacity-70 mb-3"><?= $member->role() ?></p>
          <?php endif ?>
          <?php if ($member->bio()->isNotEmpty()): ?>
            <p class="text-sm opacity-85"><?= $member->bio() ?></p>
          <?php endif ?>
        </div>
      <?php endforeach ?>
    </div>
  </div>
</section>
