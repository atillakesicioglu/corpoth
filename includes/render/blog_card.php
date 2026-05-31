<?php
/**
 * Blog kart partial.
 * Beklenen: $post (array), $delay (int, opsiyonel)
 */
$p = $post ?? [];
if (!$p) return;
$delay = $delay ?? 0;
$href  = '/blog/' . $p['slug'];
$cover = $p['cover_image'] ?? '';
$pubd  = $p['published_at'] ?? $p['created_at'] ?? null;
?>
<a href="<?= e($href) ?>" class="blog-card" data-animate="fade-up" data-animate-delay="<?= (int)$delay ?>">
  <div class="blog-card-cover">
    <?php if ($cover): ?>
      <img src="<?= e($cover) ?>" alt="<?= e($p['title']) ?>" loading="lazy"/>
    <?php else: ?>
      <div class="w-full h-full flex items-center justify-center text-primary/30">
        <span class="material-symbols-outlined" style="font-size:4rem">article</span>
      </div>
    <?php endif; ?>
  </div>
  <div class="blog-card-body">
    <?php if (!empty($p['category_name'])): ?>
      <span class="blog-card-cat"><?= e($p['category_name']) ?></span>
    <?php endif; ?>
    <h3 class="blog-card-title"><?= e($p['title']) ?></h3>
    <?php if (!empty($p['excerpt'])): ?>
      <p class="blog-card-excerpt"><?= e(str_excerpt($p['excerpt'], 140)) ?></p>
    <?php endif; ?>
    <div class="blog-card-meta">
      <?php if (!empty($p['author_name'])): ?>
        <span class="inline-flex items-center gap-1"><span class="material-symbols-outlined text-base">person</span><?= e($p['author_name']) ?></span>
      <?php endif; ?>
      <?php if ($pubd): ?>
        <time datetime="<?= e($pubd) ?>" class="inline-flex items-center gap-1"><span class="material-symbols-outlined text-base">schedule</span><?= e(fmt_date($pubd, 'd M Y')) ?></time>
      <?php endif; ?>
    </div>
  </div>
</a>
