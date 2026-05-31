<?php
$scenarios = scenarios_active();
?>
<section class="py-14 md:py-20 px-6 md:px-12 bg-surface" id="scenarios">
  <div class="max-w-screen-2xl mx-auto">
    <div class="text-center mb-16 md:mb-20" data-animate="fade-up">
      <h2 class="text-3xl md:text-4xl font-bold tracking-tight mb-4">Kullanım Senaryoları</h2>
      <p class="text-secondary max-w-2xl mx-auto">Corpoth sadece bir terapi değil, kurum içi iletişimin güçlü bir aracıdır.</p>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8">
      <?php foreach ($scenarios as $i => $s): ?>
        <?php if (!empty($s['is_text_card'])): ?>
        <div class="group relative overflow-hidden rounded-xl h-72 md:h-80" data-animate="zoom" data-animate-delay="<?= 100 * $i ?>">
          <div class="absolute inset-0 primary-gradient flex flex-col justify-center p-7 md:p-8">
            <h4 class="text-white font-bold text-xl mb-4"><?= e($s['title']) ?></h4>
            <?php if (!empty($s['description'])): ?>
            <p class="text-primary-fixed text-sm mb-4"><?= e($s['description']) ?></p>
            <?php endif; ?>
            <?php if (!empty($s['icon'])): ?>
            <span class="material-symbols-outlined text-white text-3xl"><?= e($s['icon']) ?></span>
            <?php endif; ?>
          </div>
        </div>
        <?php else: ?>
        <div class="group relative overflow-hidden rounded-xl h-72 md:h-80" data-animate="zoom" data-animate-delay="<?= 100 * $i ?>">
          <img class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" alt="<?= e($s['image_alt'] ?? $s['title']) ?>" src="<?= e($s['image_path']) ?>"/>
          <div class="absolute inset-0 bg-primary/60 flex items-end p-7 md:p-8">
            <h4 class="text-white font-bold text-xl"><?= e($s['title']) ?></h4>
          </div>
        </div>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>
  </div>
</section>
