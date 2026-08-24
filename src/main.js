import './main.css'

// Mobile menu toggle
const toggle = document.getElementById('menu-toggle')
const mobileMenu = document.getElementById('mobile-menu')

if (toggle && mobileMenu) {
  toggle.addEventListener('click', () => {
    const isOpen = !mobileMenu.classList.contains('pointer-events-none')
    mobileMenu.classList.toggle('opacity-0', isOpen)
    mobileMenu.classList.toggle('opacity-100', !isOpen)
    mobileMenu.classList.toggle('-translate-y-1', isOpen)
    mobileMenu.classList.toggle('translate-y-0', !isOpen)
    mobileMenu.classList.toggle('pointer-events-none', isOpen)
    mobileMenu.classList.toggle('pointer-events-auto', !isOpen)
    toggle.setAttribute('aria-expanded', String(!isOpen))
  })
}

// Header: transparent-over-hero at the top, solid + sticky once scrolled.
// Pages without a hero render the solid state from the start (see header.php).
const header = document.getElementById('site-header')

if (header && header.dataset.hasHero === 'true') {
  const SOLID_CLASSES = ['bg-white/95', 'backdrop-blur', 'text-ink', 'shadow-sm']
  const logo = header.querySelector('img[data-logo-light]')
  const scrollThreshold = () => Math.max(window.innerHeight * 0.8, 200)

  const setScrolled = (isScrolled) => {
    header.classList.toggle('bg-transparent', !isScrolled)
    header.classList.toggle('text-white', !isScrolled)
    SOLID_CLASSES.forEach((cls) => header.classList.toggle(cls, isScrolled))
    if (logo) {
      logo.src = isScrolled ? logo.dataset.logoDark : logo.dataset.logoLight
    }
  }

  let ticking = false
  const onScroll = () => {
    if (ticking) return
    ticking = true
    requestAnimationFrame(() => {
      setScrolled(window.scrollY > scrollThreshold())
      ticking = false
    })
  }

  window.addEventListener('scroll', onScroll, { passive: true })
  onScroll()
}

// Lead-capture modal (opened from any [data-open-lead-modal] trigger)
const modal = document.getElementById('lead-modal')

if (modal) {
  const openModal = () => {
    modal.classList.remove('hidden')
    document.body.classList.add('overflow-hidden')
  }
  const closeModal = () => {
    modal.classList.add('hidden')
    document.body.classList.remove('overflow-hidden')
  }

  document.querySelectorAll('[data-open-lead-modal]').forEach((btn) => {
    btn.addEventListener('click', openModal)
  })
  modal.querySelectorAll('[data-close-lead-modal]').forEach((btn) => {
    btn.addEventListener('click', closeModal)
  })
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && !modal.classList.contains('hidden')) closeModal()
  })
}

// Lead-capture forms (the modal's form and/or the one embedded on Contact)
document.querySelectorAll('[data-lead-form]').forEach((form) => {
  const vatField = form.querySelector('[data-vat-field]')
  const billingInputs = form.querySelectorAll('[data-billing-toggle]')

  const syncVatField = () => {
    const isProfessional = form.querySelector('[data-billing-toggle]:checked')?.value === 'professional'
    if (vatField) vatField.classList.toggle('hidden', !isProfessional)
  }
  billingInputs.forEach((input) => input.addEventListener('change', syncVatField))
  syncVatField()

  form.addEventListener('submit', async (e) => {
    e.preventDefault()

    const messageEl = form.querySelector('[data-form-message]')
    const submitBtn = form.querySelector('button[type="submit"]')
    const submitLabel = form.querySelector('[data-submit-label]')
    const originalLabel = submitLabel?.textContent

    submitBtn.disabled = true
    if (submitLabel) submitLabel.textContent = submitBtn.dataset.submittingLabel || submitLabel.textContent
    messageEl?.classList.add('hidden')

    try {
      const response = await fetch('/lead', {
        method: 'POST',
        headers: { Accept: 'application/json' },
        body: new FormData(form),
      })
      const result = await response.json()

      if (result.ok) {
        form.reset()
        syncVatField()
        if (messageEl) {
          messageEl.textContent = messageEl.dataset.successText || 'Thanks!'
          messageEl.classList.remove('hidden', 'text-red-600')
          messageEl.classList.add('text-primary')
        }
      } else {
        throw new Error(result.error || 'send_failed')
      }
    } catch (err) {
      if (messageEl) {
        messageEl.textContent = messageEl.dataset.errorText || 'Something went wrong.'
        messageEl.classList.remove('hidden', 'text-primary')
        messageEl.classList.add('text-red-600')
      }
    } finally {
      submitBtn.disabled = false
      if (submitLabel && originalLabel) submitLabel.textContent = originalLabel
    }
  })
})
