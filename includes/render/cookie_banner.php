<?php
$cookieText = setting('cookie_text', 'Bu site çerez kullanır.');
$wa = setting('contact_whatsapp');
?>
<!-- Sticky WhatsApp FAB (cookie banner ile rakipsiz konumlandi) -->
<?php if ($wa): ?>
<a id="wa-fab" href="<?= e(wa_link($wa)) ?>" target="_blank" rel="noopener noreferrer"
   class="fixed bottom-6 right-6 z-40 inline-flex items-center justify-center w-14 h-14 rounded-full bg-[#25D366] text-white shadow-2xl hover:scale-110 transition-transform"
   aria-label="WhatsApp ile iletişim" title="WhatsApp">
  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-7 h-7" fill="currentColor" aria-hidden="true">
    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413z"/>
  </svg>
</a>
<?php endif; ?>

<!-- Cookie banner: WhatsApp FAB'in uzerine cikar -->
<div id="cookie-banner" class="fixed bottom-0 left-0 right-0 z-[60] hidden">
  <div class="max-w-screen-xl mx-auto m-4 bg-white border border-outline-variant/20 shadow-2xl rounded-2xl p-5 md:p-6 flex flex-col md:flex-row gap-4 md:items-center">
    <div class="flex-1 text-sm text-on-surface">
      <?= safe_html($cookieText) ?>
    </div>
    <div class="flex gap-3 shrink-0">
      <button type="button" id="cookie-decline" class="px-4 py-2.5 rounded-xl bg-surface-container-high text-on-surface text-sm font-semibold hover:bg-surface-container-highest transition-colors">Reddet</button>
      <button type="button" id="cookie-accept" class="px-4 py-2.5 rounded-xl primary-gradient text-white text-sm font-semibold hover:-translate-y-0.5 transition-transform">Kabul Et</button>
    </div>
  </div>
</div>
