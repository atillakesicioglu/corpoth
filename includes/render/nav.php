<?php
$isSub = !empty($is_subpage);
$prefix = $isSub ? '/' : '';

$navLinks = [
    ['href' => $prefix . '#home',         'label' => 'Anasayfa'],
    ['href' => $prefix . '#service',      'label' => 'Hizmet'],
    ['href' => $prefix . '#who',          'label' => 'Kimler İçin'],
    ['href' => $prefix . '#benefits',     'label' => 'Faydalar'],
    ['href' => $prefix . '#process',      'label' => 'Süreç'],
    ['href' => $prefix . '#testimonials', 'label' => 'Yorumlar'],
    ['href' => $prefix . '#faq',          'label' => 'SSS'],
];
?>
<nav class="fixed top-0 w-full z-50 bg-white/85 backdrop-blur-xl border-b border-outline-variant/10" aria-label="Ana navigasyon">
  <div class="flex justify-between items-center gap-4 px-6 md:px-12 w-full max-w-screen-2xl mx-auto h-20">
    <a href="<?= $prefix ?>#home" class="shrink-0 flex items-center group" aria-label="Corpoth Anasayfa">
      <img src="/assets/images/corpoth-logo.png" alt="CORPOTH" class="h-12 md:h-14 w-auto transition-transform duration-300 group-hover:scale-105" />
    </a>
    <div class="hidden md:flex flex-wrap justify-end gap-x-7 gap-y-2 font-sans text-sm font-medium tracking-wide uppercase">
      <?php foreach ($navLinks as $i => $l): ?>
        <a class="nav-link<?= $i === 0 ? ' nav-link-active' : '' ?>" href="<?= e($l['href']) ?>"><?= e($l['label']) ?></a>
      <?php endforeach; ?>
    </div>
    <div class="flex items-center gap-2 shrink-0">
      <a href="<?= $prefix ?>#contact" class="hidden md:inline-flex primary-gradient text-white px-5 py-2.5 rounded-xl font-label text-sm uppercase tracking-wider font-semibold shadow-md hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300 no-underline">İletişim</a>
      <button type="button" id="nav-toggle" class="md:hidden inline-flex items-center justify-center rounded-xl border border-outline-variant/30 bg-surface-container-lowest p-2.5 text-on-surface hover:bg-surface-container transition-colors relative z-[110]" aria-expanded="false" aria-controls="mobile-menu" aria-label="Menüyü aç">
        <span class="material-symbols-outlined text-2xl" id="nav-toggle-icon">menu</span>
      </button>
    </div>
  </div>
</nav>

<!-- Tam ekran mobil overlay menu -->
<div id="mobile-menu" class="md:hidden" aria-hidden="true">
  <div class="mobile-menu-bg"></div>
  <button type="button" id="mobile-close" class="mobile-menu-close" aria-label="Menüyü kapat">
    <span class="material-symbols-outlined">close</span>
  </button>
  <nav class="mobile-menu-inner" aria-label="Mobil navigasyon">
    <a href="<?= $prefix ?>#home" class="mobile-logo" aria-label="Corpoth Anasayfa">
      <img src="/assets/images/corpoth-logo.png" alt="CORPOTH" class="h-14 w-auto"/>
    </a>
    <ul class="mobile-menu-list">
      <?php foreach ($navLinks as $l): ?>
        <li><a href="<?= e($l['href']) ?>"><?= e($l['label']) ?><span class="material-symbols-outlined">arrow_forward</span></a></li>
      <?php endforeach; ?>
    </ul>
    <a href="<?= $prefix ?>#contact" class="mobile-menu-cta primary-gradient">
      <span class="material-symbols-outlined">send</span>
      Teklif Al
    </a>
    <div class="mobile-menu-foot">
      <span>© <?= date('Y') ?> CORPOTH</span>
    </div>
  </nav>
</div>
