<?php
$faqLimit = $faq_limit ?? null;
$items = faq_active($faqLimit);
if (!$items) return;
$showAllLink = !empty($faq_show_all_link);
?>
<section class="py-14 md:py-20 px-6 md:px-12 bg-surface" id="faq">
  <div class="max-w-4xl mx-auto">
    <div class="text-center mb-12 md:mb-16" data-animate="fade-up">
      <h2 class="text-3xl md:text-4xl font-bold tracking-tight mb-4">Sıkça Sorulan Sorular</h2>
      <p class="text-secondary">Aklınızdaki sorulara hızlı cevaplar; daha fazlası için iletişim formunu kullanabilirsiniz.</p>
    </div>
    <div class="divide-y divide-outline-variant/15 border border-outline-variant/15 rounded-xl bg-surface-container-lowest overflow-hidden">
      <?php foreach ($items as $i => $f): ?>
      <div class="faq-item" data-faq data-animate="fade-up" data-animate-delay="<?= 60 * $i ?>">
        <button type="button" class="faq-summary" aria-expanded="false">
          <span class="faq-question"><?= e($f['question']) ?></span>
          <span class="material-symbols-outlined faq-toggle">add</span>
        </button>
        <div class="faq-content">
          <div class="faq-content-inner">
            <?= safe_html(nl2br(e($f['answer']))) ?>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php if ($showAllLink): ?>
    <div class="text-center mt-8" data-animate="fade-up">
      <a href="/sss.php" class="inline-flex items-center gap-2 text-primary font-semibold hover:gap-3 transition-all">
        Tüm soruları gör <span class="material-symbols-outlined">arrow_forward</span>
      </a>
    </div>
    <?php endif; ?>
  </div>
</section>
