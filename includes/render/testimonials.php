<?php
$items = testimonials_active();
if (!$items) return;
?>
<section class="py-24 md:py-32 px-6 md:px-12 bg-surface-container-low" id="testimonials">
  <div class="max-w-screen-2xl mx-auto">
    <div class="text-center mb-14 md:mb-20" data-animate="fade-up">
      <h2 class="text-3xl md:text-4xl font-bold tracking-tight mb-4">Müşteri Yorumları</h2>
      <p class="text-secondary max-w-2xl mx-auto">İK ve yönetim kademesinde Corpoth deneyimleri.</p>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
      <?php foreach ($items as $i => $t): ?>
      <figure class="bg-surface-container-lowest p-7 md:p-8 rounded-xl border border-outline-variant/10 flex flex-col h-full" data-animate="fade-up" data-animate-delay="<?= 100 * $i ?>">
        <div class="flex items-center gap-1 mb-4 text-amber-500" aria-label="<?= (int) $t['rating'] ?> / 5">
          <?php for ($r = 1; $r <= 5; $r++): ?>
            <span class="material-symbols-outlined text-xl" style="font-variation-settings:'FILL' 1,'wght' 600;"><?= $r <= (int) $t['rating'] ? 'star' : 'star_border' ?></span>
          <?php endfor; ?>
        </div>
        <blockquote class="text-on-surface leading-relaxed mb-6 flex-1">
          <span aria-hidden="true" class="text-3xl text-primary/40 leading-none mr-1">&ldquo;</span><?= e($t['content']) ?><span aria-hidden="true" class="text-3xl text-primary/40 leading-none ml-1">&rdquo;</span>
        </blockquote>
        <figcaption class="flex items-center gap-3 mt-auto pt-4 border-t border-outline-variant/15">
          <?php if (!empty($t['photo_path'])): ?>
          <img src="<?= e($t['photo_path']) ?>" alt="<?= e($t['name']) ?>" class="w-12 h-12 rounded-full object-cover"/>
          <?php else: ?>
          <div class="w-12 h-12 rounded-full bg-primary-fixed flex items-center justify-center text-primary font-bold">
            <?= e(mb_substr($t['name'], 0, 1)) ?>
          </div>
          <?php endif; ?>
          <div>
            <div class="font-bold text-on-surface"><?= e($t['name']) ?></div>
            <div class="text-xs text-secondary"><?= e(trim(($t['role'] ?? '') . ($t['role'] && $t['company'] ? ' · ' : '') . ($t['company'] ?? ''))) ?></div>
          </div>
        </figcaption>
      </figure>
      <?php endforeach; ?>
    </div>
  </div>
</section>
