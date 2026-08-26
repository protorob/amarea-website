<?php snippet('header') ?>

<main class="flex-1 w-full">

  <?php snippet('page-hero') ?>

  <?= $page->blocks()->toBlocks()->toHtml() ?>

  <?php snippet('faq-widget', ['category' => $page->slug()]) ?>

</main>

<?php snippet('footer') ?>
