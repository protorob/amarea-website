<?php
// Shared button-group renderer for the `fields/buttons` structure preset —
// used by the page-level Home hero and the Hero Block. Each button either
// opens the waitlist modal or links out, styled primary (solid) or
// secondary (outline, using currentColor so it matches whatever text
// color the surrounding section set).
$buttons ??= null;
if (!$buttons || $buttons->count() === 0) return;

$buttonStyleClasses = [
  'primary'   => 'bg-primary hover:bg-primary-strong text-white',
  'secondary' => 'border border-current hover:opacity-75',
];
?>
<div class="flex flex-wrap justify-center gap-4 mt-2">
  <?php foreach ($buttons as $button): ?>
    <?php $styleClass = $buttonStyleClasses[$button->style()->value()] ?? $buttonStyleClasses['primary'] ?>
    <?php if ($button->openWaitlistModal()->toBool()): ?>
      <button type="button" data-open-lead-modal class="inline-flex items-center rounded-full px-6 py-3 font-medium transition-colors <?= $styleClass ?>">
        <?= $button->label()->or(t('cta.joinWaitlist')) ?>
      </button>
    <?php elseif ($url = $button->link()->toUrl()): ?>
      <a href="<?= $url ?>" class="inline-flex items-center rounded-full px-6 py-3 font-medium transition-colors <?= $styleClass ?>">
        <?= $button->label() ?>
      </a>
    <?php endif ?>
  <?php endforeach ?>
</div>
