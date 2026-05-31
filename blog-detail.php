<?php
require_once __DIR__ . '/includes/bootstrap.php';

$slug = isset($_GET['slug']) ? trim((string)$_GET['slug']) : '';

if (!$slug) {
    http_response_code(404);
    header('Location: /blog.php');
    exit;
}

$post = blog_get_by_slug($slug);
if (!$post || ($post['status'] ?? '') !== 'published') {
    http_response_code(404);
    $page_title       = 'Yazı bulunamadı | CORPOTH';
    $page_description = 'Aradığınız yazı bulunamadı.';
    $current_page     = 'blog';
    $is_subpage       = true;
    require __DIR__ . '/includes/render/head.php';
    ?>
    <body class="bg-surface">
    <a href="#main" class="skip-link">İçeriğe geç</a>
    <?php require __DIR__ . '/includes/render/nav.php'; ?>
    <main id="main" class="pt-32 pb-24 px-6 text-center">
      <h1 class="text-3xl font-bold mb-4">Yazı bulunamadı</h1>
      <p class="text-secondary mb-6">Bu adreste bir yazı yok veya henüz yayınlanmamış.</p>
      <a href="/blog.php" class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-primary text-white">
        <span class="material-symbols-outlined">arrow_back</span> Blog'a dön
      </a>
    </main>
    <?php require __DIR__ . '/includes/render/footer.php'; ?>
    </body></html>
    <?php
    exit;
}

// View counter (basit)
blog_view_increment((int)$post['id']);

$siteUrl    = rtrim(setting('canonical_url', ''), '/');
$canonical  = $siteUrl . '/blog/' . $post['slug'];
$ogImg      = $post['og_image'] ?? $post['cover_image'] ?? '';
if ($ogImg && !preg_match('#^https?://#', $ogImg)) $ogImg = $siteUrl . $ogImg;

$page_title       = $post['meta_title']       ?: ($post['title'] . ' | CORPOTH Blog');
$page_description = $post['meta_description'] ?: str_excerpt($post['excerpt'] ?? strip_tags($post['content_html'] ?? ''), 160);
$page_canonical   = $canonical;
$page_og_image    = $ogImg ?: null;
$page_breadcrumb  = [
    ['label' => 'Blog', 'href' => '/blog.php'],
];
if (!empty($post['category_name'])) {
    $page_breadcrumb[] = ['label' => $post['category_name'], 'href' => '/blog.php?kategori=' . $post['category_slug']];
}
$page_breadcrumb[] = ['label' => $post['title']];

$page_jsonld = [
    '@type'       => 'BlogPosting',
    'headline'    => $post['title'],
    'description' => $page_description,
    'image'       => $ogImg ?: null,
    'author'      => [
        '@type' => 'Person',
        'name'  => $post['author_name'] ?: 'CORPOTH',
    ],
    'publisher'   => ['@id' => $siteUrl . '/#organization'],
    'datePublished' => $post['published_at'] ?: $post['created_at'],
    'dateModified'  => $post['updated_at']   ?: $post['created_at'],
    'mainEntityOfPage' => $canonical,
    'url' => $canonical,
];
$current_page = 'blog';
$is_subpage   = true;

$related = blog_related((int)$post['id'], $post['category_id'] ? (int)$post['category_id'] : null, 3);

require __DIR__ . '/includes/render/head.php';
?>
<body class="bg-surface text-on-surface font-body">
<a href="#main" class="skip-link">İçeriğe geç</a>
<?php require __DIR__ . '/includes/render/nav.php'; ?>

<main id="main" class="pt-20">
  <!-- Hero -->
  <section class="page-hero">
    <div class="page-hero-bg" aria-hidden="true"></div>
    <div class="max-w-4xl mx-auto px-6 md:px-12 pt-32 md:pt-36 pb-12 relative">
      <?php $breadcrumbs = $page_breadcrumb; require __DIR__ . '/includes/render/breadcrumb.php'; ?>
      <div class="mt-6" data-animate="fade-up">
        <?php if (!empty($post['category_name'])): ?>
        <a href="<?= e('/blog.php?kategori=' . $post['category_slug']) ?>" class="inline-block text-xs font-bold tracking-[0.18em] uppercase text-primary bg-primary-fixed px-3 py-1 rounded-full no-underline mb-4">
          <?= e($post['category_name']) ?>
        </a>
        <?php endif; ?>
        <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold tracking-tight leading-[1.15] mb-5"><?= e($post['title']) ?></h1>
        <div class="flex flex-wrap items-center gap-4 text-sm text-secondary">
          <?php if (!empty($post['author_name'])): ?>
          <span class="inline-flex items-center gap-1.5"><span class="material-symbols-outlined text-base">person</span><?= e($post['author_name']) ?></span>
          <?php endif; ?>
          <?php if ($post['published_at']): ?>
          <time datetime="<?= e($post['published_at']) ?>" class="inline-flex items-center gap-1.5"><span class="material-symbols-outlined text-base">schedule</span><?= e(fmt_date($post['published_at'], 'd M Y')) ?></time>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </section>

  <?php if (!empty($post['cover_image'])): ?>
  <div class="max-w-5xl mx-auto px-6 md:px-12 -mt-4 md:-mt-8 mb-8" data-animate="fade-up">
    <div class="rounded-xl overflow-hidden editorial-shadow">
      <img src="<?= e($post['cover_image']) ?>" alt="<?= e($post['title']) ?>" class="w-full h-[280px] md:h-[420px] object-cover"/>
    </div>
  </div>
  <?php endif; ?>

  <section class="py-8 md:py-12 px-6 md:px-12">
    <div class="max-w-3xl mx-auto" data-animate="fade-up">
      <article class="blog-prose">
        <?php if (!empty($post['excerpt'])): ?>
        <p class="text-lg md:text-xl text-secondary leading-relaxed border-l-4 border-primary pl-5 not-italic font-medium mb-8">
          <?= e($post['excerpt']) ?>
        </p>
        <?php endif; ?>
        <?= safe_html($post['content_html']) ?>
      </article>

      <!-- Paylas -->
      <div class="mt-10 pt-8 border-t border-outline-variant/15">
        <p class="text-sm font-semibold text-secondary mb-3 uppercase tracking-wider">Bu yazıyı paylaş</p>
        <div class="flex flex-wrap gap-2">
          <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?= eu($canonical) ?>" target="_blank" rel="noopener" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#0A66C2] text-white text-sm font-semibold hover:-translate-y-0.5 transition-transform">
            <span class="material-symbols-outlined text-base">work</span> LinkedIn
          </a>
          <a href="https://twitter.com/intent/tweet?url=<?= eu($canonical) ?>&text=<?= eu($post['title']) ?>" target="_blank" rel="noopener" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-on-surface/90 text-white text-sm font-semibold hover:-translate-y-0.5 transition-transform">
            <span class="material-symbols-outlined text-base">share</span> X / Twitter
          </a>
          <a href="https://wa.me/?text=<?= eu($post['title'] . ' - ' . $canonical) ?>" target="_blank" rel="noopener" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#25D366] text-white text-sm font-semibold hover:-translate-y-0.5 transition-transform">
            <span class="material-symbols-outlined text-base">chat</span> WhatsApp
          </a>
          <a href="mailto:?subject=<?= eu($post['title']) ?>&body=<?= eu($canonical) ?>" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-surface-container-high text-on-surface text-sm font-semibold hover:-translate-y-0.5 transition-transform">
            <span class="material-symbols-outlined text-base">mail</span> E-posta
          </a>
        </div>
      </div>
    </div>
  </section>

  <?php if ($related): ?>
  <section class="py-16 md:py-24 px-6 md:px-12 bg-surface-container-low">
    <div class="max-w-screen-2xl mx-auto">
      <h2 class="text-2xl md:text-3xl font-bold tracking-tight mb-8" data-animate="fade-up">İlgili yazılar</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
        <?php foreach ($related as $i => $r): ?>
          <?php $post = $r; $delay = $i * 60; require __DIR__ . '/includes/render/blog_card.php'; ?>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <?php require __DIR__ . '/includes/render/cta_band.php'; ?>
</main>

<?php require __DIR__ . '/includes/render/footer.php'; ?>
<?php require __DIR__ . '/includes/render/cookie_banner.php'; ?>
<?php $mainV = @filemtime(__DIR__ . '/assets/js/main.js') ?: time(); $animV = @filemtime(__DIR__ . '/assets/js/animations.js') ?: $mainV; ?>
<script src="/assets/js/main.js?v=<?= $mainV ?>" defer></script>
<script src="/assets/js/animations.js?v=<?= $animV ?>" defer></script>
</body>
</html>
