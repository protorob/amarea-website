<?php
// Shared hover-zoom/reveal card grid — used by the Locations Block and the
// Location page's "Common Spaces Highlights". Callers normalize their data
// source into $items: an array of ['image' => File|null, 'title' => string,
// 'text' => string, 'url' => string|null, 'buttonLabel' => string|null].
// On desktop the image zooms and an overlay reveals $text (+ button) on
// hover; on mobile the text is always visible below the title.
$items   ??= [];
$columns ??= 2;
$columnsClass = $columns === 3 ? 'lg:grid-cols-3' : 'lg:grid-cols-2';
?>
<div class="grid gap-6 sm:grid-cols-2 <?= $columnsClass ?>">
  <?php foreach ($items as $item): ?>
    <?php
    $card    = $item['url'] ?? null;
    $tag     = $card ? 'a' : 'div';
    $image   = $item['image'] ?? null;
    $title   = $item['title'] ?? '';
    $text    = $item['text'] ?? '';
    $button  = $item['buttonLabel'] ?? null;
    ?>
    <<?= $tag ?> <?= $card ? 'href="' . $card . '"' : '' ?> class="group relative block aspect-[1/1] rounded-sm overflow-hidden bg-ink">
      <?php if ($image): ?>
        <img src="<?= $image->url() ?>" alt="" class="absolute inset-0 h-full w-full object-cover transition-transform duration-500 sm:group-hover:scale-105">
      <?php endif ?>
      <div class="absolute inset-0 bg-gradient-to-t from-ink/85 via-ink/10 to-transparent"></div>

      <div class="absolute inset-x-0 bottom-0 p-6 text-white">
        <h3 class="text-2xl font-logo mb-2"><?= $title ?></h3>
        <div class="grid transition-all duration-300 sm:grid-rows-[0fr] sm:opacity-0 grid-rows-[1fr] opacity-100 sm:group-hover:grid-rows-[1fr] sm:group-hover:opacity-100">
          <div class="overflow-hidden">
            <?php if ($text): ?>
              <p class="text-sm text-white/85 mb-3"><?= $text ?></p>
            <?php endif ?>
            <?php if ($button && $card): ?>
              <span class="inline-flex items-center text-sm font-medium border-b border-white/60"><?= $button ?></span>
            <?php endif ?>
          </div>
        </div>
      </div>
    </<?= $tag ?>>
  <?php endforeach ?>
</div>
