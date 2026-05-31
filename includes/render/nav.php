<?php
/**
 * Top navigation. Anasayfada anchor linkler, alt sayfalarda (kvkk, gizlilik) tam URL.
 *
 * Beklenen: $is_subpage = true ise tum linkler "/#anchor" olur.
 */
$isSub = !empty($is_subpage);
$prefix = $isSub ? '/' : '';
?>
<nav class="fixed top-0 w-full z-50 bg-white/85 backdrop-blur-xl border-b border-outline-variant/10" aria-label="Ana navigasyon">
  <div class="flex justify-between items-center gap-4 px-6 md:px-12 w-full max-w-screen-2xl mx-auto h-20">
    <a href="<?= $prefix ?>#home" class="shrink-0 flex items-center" aria-label="Corpoth Anasayfa">
      <img src="/assets/images/corpoth-logo.png" alt="CORPOTH" class="h-12 md:h-14 w-auto" />
    </a>
    <div class="hidden md:flex flex-wrap justify-end gap-x-7 gap-y-2 font-sans text-sm font-medium tracking-wide uppercase">
      <a class="text-primary font-semibold border-b-2 border-primary pb-1 transition-colors" href="<?= $prefix ?>#home">Anasayfa</a>
      <a class="text-slate-600 hover:text-primary transition-colors" href="<?= $prefix ?>#service">Hizmet</a>
      <a class="text-slate-600 hover:text-primary transition-colors" href="<?= $prefix ?>#who">Kimler İçin</a>
      <a class="text-slate-600 hover:text-primary transition-colors" href="<?= $prefix ?>#benefits">Faydalar</a>
      <a class="text-slate-600 hover:text-primary transition-colors" href="<?= $prefix ?>#process">Süreç</a>
      <a class="text-slate-600 hover:text-primary transition-colors" href="<?= $prefix ?>#testimonials">Yorumlar</a>
      <a class="text-slate-600 hover:text-primary transition-colors" href="<?= $prefix ?>#faq">SSS</a>
    </div>
    <div class="flex items-center gap-2 shrink-0">
      <a href="<?= $prefix ?>#contact" class="hidden md:inline-flex primary-gradient text-white px-5 py-2.5 rounded-xl font-label text-sm uppercase tracking-wider font-semibold hover:opacity-90 transition-opacity no-underline">İletişim</a>
      <button type="button" id="nav-toggle" class="md:hidden inline-flex items-center justify-center rounded-xl border border-outline-variant/30 bg-surface-container-lowest p-2.5 text-on-surface" aria-expanded="false" aria-controls="mobile-menu" aria-label="Menüyü aç">
        <span class="material-symbols-outlined text-2xl" id="nav-toggle-icon">menu</span>
      </button>
    </div>
  </div>
  <div id="mobile-menu" class="hidden md:hidden border-t border-outline-variant/10 bg-white/95 backdrop-blur-xl px-6 py-4">
    <div class="flex flex-col gap-3 font-sans text-sm font-medium tracking-wide uppercase">
      <a class="text-primary font-semibold py-2 border-b border-outline-variant/15" href="<?= $prefix ?>#home">Anasayfa</a>
      <a class="text-slate-600 py-2 border-b border-outline-variant/15" href="<?= $prefix ?>#service">Hizmet</a>
      <a class="text-slate-600 py-2 border-b border-outline-variant/15" href="<?= $prefix ?>#who">Kimler İçin</a>
      <a class="text-slate-600 py-2 border-b border-outline-variant/15" href="<?= $prefix ?>#benefits">Faydalar</a>
      <a class="text-slate-600 py-2 border-b border-outline-variant/15" href="<?= $prefix ?>#process">Süreç</a>
      <a class="text-slate-600 py-2 border-b border-outline-variant/15" href="<?= $prefix ?>#testimonials">Yorumlar</a>
      <a class="text-slate-600 py-2 border-b border-outline-variant/15" href="<?= $prefix ?>#faq">SSS</a>
      <a href="<?= $prefix ?>#contact" class="primary-gradient text-white text-center px-6 py-3 rounded-xl font-label text-sm uppercase tracking-wider font-semibold no-underline mt-1">Teklif Al</a>
    </div>
  </div>
</nav>
