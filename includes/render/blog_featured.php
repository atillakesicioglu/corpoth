<?php
/**
 * Anasayfada en yeni 3 blog yazisini gosteren seksiyon.
 */
$posts = function_exists('blog_featured') ? blog_featured(3) : [];
if (!$posts) return;
?>
<section class="py-14 md:py-20 px-6 md:px-12 bg-surface-container-low" id="blog">
  <div class="max-w-screen-2xl mx-auto">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-12 md:mb-16" data-animate="fade-up">
      <div>
        <span class="text-primary font-label uppercase tracking-[0.2em] text-xs font-bold mb-2 block">Blog</span>
        <h2 class="text-3xl md:text-4xl font-bold tracking-tight">İçgörü ve uzman yazıları</h2>
      </div>
      <a href="/blog.php" class="inline-flex items-center gap-2 text-primary font-semibold hover:gap-3 transition-all">
        Tüm yazılar <span class="material-symbols-outlined">arrow_forward</span>
      </a>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
      <?php foreach ($posts as $i => $p): ?>
        <?php $post = $p; $delay = $i * 80; require __DIR__ . '/blog_card.php'; ?>
      <?php endforeach; ?>
    </div>
  </div>
</section>
