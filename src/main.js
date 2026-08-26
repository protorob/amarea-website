import './main.css'
import Swiper from 'swiper'
import { Navigation } from 'swiper/modules'
import GLightbox from 'glightbox'

// Scroll reveal: top-level sections inside <main> fade/slide in as they
// enter the viewport. The hidden starting state only applies under the
// .js-reveal class (see the anti-FOUC inline script in header.php), so a
// failed/blocked script never leaves content stuck invisible.
if (document.documentElement.classList.contains('js-reveal')) {
  const revealSections = document.querySelectorAll('main > section:not([data-no-reveal])')

  if (revealSections.length && 'IntersectionObserver' in window) {
    const revealObserver = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (!entry.isIntersecting) return
        entry.target.classList.add('is-revealed')
        revealObserver.unobserve(entry.target)
      })
    }, { threshold: 0.15, rootMargin: '0px 0px -10% 0px' })

    revealSections.forEach((section) => revealObserver.observe(section))
  } else {
    revealSections.forEach((section) => section.classList.add('is-revealed'))
  }
}

// Reveal footer: the footer is fixed to the viewport bottom (see
// footer.php) and #page-wrap sits above it at a higher z-index with an
// opaque background, so it visually covers the footer until the page is
// scrolled all the way down. That only works if #page-wrap reserves
// exactly the footer's height at its own bottom, so keep the two in sync.
const pageWrap = document.getElementById('page-wrap')
const siteFooter = document.getElementById('site-footer')

if (pageWrap && siteFooter) {
  const syncFooterSpace = () => {
    pageWrap.style.marginBottom = `${siteFooter.offsetHeight}px`
  }
  syncFooterSpace()
  window.addEventListener('resize', syncFooterSpace)
  window.addEventListener('load', syncFooterSpace)
}

// Mobile menu toggle — collapses via grid-template-rows (0fr <-> 1fr) so
// the closed state is actually zero height, not just invisible. An
// opacity-only closed state used to leave the menu's full height inside
// the fixed header, which then got painted (and blurred) as part of the
// header once scrolled.
const toggle = document.getElementById('menu-toggle')
const mobileMenu = document.getElementById('mobile-menu')

if (toggle && mobileMenu) {
  toggle.addEventListener('click', () => {
    const isOpen = mobileMenu.classList.contains('grid-rows-[1fr]')
    mobileMenu.classList.toggle('grid-rows-[1fr]', !isOpen)
    mobileMenu.classList.toggle('grid-rows-[0fr]', isOpen)
    mobileMenu.classList.toggle('opacity-100', !isOpen)
    mobileMenu.classList.toggle('opacity-0', isOpen)
    toggle.setAttribute('aria-expanded', String(!isOpen))
  })
}

// Mobile nav accordions (Locations -> Beach House / City Apartments)
document.querySelectorAll('[data-mobile-accordion]').forEach((accordion) => {
  const trigger = accordion.querySelector('[data-accordion-toggle]')
  const panel = accordion.querySelector('[data-accordion-panel]')
  const chevron = accordion.querySelector('[data-accordion-chevron]')
  if (!trigger || !panel) return

  trigger.addEventListener('click', () => {
    const isOpen = panel.classList.contains('grid-rows-[1fr]')
    panel.classList.toggle('grid-rows-[1fr]', !isOpen)
    panel.classList.toggle('grid-rows-[0fr]', isOpen)
    chevron?.classList.toggle('rotate-180', !isOpen)
    trigger.setAttribute('aria-expanded', String(!isOpen))
  })
})

// Slider Block carousels (site/snippets/blocks/slider.php) — each .js-slider
// on the page gets its own Swiper instance.
document.querySelectorAll('.js-slider').forEach((el) => {
  new Swiper(el, {
    modules: [Navigation],
    navigation: {
      nextEl: el.querySelector('.swiper-button-next'),
      prevEl: el.querySelector('.swiper-button-prev'),
    },
    slidesPerView: 1,
    spaceBetween: 16,
    loop: true,
  })
})

// Shared lightbox for any gallery image site-wide (Slider Block slides,
// Location/Unit galleries, etc.) — anything marked .js-lightbox.
if (document.querySelector('.js-lightbox')) {
  GLightbox({ selector: '.js-lightbox' })
}

// Header: transparent-over-hero at the top, solid + sticky once scrolled.
// Pages without a hero render the solid state from the start (see header.php).
const header = document.getElementById('site-header')

if (header && header.dataset.hasHero === 'true') {
  const SOLID_CLASSES = ['bg-bg/95', 'backdrop-blur', 'text-ink', 'shadow-sm']
  const headerInner = document.getElementById('site-header-inner')
  const logoLight = header.querySelector('img[data-logo-light]')
  const logoDark = header.querySelector('img[data-logo-dark]')
  const scrollThreshold = () => 24

  const setScrolled = (isScrolled) => {
    header.classList.toggle('bg-transparent', !isScrolled)
    header.classList.toggle('text-white', !isScrolled)
    SOLID_CLASSES.forEach((cls) => header.classList.toggle(cls, isScrolled))
    // The blue logo has a transition-delay (see header.php) so it only turns
    // fully blue once the header background is already mostly solid.
    logoLight?.classList.toggle('opacity-0', isScrolled)
    logoDark?.classList.toggle('opacity-0', !isScrolled)
    // Shrinks a little once it's sticking to the top instead of sitting
    // over the hero at full height.
    headerInner?.classList.toggle('h-20', !isScrolled)
    headerInner?.classList.toggle('h-16', isScrolled)
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

// Language dropdown (site/snippets/lang-switcher.php) — one or more may
// exist at once (desktop + mobile nav), each self-contained.
document.querySelectorAll('[data-lang-switcher]').forEach((switcher) => {
  const toggleBtn = switcher.querySelector('[data-lang-toggle]')
  const menu = switcher.querySelector('[data-lang-menu]')
  const chevron = switcher.querySelector('[data-lang-chevron]')
  if (!toggleBtn || !menu) return

  const closeMenu = () => {
    menu.classList.add('hidden')
    chevron?.classList.remove('rotate-180')
    toggleBtn.setAttribute('aria-expanded', 'false')
  }
  const openMenu = () => {
    document.querySelectorAll('[data-lang-menu]').forEach((m) => m.classList.add('hidden'))
    menu.classList.remove('hidden')
    chevron?.classList.add('rotate-180')
    toggleBtn.setAttribute('aria-expanded', 'true')
  }

  toggleBtn.addEventListener('click', (e) => {
    e.stopPropagation()
    menu.classList.contains('hidden') ? openMenu() : closeMenu()
  })
  document.addEventListener('click', (e) => {
    if (!switcher.contains(e.target)) closeMenu()
  })
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeMenu()
  })
})

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
