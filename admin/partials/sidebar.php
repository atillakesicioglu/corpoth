<?php
$activePage = $activePage ?? '';

$pendingMigrationsBadge = 0;
try {
    if (function_exists('migrations_pending')) {
        $pendingMigrationsBadge = count(migrations_pending());
    }
} catch (Throwable $e) {}

$nav = [
    ['group' => 'Genel', 'items' => [
        ['key' => 'dashboard',    'icon' => 'dashboard',         'label' => 'Panel',          'href' => '/admin/dashboard.php'],
        ['key' => 'leads',        'icon' => 'mark_email_unread', 'label' => 'Lead\'ler',      'href' => '/admin/leads.php'],
    ]],
    ['group' => 'İçerik', 'items' => [
        ['key' => 'hero',         'icon' => 'flag',              'label' => 'Hero',           'href' => '/admin/hero.php'],
        ['key' => 'service',      'icon' => 'health_and_safety', 'label' => 'Hizmet & Özellikler', 'href' => '/admin/service.php'],
        ['key' => 'audiences',    'icon' => 'groups',            'label' => 'Kimler İçin',    'href' => '/admin/audiences.php'],
        ['key' => 'benefits',     'icon' => 'star',              'label' => 'Faydalar',       'href' => '/admin/benefits.php'],
        ['key' => 'stats',        'icon' => 'leaderboard',       'label' => 'İstatistikler',  'href' => '/admin/stats.php'],
        ['key' => 'process',      'icon' => 'route',             'label' => 'Süreç Adımları', 'href' => '/admin/process.php'],
        ['key' => 'why',          'icon' => 'verified',          'label' => 'Neden Corpoth',  'href' => '/admin/why.php'],
        ['key' => 'scenarios',    'icon' => 'collections',       'label' => 'Senaryolar',     'href' => '/admin/scenarios.php'],
        ['key' => 'references',   'icon' => 'apartment',         'label' => 'Referanslar',    'href' => '/admin/references.php'],
        ['key' => 'testimonials', 'icon' => 'reviews',           'label' => 'Yorumlar',       'href' => '/admin/testimonials.php'],
        ['key' => 'faq',          'icon' => 'help',              'label' => 'SSS',            'href' => '/admin/faq.php'],
    ]],
    ['group' => 'Sayfalar & Blog', 'items' => [
        ['key' => 'pages',           'icon' => 'description',     'label' => 'Sayfa İçerikleri', 'href' => '/admin/pages.php'],
        ['key' => 'team',            'icon' => 'badge',           'label' => 'Ekip Üyeleri',     'href' => '/admin/team.php'],
        ['key' => 'blog-posts',      'icon' => 'article',         'label' => 'Blog Yazıları',    'href' => '/admin/blog-posts.php'],
        ['key' => 'blog-categories', 'icon' => 'sell',            'label' => 'Blog Kategorileri','href' => '/admin/blog-categories.php'],
    ]],
    ['group' => 'Yapılandırma', 'items' => [
        ['key' => 'nav',          'icon' => 'menu_book',         'label' => 'Header Menüsü',     'href' => '/admin/nav.php'],
        ['key' => 'contact',      'icon' => 'call',              'label' => 'İletişim Bilgileri', 'href' => '/admin/contact.php'],
        ['key' => 'settings',     'icon' => 'settings',          'label' => 'Genel Ayarlar',     'href' => '/admin/settings.php'],
        ['key' => 'media',        'icon' => 'image',             'label' => 'Medya',             'href' => '/admin/media.php'],
        ['key' => 'account',      'icon' => 'account_circle',    'label' => 'Hesabım',           'href' => '/admin/account.php'],
    ]],
    ['group' => 'Sistem', 'items' => [
        [
            'key'   => 'migrations',
            'icon'  => 'storage',
            'label' => 'Veritabanı Güncellemeleri',
            'href'  => '/admin/migrations.php',
            'badge' => $pendingMigrationsBadge > 0 ? $pendingMigrationsBadge : null,
        ],
    ]],
];
?>
<aside id="sidebar" class="w-64 bg-slate-900 text-slate-200 flex-shrink-0 hidden md:flex flex-col">
  <a href="/admin/dashboard.php" class="flex items-center gap-3 px-5 py-5 border-b border-white/10">
    <img src="/assets/images/corpoth-logo-white.png" alt="CORPOTH" class="h-8 w-auto"/>
    <span class="font-bold tracking-wide">Admin</span>
  </a>
  <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-6">
    <?php foreach ($nav as $section): ?>
    <div>
      <p class="px-3 text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-2"><?= e($section['group']) ?></p>
      <ul class="space-y-1">
        <?php foreach ($section['items'] as $item):
          $isActive = $activePage === $item['key'];
          $cls = $isActive
            ? 'bg-white/10 text-white'
            : 'text-slate-300 hover:bg-white/5 hover:text-white';
        ?>
        <li>
          <a href="<?= e($item['href']) ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors <?= $cls ?>">
            <span class="material-symbols-outlined text-[20px]"><?= e($item['icon']) ?></span>
            <span class="flex-1"><?= e($item['label']) ?></span>
            <?php if (!empty($item['badge'])): ?>
              <span class="inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 rounded-full bg-amber-500 text-white text-[11px] font-bold">
                <?= (int) $item['badge'] ?>
              </span>
            <?php endif; ?>
          </a>
        </li>
        <?php endforeach; ?>
      </ul>
    </div>
    <?php endforeach; ?>
  </nav>
</aside>
