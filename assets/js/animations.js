(() => {
  'use strict';

  const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* ========== Scroll reveal (IntersectionObserver) ========== */
  const elements = document.querySelectorAll('[data-animate]');

  if (!('IntersectionObserver' in window) || reduceMotion) {
    elements.forEach((el) => el.classList.add('is-visible'));
  } else {
    const observer = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (!entry.isIntersecting) return;
        const el    = entry.target;
        const delay = parseInt(el.dataset.animateDelay || '0', 10);
        setTimeout(() => el.classList.add('is-visible'), delay);
        observer.unobserve(el);
      });
    }, { threshold: 0.12, rootMargin: '0px 0px -8% 0px' });

    elements.forEach((el) => observer.observe(el));
  }

  /* ========== Sayac animasyonu ========== */
  const counters = document.querySelectorAll('.counter');
  if (counters.length && !reduceMotion && 'IntersectionObserver' in window) {
    const animateCounter = (el) => {
      const target = parseInt(el.dataset.countTo || '0', 10);
      const prefix = el.dataset.countPrefix || '';
      const suffix = el.dataset.countSuffix || '';
      const duration = 1400;
      const startTs = performance.now();

      const tick = (now) => {
        const progress = Math.min(1, (now - startTs) / duration);
        const eased    = 1 - Math.pow(1 - progress, 3);
        const value    = Math.round(target * eased);
        el.textContent = prefix + value + suffix;
        if (progress < 1) requestAnimationFrame(tick);
      };
      requestAnimationFrame(tick);
    };

    const counterObserver = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (!entry.isIntersecting) return;
        animateCounter(entry.target);
        counterObserver.unobserve(entry.target);
      });
    }, { threshold: 0.5 });

    counters.forEach((c) => counterObserver.observe(c));
  } else if (reduceMotion) {
    counters.forEach((c) => {
      const target = parseInt(c.dataset.countTo || '0', 10);
      c.textContent = (c.dataset.countPrefix || '') + target + (c.dataset.countSuffix || '');
    });
  }

  /* ========== Hero rakam balonu icin hafif parallax ========== */
  if (!reduceMotion) {
    const parallax = document.querySelectorAll('[data-parallax]');
    if (parallax.length) {
      const onScroll = () => {
        parallax.forEach((el) => {
          const rect = el.getBoundingClientRect();
          const speed = parseFloat(el.dataset.parallaxSpeed || '0.05');
          const offset = (window.innerHeight / 2 - rect.top) * speed;
          el.style.transform = `translate3d(0, ${offset}px, 0)`;
        });
      };
      window.addEventListener('scroll', onScroll, { passive: true });
      onScroll();
    }
  }
})();
