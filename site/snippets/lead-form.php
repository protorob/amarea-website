<?php
// Shared lead-capture form fields — used both inside the site-wide modal
// (lead-form-modal.php) and embedded directly on the Contact page.
// $idPrefix keeps field IDs unique when both instances exist on one page.
$idPrefix ??= 'lead';
?>
<form class="lead-form" data-lead-form novalidate>
  <div class="hidden" aria-hidden="true">
    <label>Website
      <input type="text" name="website" tabindex="-1" autocomplete="off">
    </label>
  </div>

  <div class="grid sm:grid-cols-2 gap-4">
    <div>
      <label class="block text-sm mb-1" for="<?= $idPrefix ?>-firstName"><?= t('form.firstName') ?> *</label>
      <input required type="text" id="<?= $idPrefix ?>-firstName" name="firstName" autocomplete="given-name" class="w-full rounded-lg border border-line px-3 py-2 text-sm">
    </div>
    <div>
      <label class="block text-sm mb-1" for="<?= $idPrefix ?>-lastName"><?= t('form.lastName') ?> *</label>
      <input required type="text" id="<?= $idPrefix ?>-lastName" name="lastName" autocomplete="family-name" class="w-full rounded-lg border border-line px-3 py-2 text-sm">
    </div>
    <div>
      <label class="block text-sm mb-1" for="<?= $idPrefix ?>-email"><?= t('form.email') ?> *</label>
      <input required type="email" id="<?= $idPrefix ?>-email" name="email" autocomplete="email" class="w-full rounded-lg border border-line px-3 py-2 text-sm">
    </div>
    <div>
      <label class="block text-sm mb-1" for="<?= $idPrefix ?>-phone"><?= t('form.phone') ?> *</label>
      <input required type="tel" id="<?= $idPrefix ?>-phone" name="phone" autocomplete="tel" class="w-full rounded-lg border border-line px-3 py-2 text-sm">
    </div>
    <div class="sm:col-span-2">
      <label class="block text-sm mb-1" for="<?= $idPrefix ?>-country"><?= t('form.country') ?> *</label>
      <input required type="text" id="<?= $idPrefix ?>-country" name="country" autocomplete="country-name" class="w-full rounded-lg border border-line px-3 py-2 text-sm">
    </div>

    <div class="sm:col-span-2">
      <span class="block text-sm mb-1"><?= t('form.billingType') ?> *</span>
      <div class="flex gap-4 text-sm">
        <label class="inline-flex items-center gap-2">
          <input required type="radio" name="billingType" value="personal" checked data-billing-toggle>
          <?= t('form.billingPersonal') ?>
        </label>
        <label class="inline-flex items-center gap-2">
          <input required type="radio" name="billingType" value="professional" data-billing-toggle>
          <?= t('form.billingProfessional') ?>
        </label>
      </div>
    </div>

    <div class="sm:col-span-2 hidden" data-vat-field>
      <label class="block text-sm mb-1" for="<?= $idPrefix ?>-vatId"><?= t('form.vatId') ?></label>
      <input type="text" id="<?= $idPrefix ?>-vatId" name="vatId" class="w-full rounded-lg border border-line px-3 py-2 text-sm">
    </div>

    <div class="sm:col-span-2">
      <label class="block text-sm mb-1" for="<?= $idPrefix ?>-linkedin"><?= t('form.linkedin') ?></label>
      <input type="url" id="<?= $idPrefix ?>-linkedin" name="linkedin" class="w-full rounded-lg border border-line px-3 py-2 text-sm">
    </div>

    <div class="sm:col-span-2">
      <label class="block text-sm mb-1" for="<?= $idPrefix ?>-about"><?= t('form.about') ?> *</label>
      <textarea required id="<?= $idPrefix ?>-about" name="about" rows="3" class="w-full rounded-lg border border-line px-3 py-2 text-sm"></textarea>
    </div>

    <div class="sm:col-span-2">
      <label class="block text-sm mb-1" for="<?= $idPrefix ?>-referral"><?= t('form.referral') ?> *</label>
      <select required id="<?= $idPrefix ?>-referral" name="referral" class="w-full rounded-lg border border-line px-3 py-2 text-sm">
        <option value=""></option>
        <option value="facebook"><?= t('form.referral.facebook') ?></option>
        <option value="instagram"><?= t('form.referral.instagram') ?></option>
        <option value="search"><?= t('form.referral.search') ?></option>
        <option value="friend"><?= t('form.referral.friend') ?></option>
        <option value="website"><?= t('form.referral.website') ?></option>
        <option value="other"><?= t('form.referral.other') ?></option>
      </select>
    </div>
  </div>

  <p
    class="mt-4 text-sm hidden"
    data-form-message
    data-success-text="<?= t('form.success') ?>"
    data-error-text="<?= t('form.error') ?>"
  ></p>

  <button
    type="submit"
    data-submitting-label="<?= t('form.submitting') ?>"
    class="mt-6 w-full inline-flex justify-center items-center rounded-full bg-primary px-6 py-3 text-white font-medium hover:bg-primary-strong transition-colors disabled:opacity-60"
  >
    <span data-submit-label><?= t('form.submit') ?></span>
  </button>
</form>
