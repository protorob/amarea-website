<?php
// Drop-in "FAQs about this page" widget — reads the FAQ page's flat
// structure and optionally filters by category. Call from any template:
//   snippet('faq-widget', ['category' => 'booking'])
// Works with zero categories filled in (falls back to the full list).
$category ??= null;

$faqPage = $site->find('faq');
$faqs = $faqPage ? $faqPage->faqs()->toStructure() : null;

if ($faqs && $category) {
  $faqs = $faqs->filterBy('category', $category);
}
?>

<?php if ($faqs && $faqs->count()): ?>
  <div class="divide-y divide-line">
    <?php foreach ($faqs as $faq): ?>
      <details class="group py-4">
        <summary class="flex items-center justify-between gap-4 cursor-pointer font-medium text-ink list-none">
          <?= $faq->question() ?>
          <span class="shrink-0 text-ink-soft transition-transform group-open:rotate-45">+</span>
        </summary>
        <div class="mt-2 text-sm text-ink-soft prose max-w-none"><?= $faq->answer()->kt() ?></div>
      </details>
    <?php endforeach ?>
  </div>
<?php endif ?>
