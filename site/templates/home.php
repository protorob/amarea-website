<?php snippet('header') ?>

<?php snippet('hero', [
  'eyebrow' => $page->heroEyebrow()->value(),
  'title'   => $page->heroTitle()->or($page->title())->value(),
  'text'    => $page->heroDescription(),
  'video'   => $page->heroVideo()->toFile(),
  'image'   => $page->heroPoster()->toFile(),
  'buttons' => $page->buttons()->toStructure(),
]) ?>

<main class="flex-1 w-full">
  <?= $page->blocks()->toBlocks()->toHtml() ?>
</main>

<?php snippet('footer') ?>
