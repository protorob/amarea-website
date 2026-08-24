<?php $navItems = $site->children()->listed() ?>

<footer class="mt-auto bg-ink text-white">
  <div class="max-w-site mx-auto px-4 py-16 grid gap-10 sm:grid-cols-2 lg:grid-cols-4">

    <div class="lg:col-span-1">
      <img src="<?= url('assets/images/amarea-logo-textonly-white.svg') ?>" alt="<?= $site->title() ?>" class="h-7 w-auto mb-3">
      <p class="text-sm text-white/70"><?= $site->footerClaim()->or('Mediterranean Co-Living') ?></p>
    </div>

    <nav class="flex flex-col gap-2 text-sm">
      <?php foreach ($navItems as $item): ?>
        <a href="<?= $item->url() ?>" class="text-white/80 hover:text-white transition-colors"><?= $item->title() ?></a>
      <?php endforeach ?>
    </nav>

    <div class="flex flex-col gap-2 text-sm text-white/80">
      <?php if ($site->email()->isNotEmpty()): ?>
        <a href="mailto:<?= $site->email() ?>" class="hover:text-white transition-colors"><?= $site->email() ?></a>
      <?php endif ?>
      <?php if ($site->whatsapp()->isNotEmpty()): ?>
        <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $site->whatsapp()->value()) ?>" class="hover:text-white transition-colors" target="_blank" rel="noopener">
          <?= t('whatsapp.cta') ?>
        </a>
      <?php endif ?>
      <?php if ($site->address()->isNotEmpty()): ?>
        <address class="not-italic text-white/60"><?= nl2br($site->address()->esc()) ?></address>
      <?php endif ?>
    </div>

    <?php if ($site->instagramUrl()->isNotEmpty()): ?>
      <div class="text-sm">
        <a href="<?= $site->instagramUrl() ?>" target="_blank" rel="noopener" class="text-white/80 hover:text-white transition-colors">
          Instagram <?= $site->instagramHandle() ?>
        </a>
      </div>
    <?php endif ?>
  </div>

  <div class="border-t border-white/10">
    <div class="max-w-site mx-auto px-4 py-4 text-xs text-white/50">
      &copy; <?= date('Y') ?> <?= $site->title() ?>
    </div>
  </div>
</footer>

<?php snippet('lead-form-modal') ?>

<?php if ($site->elfsightWidgetClass()->isNotEmpty()): ?>
  <script src="https://elfsightcdn.com/platform.js" async></script>
<?php endif ?>

<script type="module" src="<?= url('assets/js/main.js') ?>"></script>
</body>
</html>
