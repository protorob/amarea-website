<div id="lead-modal" class="fixed inset-0 z-[100] hidden" role="dialog" aria-modal="true" aria-labelledby="lead-modal-title">
  <div class="absolute inset-0 bg-ink/60 backdrop-blur-sm" data-close-lead-modal></div>

  <div class="relative h-full overflow-y-auto flex items-start sm:items-center justify-center p-4">
    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg p-6 sm:p-8 my-8">
      <button type="button" data-close-lead-modal class="absolute top-4 right-4 p-2 text-ink-soft hover:text-ink" aria-label="<?= t('modal.close') ?>">
        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" aria-hidden="true">
          <path d="M5 5l10 10M15 5L5 15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
        </svg>
      </button>

      <h2 id="lead-modal-title" class="text-xl font-semibold mb-6"><?= t('cta.joinWaitlist') ?></h2>

      <?php snippet('lead-form', ['idPrefix' => 'lead-modal']) ?>
    </div>
  </div>
</div>
