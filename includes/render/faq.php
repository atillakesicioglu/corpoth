<?php
$items = faq_active();
if (!$items) return;
?>
<section class="py-24 md:py-32 px-6 md:px-12 bg-surface" id="faq">
  <div class="max-w-4xl mx-auto">
    <div class="text-center mb-12 md:mb-16" data-animate="fade-up">
      <h2 class="text-3xl md:text-4xl font-bold tracking-tight mb-4">Sıkça Sorulan Sorular</h2>
      <p class="text-secondary">Aklınızdaki sorulara hızlı cevaplar; daha fazlası için iletişim formunu kullanabilirsiniz.</p>
    </div>
    <div class="divide-y divide-outline-variant/15 border border-outline-variant/15 rounded-xl bg-surface-container-lowest overflow-hidden">
      <?php foreach ($items as $i => $f): ?>
      <details class="group" data-animate="fade-up" data-animate-delay="<?= 60 * $i ?>" <?= $i === 0 ? 'open' : '' ?>>
        <summary class="flex items-start justify-between gap-4 cursor-pointer p-5 md:p-6 list-none">
          <span class="font-semibold text-on-surface text-base md:text-lg"><?= e($f['question']) ?></span>
          <span class="material-symbols-outlined text-primary transition-transform group-open:rotate-45 shrink-0">add</span>
        </summary>
        <div class="px-5 md:px-6 pb-5 md:pb-6 text-secondary leading-relaxed prose-sm">
          <?= safe_html(nl2br(e($f['answer']))) ?>
        </div>
      </details>
      <?php endforeach; ?>
    </div>
  </div>
</section>
