<?php
$audiences = audiences_active();
?>
<section class="py-14 md:py-20 px-6 md:px-12 bg-surface" id="who">
  <div class="max-w-screen-2xl mx-auto text-center mb-16 md:mb-20" data-animate="fade-up">
    <h2 class="text-3xl md:text-4xl font-bold tracking-tight mb-4">Corpoth Kimler İçin?</h2>
    <p class="text-secondary max-w-2xl mx-auto">Sağlıklı bir çalışma kültürü oluşturmak isteyen vizyoner kurumlar ve çalışanları için tasarlandı.</p>
  </div>
  <div class="max-w-screen-2xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8">
    <?php foreach ($audiences as $i => $a): ?>
    <div class="bg-surface-container-lowest p-8 md:p-10 rounded-xl transition-all duration-500 hover:-translate-y-2 group" data-animate="fade-up" data-animate-delay="<?= 100 * $i ?>">
      <div class="w-16 h-16 rounded-full bg-primary-fixed flex items-center justify-center mb-8 group-hover:scale-110 transition-transform">
        <span class="material-symbols-outlined text-primary text-3xl"><?= e($a['icon']) ?></span>
      </div>
      <h3 class="text-xl font-bold mb-4"><?= e($a['title']) ?></h3>
      <p class="text-secondary leading-relaxed"><?= e($a['description']) ?></p>
    </div>
    <?php endforeach; ?>
  </div>
</section>
