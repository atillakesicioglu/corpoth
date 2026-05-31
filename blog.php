<?php
require_once __DIR__ . '/includes/bootstrap.php';

$catSlug   = isset($_GET['kategori']) ? (string)$_GET['kategori'] : '';
$pageNum   = max(1, (int)($_GET['s'] ?? 1));
$perPage   = 12;

$cat       = $catSlug ? blog_cat_get_by_slug($catSlug) : null;
$catId     = $cat['id'] ?? null;
$total     = blog_count($catId);
$totalPages= max(1, (int)ceil($total / $perPage));
$pageNum   = min($pageNum, $totalPages);
$posts     = blog_published($pageNum, $perPage, $catId);
$cats      = blog_cat_active();

$page_title       = $cat ? ($cat['name'] . ' | CORPOTH Blog') : 'Blog | CORPOTH';
$page_description = $cat ? ($cat['description'] ?: ('CORPOTH Blog - ' . $cat['name'])) : 'CORPOTH Blog: kurumsal sağlık, ofis ergonomisi ve çalışan deneyimi üzerine içgörüler.';
$page_canonical   = rtrim(setting('canonical_url', ''), '/') . '/blog.php' . ($cat ? '?kategori=' . $cat['slug'] : '');
$page_breadcrumb  = $cat ? [['label' => 'Blog', 'href' => '/blog.php'], ['label' => $cat['name']]] : [['label' => 'Blog']];
$current_page     = 'blog';
$is_subpage       = true;

require __DIR__ . '/includes/render/head.php';
?>
<body class="bg-surface text-on-surface font-body">
<a href="#main" class="skip-link">İçeriğe geç</a>
<?php require __DIR__ . '/includes/render/nav.php'; ?>

<main id="main" class="pt-20">
  <?php
  $heroOverrides = [];
  if ($cat) {
    $heroOverrides['hero_title']    = $cat['name'];
    $heroOverrides['hero_subtitle'] = $cat['description'] ?: '';
  }
  extract(page_hero_load('blog', [
    'hero_eyebrow'  => 'BLOG',
    'hero_title'    => 'İçgörüler & uzman yazıları',
    'hero_subtitle' => 'Kurumsal sağlık, ofis ergonomisi ve çalışan deneyimi üzerine düşünceler.',
  ], $heroOverrides));
  $breadcrumbs = $page_breadcrumb;
  require __DIR__ . '/includes/render/page_hero.php';
  ?>

  <!-- Kategori filtre seridi -->
  <?php if ($cats): ?>
  <section class="px-6 md:px-12">
    <div class="max-w-screen-2xl mx-auto -mt-2">
      <div class="flex gap-2 overflow-x-auto pb-2 -mx-1 px-1">
        <a href="/blog.php" class="shrink-0 px-4 py-2 rounded-full text-sm font-medium border <?= !$cat ? 'bg-primary text-on-primary border-primary' : 'bg-white border-outline-variant/30 text-on-surface hover:border-primary/40' ?>">Tümü</a>
        <?php foreach ($cats as $c): ?>
        <a href="/blog.php?kategori=<?= eu($c['slug']) ?>" class="shrink-0 px-4 py-2 rounded-full text-sm font-medium border <?= ($cat && $cat['id'] === $c['id']) ? 'bg-primary text-on-primary border-primary' : 'bg-white border-outline-variant/30 text-on-surface hover:border-primary/40' ?>"><?= e($c['name']) ?></a>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <section class="py-10 md:py-16 px-6 md:px-12">
    <div class="max-w-screen-2xl mx-auto">
      <?php if (!$posts): ?>
        <div class="text-center py-16 text-secondary">
          <span class="material-symbols-outlined text-5xl text-primary/40 mb-3 block">article</span>
          <p class="text-lg">Henüz yayınlanmış bir yazı yok.</p>
        </div>
      <?php else: ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
          <?php foreach ($posts as $i => $p): ?>
            <?php $post = $p; $delay = $i * 60; require __DIR__ . '/includes/render/blog_card.php'; ?>
          <?php endforeach; ?>
        </div>

        <?php if ($totalPages > 1): ?>
        <nav class="mt-12 flex justify-center items-center gap-1" aria-label="Sayfalama">
          <?php
          $baseUrl = '/blog.php' . ($cat ? '?kategori=' . eu($cat['slug']) . '&' : '?');
          $prevPage = $pageNum - 1;
          $nextPage = $pageNum + 1;
          ?>
          <?php if ($pageNum > 1): ?>
          <a href="<?= e($baseUrl . 's=' . $prevPage) ?>" class="inline-flex items-center justify-center w-10 h-10 rounded-lg border border-outline-variant/30 bg-white hover:border-primary/40">
            <span class="material-symbols-outlined">chevron_left</span>
          </a>
          <?php endif; ?>
          <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <?php if ($i === $pageNum): ?>
              <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-primary text-on-primary font-semibold"><?= $i ?></span>
            <?php else: ?>
              <a href="<?= e($baseUrl . 's=' . $i) ?>" class="inline-flex items-center justify-center w-10 h-10 rounded-lg border border-outline-variant/30 bg-white hover:border-primary/40 font-semibold"><?= $i ?></a>
            <?php endif; ?>
          <?php endfor; ?>
          <?php if ($pageNum < $totalPages): ?>
          <a href="<?= e($baseUrl . 's=' . $nextPage) ?>" class="inline-flex items-center justify-center w-10 h-10 rounded-lg border border-outline-variant/30 bg-white hover:border-primary/40">
            <span class="material-symbols-outlined">chevron_right</span>
          </a>
          <?php endif; ?>
        </nav>
        <?php endif; ?>
      <?php endif; ?>
    </div>
  </section>

  <?php require __DIR__ . '/includes/render/cta_band.php'; ?>
</main>

<?php require __DIR__ . '/includes/render/footer.php'; ?>
<?php require __DIR__ . '/includes/render/cookie_banner.php'; ?>
<?php $mainV = @filemtime(__DIR__ . '/assets/js/main.js') ?: time(); $animV = @filemtime(__DIR__ . '/assets/js/animations.js') ?: $mainV; ?>
<script src="/assets/js/main.js?v=<?= $mainV ?>" defer></script>
<script src="/assets/js/animations.js?v=<?= $animV ?>" defer></script>
</body>
</html>
