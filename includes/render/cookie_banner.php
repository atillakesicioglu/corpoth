<?php
$cookieText = setting('cookie_text', 'Bu site çerez kullanır.');
?>
<div id="cookie-banner" class="fixed bottom-0 left-0 right-0 z-40 hidden">
  <div class="max-w-screen-xl mx-auto m-4 bg-white border border-outline-variant/20 shadow-2xl rounded-2xl p-5 md:p-6 flex flex-col md:flex-row gap-4 md:items-center">
    <div class="flex-1 text-sm text-on-surface">
      <?= safe_html($cookieText) ?>
    </div>
    <div class="flex gap-3 shrink-0">
      <button type="button" id="cookie-decline" class="px-4 py-2.5 rounded-xl bg-surface-container-high text-on-surface text-sm font-semibold">Reddet</button>
      <button type="button" id="cookie-accept" class="px-4 py-2.5 rounded-xl primary-gradient text-white text-sm font-semibold">Kabul Et</button>
    </div>
  </div>
</div>

<!-- Sticky WhatsApp FAB -->
<?php $wa = setting('contact_whatsapp'); if ($wa): ?>
<a href="<?= e(wa_link($wa)) ?>" target="_blank" rel="noopener noreferrer"
   class="fixed bottom-6 right-6 z-40 inline-flex items-center justify-center w-14 h-14 rounded-full bg-[#25D366] text-white shadow-2xl hover:scale-105 transition-transform"
   aria-label="WhatsApp ile iletişim">
  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" class="w-7 h-7" fill="currentColor" aria-hidden="true">
    <path d="M19.11 17.205c-.372 0-1.088 1.39-1.518 1.39-.066 0-.123-.027-.183-.06-.452-.226-.892-.434-1.342-.661-1.106-.55-2.252-1.21-3.187-2.066-.297-.265-.561-.554-.85-.825-.34-.327-.652-.679-.872-1.119-.067-.131-.118-.275-.118-.413 0-.139.041-.282.084-.404.117-.332.366-.467.612-.643.149-.114.302-.225.46-.328.158-.103.323-.193.494-.273.069-.034.135-.068.205-.085.067-.018.137-.027.207-.027.146 0 .29.04.418.116.131.078.255.183.376.291.121.107.241.227.354.354.211.241.396.515.55.819.155.305.301.626.426.952.12.331.226.668.31 1.014.043.176.075.36.075.546 0 .35-.137.706-.404.951-.276.255-.626.41-.99.41ZM27.987 15.83c0 6.617-5.359 11.971-11.973 11.971-2.018 0-3.99-.499-5.737-1.448L4 28l1.692-6.18c-1.078-1.866-1.642-3.967-1.642-6.123 0-6.617 5.354-11.97 11.97-11.97 6.618-.001 11.971 5.354 11.971 11.97l-.004.133Zm-11.969-9.978c-5.503 0-9.978 4.475-9.978 9.978 0 2.184.71 4.219 1.91 5.872l-1.252 4.564 4.679-1.231c1.586 1.04 3.461 1.661 5.488 1.661 5.502 0 9.977-4.475 9.977-9.978 0-5.504-4.475-9.866-9.977-9.866Z"/>
  </svg>
</a>
<?php endif; ?>
