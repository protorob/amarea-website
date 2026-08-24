<?php snippet('header') ?>

<main class="flex-1 w-full pt-32">

  <section class="max-w-3xl mx-auto px-4 pb-16 text-center">
    <h1 class="text-3xl sm:text-4xl font-title mb-6"><?= $page->introTitle() ?></h1>
    <div class="prose max-w-none mx-auto text-ink-soft"><?= $page->introText()->kt() ?></div>
  </section>

  <?php if ($page->symbolText()->isNotEmpty()): ?>
    <section class="max-w-3xl mx-auto px-4 pb-16">
      <h2 class="text-2xl font-title mb-4"><?= $page->symbolTitle() ?></h2>
      <div class="prose max-w-none text-ink-soft"><?= $page->symbolText()->kt() ?></div>
    </section>
  <?php endif ?>

  <section class="bg-accent-soft/40">
    <div class="max-w-5xl mx-auto px-4 py-16">
      <h2 class="text-2xl font-title mb-4 text-center"><?= $page->philosophyTitle() ?></h2>
      <?php if ($page->philosophyIntro()->isNotEmpty()): ?>
        <div class="prose max-w-none text-ink-soft text-center mx-auto mb-10"><?= $page->philosophyIntro()->kt() ?></div>
      <?php endif ?>

      <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
        <?php
        $pillars = [
          'Home' => $page->philosophyHome(),
          'Flow' => $page->philosophyFlow(),
          'Belonging' => $page->philosophyBelonging(),
          'The Mediterranean Way' => $page->philosophyMediterranean(),
        ];
        ?>
        <?php foreach ($pillars as $label => $text): ?>
          <?php if ($text->isNotEmpty()): ?>
            <div>
              <h3 class="font-semibold mb-2"><?= $label ?></h3>
              <p class="text-sm text-ink-soft"><?= $text ?></p>
            </div>
          <?php endif ?>
        <?php endforeach ?>
      </div>
    </div>
  </section>

  <?php if ($page->team()->toStructure()->count()): ?>
    <section class="max-w-site mx-auto px-4 py-20">
      <h2 class="text-2xl font-title mb-10 text-center">Meet the Team</h2>
      <div class="grid gap-10 sm:grid-cols-2 lg:grid-cols-3">
        <?php foreach ($page->team()->toStructure() as $member): ?>
          <div class="text-center">
            <?php if ($photo = $member->photo()->toFile()): ?>
              <img src="<?= $photo->url() ?>" alt="<?= $member->name() ?>" class="w-32 h-32 rounded-full object-cover mx-auto mb-4">
            <?php endif ?>
            <h3 class="font-semibold"><?= $member->name() ?></h3>
            <p class="text-sm text-primary mb-3"><?= $member->role() ?></p>
            <p class="text-sm text-ink-soft"><?= $member->bio() ?></p>
          </div>
        <?php endforeach ?>
      </div>
    </section>
  <?php endif ?>

</main>

<?php snippet('footer') ?>
