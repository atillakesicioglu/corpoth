(() => {
  'use strict';

  const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* ========== Mobil menu ========== */
  const navToggle  = document.getElementById('nav-toggle');
  const navIcon    = document.getElementById('nav-toggle-icon');
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
     Hash-link tiklamalarini yakalayip kendi animasyonumuzla scroll'lariz.
     Easing: easeOutQuart (basta hizli, sonda yavas). */
  const easeOutQuart = (t) => 1 - Math.pow(1 - t, 4);

  const smoothScrollTo = (targetY, duration = 900) => {
    if (reduceMotion) {
      window.scrollTo(0, targetY);
      return;
    }
    const startY  = window.pageYOffset;
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

  // CSS scroll-smooth varsayilanini iptal et (kendi animasyonumuz devraliyor)
  document.documentElement.classList.remove('scroll-smooth');
  document.documentElement.style.scrollBehavior = 'auto';

  document.addEventListener('click', (e) => {
    const a = e.target.closest('a[href^="#"]');
    if (!a) return;
    const href = a.getAttribute('href');
    if (!href || href === '#' || href.length < 2) return;

    const target = document.querySelector(href);
    if (!target) return;

    e.preventDefault();
    const offsetTop = target.getBoundingClientRect().top + window.pageYOffset - 88; // nav yuksekligi
    smoothScrollTo(offsetTop, 950);

    // URL'i guncelle (yenilemeden)
    if (history.pushState) {
      history.pushState(null, '', href);
    }
  });

  /* ========== FAQ smooth aciliska ========== */
  document.querySelectorAll('details.faq-item').forEach((detail) => {
    const content = detail.querySelector('.faq-content');
    if (!content) return;

    // Default acik olan kalemler icin yuksekligi initial olarak ayarla
    if (detail.open) {
      content.style.height = 'auto';
    } else {
      content.style.height = '0px';
    }

    const summary = detail.querySelector('summary');
    if (!summary) return;

    summary.addEventListener('click', (e) => {
      if (reduceMotion) return; // CSS yonetir
      e.preventDefault();

      if (detail.open) {
        // Kapat
        const startHeight = content.scrollHeight;
        content.style.height = startHeight + 'px';
        requestAnimationFrame(() => {
          content.style.height = '0px';
        });
        const onEnd = () => {
          detail.open = false;
          content.removeEventListener('transitionend', onEnd);
        };
        content.addEventListener('transitionend', onEnd);
      } else {
        // Ac
        detail.open = true;
        const endHeight = content.scrollHeight;
        content.style.height = '0px';
        requestAnimationFrame(() => {
          content.style.height = endHeight + 'px';
        });
        const onEnd = () => {
          content.style.height = 'auto';
          content.removeEventListener('transitionend', onEnd);
        };
        content.addEventListener('transitionend', onEnd);
      }
    });
  });

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
