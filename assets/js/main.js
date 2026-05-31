(() => {
  'use strict';

  /* ========== Mobil menu ========== */
  const navToggle = document.getElementById('nav-toggle');
  const navIcon   = document.getElementById('nav-toggle-icon');
  const mobileMenu = document.getElementById('mobile-menu');

  if (navToggle && mobileMenu) {
    const setOpen = (open) => {
      mobileMenu.classList.toggle('hidden', !open);
      mobileMenu.classList.toggle('is-open', open);
      navToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
      navToggle.setAttribute('aria-label', open ? 'Menüyü kapat' : 'Menüyü aç');
      if (navIcon) navIcon.textContent = open ? 'close' : 'menu';
    };
    navToggle.addEventListener('click', () => setOpen(mobileMenu.classList.contains('hidden')));
    mobileMenu.querySelectorAll('a').forEach((link) => link.addEventListener('click', () => setOpen(false)));
  }

  /* ========== Cookie banner ========== */
  const COOKIE_KEY = 'corpoth_cookie_consent_v1';
  const banner   = document.getElementById('cookie-banner');
  const accept   = document.getElementById('cookie-accept');
  const decline  = document.getElementById('cookie-decline');

  if (banner) {
    const consent = localStorage.getItem(COOKIE_KEY);
    if (!consent) {
      banner.classList.remove('hidden');
      requestAnimationFrame(() => banner.classList.add('is-shown'));
    }
    const dismiss = (val) => {
      localStorage.setItem(COOKIE_KEY, val);
      banner.classList.remove('is-shown');
      setTimeout(() => banner.classList.add('hidden'), 500);
    };
    accept  && accept .addEventListener('click', () => dismiss('accepted'));
    decline && decline.addEventListener('click', () => dismiss('declined'));
  }

  /* ========== Lead form (AJAX) ========== */
  const form   = document.getElementById('lead-form');
  const status = document.getElementById('lead-form-status');

  if (form && status) {
    form.addEventListener('submit', async (event) => {
      event.preventDefault();
      status.classList.remove('is-success', 'is-error');
      status.classList.add('hidden');

      const submit = form.querySelector('button[type="submit"]');
      if (submit) {
        submit.disabled = true;
        submit.dataset.label = submit.dataset.label || submit.innerHTML;
        submit.innerHTML = '<span class="material-symbols-outlined text-lg animate-spin">progress_activity</span> Gönderiliyor...';
      }

      try {
        const data = new FormData(form);
        const res  = await fetch(form.action, {
          method: 'POST',
          body: data,
          headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        });
        const json = await res.json().catch(() => ({ ok: false, message: 'Sunucu hatası.' }));

        status.textContent = json.message || (json.ok ? 'Talebiniz alındı.' : 'Bir hata oluştu.');
        status.classList.remove('hidden');
        status.classList.add(json.ok ? 'is-success' : 'is-error');

        if (json.ok) {
          form.reset();
          if (window.gtag) {
            window.gtag('event', 'lead_form_submit', { event_category: 'engagement' });
          }
        } else if (json.errors) {
          // Alan bazli hatalari ozetle
          const list = Object.values(json.errors).join(' ');
          if (list) status.textContent = list;
        }
      } catch (err) {
        status.textContent = 'Bağlantı hatası, lütfen tekrar deneyin.';
        status.classList.remove('hidden');
        status.classList.add('is-error');
      } finally {
        if (submit) {
          submit.disabled = false;
          if (submit.dataset.label) submit.innerHTML = submit.dataset.label;
        }
      }
    });
  }

  /* ========== Footer dinamik yil (PHP ile uretilse de fallback) ========== */
  const fy = document.getElementById('footer-year');
  if (fy) fy.textContent = new Date().getFullYear();
})();
