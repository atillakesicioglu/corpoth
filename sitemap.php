<?php
require_once __DIR__ . '/includes/bootstrap.php';

header('Content-Type: application/xml; charset=utf-8');

$siteUrl = rtrim(setting('canonical_url', 'https://www.corpoth.com'), '/');

$urls = [
    ['loc' => '/',                'changefreq' => 'weekly',  'priority' => '1.0'],
    ['loc' => '/hizmet.php',      'changefreq' => 'monthly', 'priority' => '0.9'],
    ['loc' => '/hakkimizda.php',  'changefreq' => 'monthly', 'priority' => '0.8'],
    ['loc' => '/ekip.php',        'changefreq' => 'monthly', 'priority' => '0.7'],
    ['loc' => '/referanslar.php', 'changefreq' => 'monthly', 'priority' => '0.7'],
    ['loc' => '/blog.php',        'changefreq' => 'weekly',  'priority' => '0.8'],
    ['loc' => '/sss.php',         'changefreq' => 'monthly', 'priority' => '0.6'],
    ['loc' => '/iletisim.php',    'changefreq' => 'monthly', 'priority' => '0.7'],
    ['loc' => '/kvkk.php',        'changefreq' => 'yearly',  'priority' => '0.3'],
    ['loc' => '/gizlilik.php',    'changefreq' => 'yearly',  'priority' => '0.3'],
];

// Blog yazilari
$posts = [];
try {
    $posts = db()->query('SELECT slug, updated_at, published_at FROM blog_posts WHERE status = "published" AND (published_at IS NULL OR published_at <= NOW()) ORDER BY published_at DESC')->fetchAll();
} catch (Throwable $e) {}

foreach ($posts as $p) {
    $urls[] = [
        'loc'        => '/blog/' . $p['slug'],
        'lastmod'    => $p['updated_at'] ?? $p['published_at'] ?? null,
        'changefreq' => 'monthly',
        'priority'   => '0.6',
    ];
}

// Ekip uyeleri
$members = [];
try {
    $members = db()->query('SELECT slug, updated_at FROM team_members WHERE is_active = 1 ORDER BY sort_order, id')->fetchAll();
} catch (Throwable $e) {}

foreach ($members as $m) {
    $urls[] = [
        'loc'        => '/ekip/' . $m['slug'],
        'lastmod'    => $m['updated_at'] ?? null,
        'changefreq' => 'monthly',
        'priority'   => '0.5',
    ];
}

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php foreach ($urls as $u): ?>
  <url>
    <loc><?= htmlspecialchars($siteUrl . $u['loc'], ENT_XML1) ?></loc>
    <?php if (!empty($u['lastmod'])): ?>
    <lastmod><?= htmlspecialchars(date('c', strtotime($u['lastmod'])), ENT_XML1) ?></lastmod>
    <?php endif; ?>
    <changefreq><?= htmlspecialchars($u['changefreq'], ENT_XML1) ?></changefreq>
    <priority><?= htmlspecialchars($u['priority'], ENT_XML1) ?></priority>
  </url>
<?php endforeach; ?>
</urlset>
