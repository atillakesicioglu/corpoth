(() => {
  'use strict';

  const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* ========== Native CSS scroll-smooth'i hemen iptal et ==========
     Tailwind 'scroll-smooth' class'i <html>'de aktif olabilir. JS smooth
     scroll'u kendimiz yoneteceiz, dolayisiyla CSS'i kaldiriyoruz. */
  document.documentElement.classList.remove('scroll-smooth');
  document.documentElement.style.scrollBehavior = 'auto';

  /* ========== Mobil tam ekran menu ========== */
  const navToggle  = document.getElementById('nav-toggle');
  const navIcon    = document.getElementById('nav-toggle-icon');
  const mobileMenu = document.getElementById('mobile-menu');

  const setMobileMenu = (open) => {
    if (!mobileMenu) return;
    mobileMenu.classList.toggle('is-open', open);
    mobileMenu.setAttribute('aria-hidden', open ? 'false' : 'true');
    document.body.classList.toggle('mobile-menu-open', open);
    if (navToggle) {
      navToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
      navToggle.setAttribute('aria-label', open ? 'Menüyü kapat' : 'Menüyü aç');
    }
    if (navIcon) navIcon.textContent = open ? 'close' : 'menu';
  };

  if (navToggle && mobileMenu) {
    navToggle.addEventListener('click', () => {
      setMobileMenu(!mobileMenu.classList.contains('is-open'));
    });

    mobileMenu.querySelectorAll('a').forEach((link) => {
      link.addEventListener('click', () => setMobileMenu(false));
    });

    // ESC ile kapat
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && mobileMenu.classList.contains('is-open')) {
        setMobileMenu(false);
      }
    });
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
      requestAnimationFrame(() => {
        banner.classList.add('is-shown');
        document.body.classList.add('cookie-visible');
      });
    }
    const dismiss = (val) => {
      localStorage.setItem(COOKIE_KEY, val);
      banner.classList.remove('is-shown');
      document.body.classList.remove('cookie-visible');
      setTimeout(() => banner.classList.add('hidden'), 600);
    };
    accept  && accept .addEventListener('click', () => dismiss('accepted'));
    decline && decline.addEventListener('click', () => dismiss('declined'));
  }

  /* ========== Smooth scroll (custom easing) ==========
     Easing: easeOutQuart (basta hizli, sonda yavas) */
  const easeOutQuart = (t) => 1 - Math.pow(1 - t, 4);

  const smoothScrollTo = (targetY, duration = 950) => {
    if (reduceMotion) {
      window.scrollTo(0, targetY);
      return;
    }
    const startY = window.pageYOffset;
    const distance = targetY - startY;
    if (Math.abs(distance) < 4) return;

    const startTs = performance.now();
    const tick = (now) => {
      const elapsed = now - startTs;
      const progress = Math.min(1, elapsed / duration);
      const eased   = easeOutQuart(progress);
      window.scrollTo(0, startY + distance * eased);
      if (progress < 1) requestAnimationFrame(tick);
    };
    requestAnimationFrame(tick);
  };

  // Click handler - capture phase'de calistir, herhangi bir parent
  // stopPropagation yapsa bile bizim handler garanti tetiklenir.
  const onAnchorClick = (e) => {
    // Modifier tuslari (Ctrl/Cmd/Shift/Alt) veya orta-tikla ile yeni sekmede acmaya izin ver
    if (e.defaultPrevented) return;
    if (e.button !== undefined && e.button !== 0) return;
    if (e.ctrlKey || e.metaKey || e.shiftKey || e.altKey) return;

    const a = e.target.closest && e.target.closest('a[href]');
    if (!a) return;

    const href = a.getAttribute('href');
    if (!href) return;

    // Ayni sayfa hash linki mi?
    let hash = '';
    if (href.startsWith('#')) {
      hash = href;
    } else if (href.startsWith('/#')) {
      hash = href.substring(1);
    } else {
      return; // baska bir sayfaya gidiyorsa karismayalim
    }

    if (hash === '#' || hash.length < 2) return;

    let target;
    try { target = document.querySelector(hash); } catch (_) { return; }
    if (!target) return;

    e.preventDefault();
    e.stopPropagation();

    const navHeight = 80; // h-20
    const offsetTop = target.getBoundingClientRect().top + window.pageYOffset - (navHeight + 8);

    smoothScrollTo(offsetTop, 950);

    if (history.pushState) {
      history.pushState(null, '', hash);
    }
  };

  document.addEventListener('click', onAnchorClick, true); // capture
  // Touch cihazlar icin ek garanti (bazi tarayicilarda click delay olabilir)
  document.addEventListener('touchend', () => {}, { passive: true });

  /* ========== Lead form (AJAX + animasyon) ========== */
  const form   = document.getElementById('lead-form');
  const status = document.getElementById('lead-form-status');

  if (form && status) {
    const setStatus = (msg, kind) => {
      status.classList.remove('is-success', 'is-error', 'hidden');
      status.classList.add(kind === 'success' ? 'is-success' : 'is-error');
      status.innerHTML = (kind === 'success'
        ? '<span class="lead-success-icon material-symbols-outlined align-middle text-base mr-1" style="font-variation-settings:\'FILL\' 1">check_circle</span>'
        : '<span class="material-symbols-outlined align-middle text-base mr-1">error</span>') + msg;
    };

    form.addEventListener('submit', async (event) => {
      event.preventDefault();
      status.classList.add('hidden');
      status.classList.remove('is-success', 'is-error');

      const submit = form.querySelector('button[type="submit"]');
      let originalHtml = '';
      if (submit) {
        originalHtml = submit.innerHTML;
        submit.disabled = true;
        submit.innerHTML = '<span class="material-symbols-outlined text-lg spinner-rotate">progress_activity</span> Gönderiliyor...';
      }

      try {
        const data = new FormData(form);
        const res  = await fetch(form.action, {
          method: 'POST',
          body: data,
          headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        });
        const text = await res.text();
        let json;
        try {
          json = JSON.parse(text);
        } catch (parseErr) {
          throw new Error('Sunucu beklenmeyen bir cevap döndü.');
        }

        if (json.ok) {
          setStatus(json.message || 'Talebiniz alındı.', 'success');
          form.reset();
          if (window.gtag) {
            window.gtag('event', 'lead_form_submit', { event_category: 'engagement' });
          }
        } else {
          let msg = json.message || 'Bir hata oluştu.';
          if (json.errors) {
            msg = Object.values(json.errors).join(' ');
          }
          setStatus(msg, 'error');
        }
      } catch (err) {
        setStatus('Bağlantı hatası: ' + (err.message || 'Lütfen tekrar deneyin.'), 'error');
      } finally {
        if (submit) {
          submit.disabled = false;
          submit.innerHTML = originalHtml;
        }
      }
    });
  }
})();
