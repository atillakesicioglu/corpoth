<?php
/**
 * Ic sayfalarin altinda tekrar eden buyuk CTA seridi.
 *
 * Beklenen degiskenler (opsiyonel, tumu defaultlu):
 *   $cta_eyebrow
 *   $cta_title
 *   $cta_text
 *   $cta_button_text
 *   $cta_button_href
 *   $cta_secondary_text
 *   $cta_secondary_href
 */
$eyebrow   = $cta_eyebrow   ?? 'Sonraki adim';
$title     = $cta_title     ?? 'Çalışanlarınıza değer katacak bir görüşme planlayalım';
$text      = $cta_text      ?? 'Şirketiniz icin ozel teklif hazirlayabilmemiz icin formu doldurun ya da dogrudan bizimle iletisime gecin.';
$btnText   = $cta_button_text   ?? 'Teklif Al';
$btnHref   = $cta_button_href   ?? '/iletisim.php#form';
$wa        = setting('contact_whatsapp');
$secText   = $cta_secondary_text ?? ($wa ? 'WhatsApp ile yaz' : null);
$secHref   = $cta_secondary_href ?? ($wa ? wa_link($wa) : null);
?>
<section class="py-20 md:py-24 px-6 md:px-12">
  <div class="max-w-screen-2xl mx-auto">
    <div class="cta-band rounded-3xl px-8 md:px-16 py-14 md:py-20 text-center relative overflow-hidden" data-animate="fade-up">
      <div class="cta-band-orb cta-band-orb-1" aria-hidden="true"></div>
      <div class="cta-band-orb cta-band-orb-2" aria-hidden="true"></div>
      <div class="relative z-10 max-w-3xl mx-auto">
        <span class="inline-block text-white/70 font-label uppercase tracking-[0.25em] text-xs font-bold mb-4"><?= e($eyebrow) ?></span>
        <h2 class="text-white text-3xl md:text-4xl lg:text-5xl font-bold tracking-tight mb-5"><?= e($title) ?></h2>
        <p class="text-white/85 text-base md:text-lg mb-10 leading-relaxed"><?= e($text) ?></p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-stretch sm:items-center">
          <a href="<?= e($btnHref) ?>" class="bg-white text-primary px-8 py-4 rounded-xl font-label text-sm uppercase tracking-widest font-bold shadow-lg hover:-translate-y-0.5 hover:shadow-2xl transition-all inline-flex items-center justify-center gap-2 no-underline">
            <span class="material-symbols-outlined text-lg">send</span>
            <?= e($btnText) ?>
          </a>
          <?php if ($secText && $secHref): ?>
          <a href="<?= e($secHref) ?>" target="_blank" rel="noopener noreferrer" class="bg-white/10 text-white border border-white/30 px-8 py-4 rounded-xl font-label text-sm uppercase tracking-widest font-bold hover:bg-white/20 transition-colors inline-flex items-center justify-center gap-2 no-underline backdrop-blur">
            <span class="material-symbols-outlined text-lg">chat</span>
            <?= e($secText) ?>
          </a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</section>
