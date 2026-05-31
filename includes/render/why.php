<?php
$why    = why_block_get();
$values = value_props_active();
?>
<section class="py-14 md:py-20 px-6 md:px-12 bg-primary text-on-primary rounded-t-[3rem] md:rounded-t-[4rem] overflow-hidden" id="why">
  <div class="max-w-screen-2xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">
    <div data-animate="slide-right">
      <h2 class="text-4xl md:text-5xl font-bold mb-8 leading-tight"><?= e($why['title'] ?? 'Neden CORPOTH?') ?></h2>
      <ul class="space-y-6">
        <?php foreach ($values as $i => $v): ?>
        <li class="flex items-start gap-4" data-animate="fade-up" data-animate-delay="<?= 100 * $i ?>">
          <span class="material-symbols-outlined text-primary-fixed mt-1"><?= e($v['icon']) ?></span>
          <div>
            <h5 class="font-bold text-lg"><?= e($v['title']) ?></h5>
            <p class="text-primary-fixed/80"><?= e($v['description']) ?></p>
          </div>
        </li>
        <?php endforeach; ?>
      </ul>
    </div>
    <?php if (!empty($why['image_path'])): ?>
    <div class="relative" data-animate="slide-left">
      <img alt="<?= e($why['image_alt'] ?? '') ?>" class="rounded-xl object-cover h-[400px] md:h-[500px] w-full border-4 border-white/10" src="<?= e($why['image_path']) ?>"/>
    </div>
    <?php endif; ?>
  </div>
</section>
