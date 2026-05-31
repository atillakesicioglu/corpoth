<?php
require_once __DIR__ . '/includes/bootstrap.php';

$slug = $_GET['slug'] ?? '';
$slug = is_string($slug) ? trim($slug) : '';

if (!$slug) {
    http_response_code(404);
    header('Location: /ekip.php');
    exit;
}

$member = team_get_by_slug($slug);
if (!$member || empty($member['is_active'])) {
    http_response_code(404);
    $page_title       = 'Üye bulunamadı | CORPOTH';
    $page_description = 'Aradığınız ekip üyesi bulunamadı.';
    $current_page     = 'team';
    $is_subpage       = true;
    require __DIR__ . '/includes/render/head.php';
    ?>
    <body class="bg-surface">
    <a href="#main" class="skip-link">İçeriğe geç</a>
    <?php require __DIR__ . '/includes/render/nav.php'; ?>
    <main id="main" class="pt-32 pb-24 px-6 text-center">
      <h1 class="text-3xl font-bold mb-4">Üye bulunamadı</h1>
      <p class="text-secondary mb-6">Bu adreste bir ekip üyesi yok.</p>
      <a href="/ekip.php" class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-primary text-white">
        <span class="material-symbols-outlined">arrow_back</span> Ekip sayfasına dön
      </a>
    </main>
    <?php require __DIR__ . '/includes/render/footer.php'; ?>
    </body></html>
    <?php
    exit;
}

$page_title       = ($member['full_name'] ?? '') . ' | CORPOTH Ekip';
$page_description = $member['bio'] ?? ('CORPOTH ekibi - ' . $member['full_name']);
$page_canonical   = rtrim(setting('canonical_url', ''), '/') . '/ekip/' . $member['slug'];
$page_og_image    = $member['photo'] ?? null;
$page_breadcrumb  = [
    ['label' => 'Ekip', 'href' => '/ekip.php'],
    ['label' => $member['full_name']],
];
$page_jsonld      = [
    '@type'       => 'Person',
    'name'        => $member['full_name'],
    'jobTitle'    => $member['title'] ?? null,
    'description' => strip_tags($member['bio'] ?? ''),
    'image'       => !empty($member['photo']) ? rtrim(setting('canonical_url', ''), '/') . $member['photo'] : null,
    'sameAs'      => array_values(array_filter([$member['linkedin'] ?? null])),
    'worksFor'    => ['@id' => rtrim(setting('canonical_url', ''), '/') . '/#organization'],
];
$current_page     = 'team';
$is_subpage       = true;

require __DIR__ . '/includes/render/head.php';
?>
<body class="bg-surface text-on-surface font-body">
<a href="#main" class="skip-link">İçeriğe geç</a>
<?php require __DIR__ . '/includes/render/nav.php'; ?>

<main id="main" class="pt-20">
  <section class="page-hero">
    <div class="page-hero-bg" aria-hidden="true"></div>
    <div class="max-w-screen-2xl mx-auto px-6 md:px-12 pt-32 md:pt-36 pb-12 md:pb-16 relative">
      <?php $breadcrumbs = $page_breadcrumb; require __DIR__ . '/includes/render/breadcrumb.php'; ?>
      <div class="grid grid-cols-1 md:grid-cols-12 gap-8 lg:gap-12 items-start mt-8">
        <div class="md:col-span-4 lg:col-span-3" data-animate="fade-up">
          <div class="rounded-xl overflow-hidden editorial-shadow aspect-[4/5] bg-surface-container-high">
            <?php if (!empty($member['photo'])): ?>
              <img src="<?= e($member['photo']) ?>" alt="<?= e($member['full_name']) ?>" class="w-full h-full object-cover"/>
            <?php else: ?>
              <div class="w-full h-full flex items-center justify-center text-primary/30">
                <span class="material-symbols-outlined" style="font-size:7rem">person</span>
              </div>
            <?php endif; ?>
          </div>
          <div class="mt-5 flex flex-wrap gap-3">
            <?php if (!empty($member['linkedin'])): ?>
            <a href="<?= e($member['linkedin']) ?>" target="_blank" rel="noopener" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-primary text-on-primary text-sm font-semibold hover:-translate-y-0.5 transition-transform">
              <span class="material-symbols-outlined text-base">work</span> LinkedIn
            </a>
            <?php endif; ?>
            <?php if (!empty($member['email'])): ?>
            <a href="mailto:<?= e($member['email']) ?>" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-surface-container-high text-on-surface text-sm font-semibold hover:-translate-y-0.5 transition-transform">
              <span class="material-symbols-outlined text-base">mail</span> E-posta
            </a>
            <?php endif; ?>
          </div>
        </div>
        <div class="md:col-span-8 lg:col-span-9" data-animate="fade-up" data-animate-delay="100">
          <span class="text-primary font-label uppercase tracking-[0.2em] text-xs font-bold mb-2 block"><?= e($member['title'] ?? 'Ekip Üyesi') ?></span>
          <h1 class="text-4xl md:text-5xl font-bold tracking-tight mb-5"><?= e($member['full_name']) ?></h1>
          <?php if (!empty($member['bio'])): ?>
          <p class="text-lg text-secondary leading-relaxed mb-8"><?= e($member['bio']) ?></p>
          <?php endif; ?>
          <?php if (!empty($member['bio_long'])): ?>
          <article class="blog-prose">
            <?= safe_html($member['bio_long']) ?>
          </article>
          <?php endif; ?>
        </div>
      </div>
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
