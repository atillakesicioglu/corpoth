<?php
$steps = process_active();
?>
<section class="py-14 md:py-20 px-6 md:px-12 bg-surface" id="process">
  <div class="max-w-screen-2xl mx-auto text-center mb-16 md:mb-20" data-animate="fade-up">
    <h2 class="text-3xl md:text-4xl font-bold tracking-tight mb-4">Nasıl Çalışır?</h2>
    <p class="text-secondary max-w-2xl mx-auto">Sadece <?= count($steps) ?> adımda çalışanlarınızın enerjisini tazeliyoruz.</p>
  </div>
  <div class="max-w-screen-2xl mx-auto relative">
    <div class="hidden lg:block absolute top-8 left-0 w-full h-px bg-outline-variant/20 -z-10"></div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-<?= max(1, count($steps)) ?> gap-12">
      <?php foreach ($steps as $i => $s): ?>
      <div class="flex flex-col items-center text-center" data-animate="fade-up" data-animate-delay="<?= 100 * $i ?>">
        <div class="w-16 h-16 rounded-full bg-primary text-on-primary flex items-center justify-center font-bold text-xl mb-6 editorial-shadow"><?= (int) $s['step_number'] ?></div>
        <h4 class="font-bold mb-3"><?= e($s['title']) ?></h4>
        <p class="text-sm text-secondary"><?= e($s['description']) ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
