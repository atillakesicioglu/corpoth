(() => {
  'use strict';

  const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* ========== Native CSS scroll-smooth'i hemen iptal et ========== */
  document.documentElement.classList.remove('scroll-smooth');
  document.documentElement.style.scrollBehavior = 'auto';
  document.documentElement.classList.remove('no-js');

  /* ========== Smooth scroll (custom easing) ==========
     Easing: easeOutQuart (basta hizli, sonda yavas) */
  const easeOutQuart = (t) => 1 - Math.pow(1 - t, 4);

  let activeScrollId = 0;
  const smoothScrollTo = (targetY, duration = 950) => {
    if (reduceMotion) {
      window.scrollTo(0, targetY);
      return;
    }
    const startY = window.pageYOffset;
    const distance = targetY - startY;
    if (Math.abs(distance) < 4) return;

    const myId = ++activeScrollId;
    const startTs = performance.now();
    const tick = (now) => {
      if (myId !== activeScrollId) return; // baska bir scroll devraldi
      const elapsed = now - startTs;
      const progress = Math.min(1, elapsed / duration);
      const eased   = easeOutQuart(progress);
      window.scrollTo(0, startY + distance * eased);
      if (progress < 1) requestAnimationFrame(tick);
    };
    requestAnimationFrame(tick);
  };

  const NAV_OFFSET = 88; // h-20 (80px) + 8px buffer

  const scrollToHash = (hash, duration = 950) => {
    if (!hash || hash === '#' || hash.length < 2) return false;
    let target;
    try { target = document.querySelector(hash); } catch (_) { return false; }
    if (!target) return false;
    const offsetTop = target.getBoundingClientRect().top + window.pageYOffset - NAV_OFFSET;
    smoothScrollTo(offsetTop, duration);
    return true;
  };

  /* ========== Mobil tam ekran menu ========== */
  const navToggle  = document.getElementById('nav-toggle');
  const mobileMenu = document.getElementById('mobile-menu');
  const MOBILE_MENU_CLOSE_MS = 340;
  let mobileMenuClosing = false;

  const setMobileMenu = (open) => {
    if (!mobileMenu || mobileMenuClosing) return;

    const isOpen = mobileMenu.classList.contains('is-open');
    if (open === isOpen) return;

    if (!open) {
      mobileMenuClosing = true;
      mobileMenu.classList.add('is-closing');
      if (navToggle) {
        navToggle.classList.remove('is-active');
        navToggle.setAttribute('aria-expanded', 'false');
        navToggle.setAttribute('aria-label', 'Menüyü aç');
      }
      window.setTimeout(() => {
        mobileMenu.classList.remove('is-open', 'is-closing');
        mobileMenu.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('mobile-menu-open');
        mobileMenuClosing = false;
      }, MOBILE_MENU_CLOSE_MS);
      return;
    }

    mobileMenu.classList.remove('is-closing');
    mobileMenu.classList.add('is-open');
    mobileMenu.setAttribute('aria-hidden', 'false');
    document.body.classList.add('mobile-menu-open');
    if (navToggle) {
      navToggle.classList.add('is-active');
      navToggle.setAttribute('aria-expanded', 'true');
      navToggle.setAttribute('aria-label', 'Menüyü kapat');
    }
  };

  if (navToggle && mobileMenu) {
    navToggle.addEventListener('click', () => {
      setMobileMenu(!mobileMenu.classList.contains('is-open'));
    });

    // Mobil accordion (alt menuler)
    mobileMenu.querySelectorAll('[data-mobile-acc]').forEach((acc) => {
      const head = acc.querySelector('.mobile-acc-head');
      if (!head) return;
      head.addEventListener('click', (e) => {
        e.stopPropagation();
        acc.classList.toggle('is-open');
      });
    });

    // Sadece anchor link / sub-item link tiklamasinda menuyu kapat
    mobileMenu.querySelectorAll('a').forEach((el) => {
      el.addEventListener('click', () => setMobileMenu(false));
    });

    // ESC ile kapat
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && mobileMenu.classList.contains('is-open')) {
        setMobileMenu(false);
      }
    });
  }

  /* ========== Desktop dropdown nav ========== */
  const dropdowns = document.querySelectorAll('[data-dropdown]');
  dropdowns.forEach((dd) => {
    const trigger = dd.querySelector('.nav-dropdown-trigger');
    if (!trigger) return;

    let hoverTimer = null;
    const open  = () => {
      clearTimeout(hoverTimer);
      dropdowns.forEach((other) => { if (other !== dd) other.classList.remove('is-open'); });
      dd.classList.add('is-open');
      trigger.setAttribute('aria-expanded', 'true');
    };
    const close = () => {
      hoverTimer = setTimeout(() => {
        dd.classList.remove('is-open');
        trigger.setAttribute('aria-expanded', 'false');
      }, 140);
    };
    const cancelClose = () => clearTimeout(hoverTimer);

    dd.addEventListener('mouseenter', open);
    dd.addEventListener('mouseleave', close);
    dd.addEventListener('focusin',  cancelClose);
    dd.addEventListener('focusin',  open);
    dd.addEventListener('focusout', (e) => {
      if (!dd.contains(e.relatedTarget)) close();
    });
    trigger.addEventListener('click', (e) => {
      e.preventDefault();
      const isOpen = dd.classList.contains('is-open');
      if (isOpen) {
        dd.classList.remove('is-open');
        trigger.setAttribute('aria-expanded', 'false');
      } else {
        open();
      }
    });
  });

  // Disari tiklayinca tum dropdownlari kapat
  document.addEventListener('click', (e) => {
    if (!e.target.closest('[data-dropdown]')) {
      dropdowns.forEach((dd) => {
        dd.classList.remove('is-open');
        const t = dd.querySelector('.nav-dropdown-trigger');
        if (t) t.setAttribute('aria-expanded', 'false');
      });
    }
  });

  // ESC ile dropdown kapansin
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      dropdowns.forEach((dd) => dd.classList.remove('is-open'));
    }
  });

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

  /* ========== Smooth scroll click handler (capture phase) ========== */
  const onAnchorClick = (e) => {
    if (e.defaultPrevented) return;
    if (e.button !== undefined && e.button !== 0) return;
    if (e.ctrlKey || e.metaKey || e.shiftKey || e.altKey) return;

    const a = e.target.closest && e.target.closest('a[href]');
    if (!a) return;

    const href = a.getAttribute('href');
    if (!href) return;

    let hash = '';
    if (href.startsWith('#')) {
      hash = href;
    } else if (href.startsWith('/#')) {
      hash = href.substring(1);
    } else {
      // Farkli sayfa - dokunmuyoruz
      return;
    }

    if (hash === '#' || hash.length < 2) return;

    let target;
    try { target = document.querySelector(hash); } catch (_) { return; }
    if (!target) return;

    e.preventDefault();
    e.stopPropagation();

    // Mobil menu acik ise once kapat, sonra scroll
    const wasMenuOpen = mobileMenu && mobileMenu.classList.contains('is-open');
    if (wasMenuOpen) setMobileMenu(false);

    const doScroll = () => {
      const offsetTop = target.getBoundingClientRect().top + window.pageYOffset - NAV_OFFSET;
      smoothScrollTo(offsetTop, 950);
      if (history.pushState) {
        history.pushState(null, '', hash);
      }
    };

    if (wasMenuOpen) {
      // Mobil menu animasyonu bitsin (CSS .35s)
      setTimeout(doScroll, 360);
    } else {
      doScroll();
    }
  };

  document.addEventListener('click', onAnchorClick, true); // capture

  // Tarayici geri/ileri (popstate)
  window.addEventListener('popstate', () => {
    if (window.location.hash) scrollToHash(window.location.hash, 600);
  });

  // Sayfa load: URL'de hash varsa kendi smooth scroll ile git
  if (window.location.hash) {
    const initHash = window.location.hash;
    // Browser'in default scroll'unu engelle
    window.scrollTo(0, 0);
    history.scrollRestoration && (history.scrollRestoration = 'manual');
    window.addEventListener('load', () => {
      setTimeout(() => scrollToHash(initHash, 800), 80);
    });
  }

  /* ========== FAQ - JS-controlled height animation ========== */
  document.querySelectorAll('[data-faq]').forEach((item) => {
    const btn     = item.querySelector('.faq-summary');
    const content = item.querySelector('.faq-content');
    if (!btn || !content) return;

    let busy = false;

    const open = () => {
      if (busy) return;
      busy = true;
      item.classList.add('is-open');
      btn.setAttribute('aria-expanded', 'true');

      if (reduceMotion) {
        content.style.height = 'auto';
        busy = false;
        return;
      }

      const targetH = content.scrollHeight;
      content.style.height = '0px';
      // force reflow
      void content.offsetHeight;
      content.style.height = targetH + 'px';

      const onEnd = (e) => {
        if (e.propertyName !== 'height') return;
        content.style.height = 'auto';
        content.removeEventListener('transitionend', onEnd);
        busy = false;
      };
      content.addEventListener('transitionend', onEnd);
    };

    const close = () => {
      if (busy) return;
      busy = true;
      btn.setAttribute('aria-expanded', 'false');

      if (reduceMotion) {
        item.classList.remove('is-open');
        content.style.height = '0px';
        busy = false;
        return;
      }

      const startH = content.scrollHeight;
      content.style.height = startH + 'px';
      void content.offsetHeight;
      item.classList.remove('is-open');
      content.style.height = '0px';

      const onEnd = (e) => {
        if (e.propertyName !== 'height') return;
        content.removeEventListener('transitionend', onEnd);
        busy = false;
      };
      content.addEventListener('transitionend', onEnd);
    };

    btn.addEventListener('click', () => {
      if (item.classList.contains('is-open')) close();
      else open();
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
