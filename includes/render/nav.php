<?php
/**
 * Kurumsal nav: dropdown menu destegi + tam ekran mobil overlay.
 *
 * Beklenen degiskenler (opsiyonel):
 *   $current_page  - 'home'|'service'|'about'|'team'|'references'|'blog'|'faq'|'contact'|'kvkk'|'privacy'
 */

$current = $current_page ?? 'home';

// DB'den nav menusu (varsa). Fallback: hardcoded array.
$navItems = function_exists('nav_tree') ? nav_tree() : null;
if (!$navItems) {
    $navItems = [
        ['type'  => 'link',     'key'   => 'home',     'label' => 'Anasayfa',  'href'  => '/'],
        ['type'  => 'link',     'key'   => 'service',  'label' => 'Ne yapıyoruz?', 'href'  => '/ne-yapiyoruz.php'],
        [
            'type'    => 'dropdown',
            'key'     => 'corporate',
            'label'   => 'Kurumsal',
            'children_keys' => ['about', 'team', 'references'],
            'items'   => [
                ['icon' => 'corporate_fare', 'label' => 'Hakkımızda',  'href' => '/hakkimizda.php', 'desc' => 'Misyonumuz, vizyonumuz, değerlerimiz', 'key' => 'about'],
                ['icon' => 'groups',         'label' => 'Ekip',        'href' => '/ekip.php',       'desc' => 'Kurucu ve uzman kadromuz',       'key' => 'team'],
                ['icon' => 'apartment',      'label' => 'Referanslar', 'href' => '/referanslar.php','desc' => 'Birlikte çalıştığımız markalar', 'key' => 'references'],
            ],
        ],
        [
            'type'    => 'dropdown',
            'key'     => 'resources',
            'label'   => 'Bilgi',
            'children_keys' => ['blog', 'faq'],
            'items'   => [
                ['icon' => 'article', 'label' => 'Blog', 'href' => '/blog.php', 'desc' => 'İçgörü ve uzman yazıları', 'key' => 'blog'],
                ['icon' => 'help',    'label' => 'SSS',  'href' => '/sss.php',  'desc' => 'Sıkça sorulan sorular',  'key' => 'faq'],
            ],
        ],
        ['type'  => 'link',     'key'   => 'contact',  'label' => 'İletişim',  'href'  => '/iletisim.php'],
    ];
}

$isActive = function ($item) use ($current) {
    if ($item['type'] === 'link') {
        return $item['key'] === $current;
    }
    return in_array($current, $item['children_keys'] ?? [], true);
};
?>
<nav id="site-nav" class="fixed top-0 w-full z-50 bg-white/85 backdrop-blur-xl border-b border-outline-variant/10" aria-label="Ana navigasyon">
  <div class="flex justify-between items-center gap-4 px-6 md:px-12 w-full max-w-screen-2xl mx-auto h-20">
    <a href="/" class="shrink-0 flex items-center group" aria-label="Corpoth Anasayfa">
      <img src="/assets/images/corpoth-logo.png" alt="CORPOTH" class="site-logo h-14 md:h-[4.25rem] w-auto transition-transform duration-300 group-hover:scale-105" />
    </a>

    <div class="hidden md:flex items-center gap-x-1 font-sans text-sm font-medium tracking-wide">
      <?php foreach ($navItems as $i => $item): ?>
        <?php $active = $isActive($item); ?>
        <?php if ($item['type'] === 'link'): ?>
          <a class="nav-link px-3 py-2<?= $active ? ' nav-link-active' : '' ?>" href="<?= e($item['href']) ?>"><?= e($item['label']) ?></a>
        <?php else: ?>
          <div class="nav-dropdown" data-dropdown>
            <button type="button" class="nav-link nav-dropdown-trigger px-3 py-2 inline-flex items-center gap-1<?= $active ? ' nav-link-active' : '' ?>" aria-expanded="false" aria-haspopup="true">
              <?= e($item['label']) ?>
              <span class="material-symbols-outlined text-base nav-dropdown-chevron">expand_more</span>
            </button>
            <div class="nav-dropdown-panel" role="menu">
              <div class="nav-dropdown-inner">
                <?php foreach ($item['items'] as $sub): ?>
                <a href="<?= e($sub['href']) ?>" class="nav-dropdown-item<?= $sub['key'] === $current ? ' is-active' : '' ?>" role="menuitem">
                  <span class="nav-dropdown-icon">
                    <span class="material-symbols-outlined"><?= e($sub['icon']) ?></span>
                  </span>
                  <span class="nav-dropdown-text">
                    <span class="nav-dropdown-title"><?= e($sub['label']) ?></span>
                    <span class="nav-dropdown-desc"><?= e($sub['desc']) ?></span>
                  </span>
                </a>
                <?php endforeach; ?>
              </div>
            </div>
          </div>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>

    <div class="flex items-center gap-2 shrink-0">
      <a href="/iletisim.php#form" class="hidden md:inline-flex primary-gradient text-white px-5 py-2.5 rounded-xl font-label text-sm uppercase tracking-wider font-semibold shadow-md hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300 no-underline">Teklif Al</a>
      <button type="button" id="nav-toggle" class="nav-toggle md:hidden" aria-expanded="false" aria-controls="mobile-menu" aria-label="Menüyü aç">
        <span class="nav-toggle-box" aria-hidden="true">
          <span class="nav-toggle-bar"></span>
          <span class="nav-toggle-bar"></span>
          <span class="nav-toggle-bar"></span>
        </span>
      </button>
    </div>
  </div>
</nav>

<!-- Tam ekran mobil overlay menu (accordion alt menulerle) -->
<div id="mobile-menu" class="md:hidden" aria-hidden="true">
  <div class="mobile-menu-bg"></div>
  <nav class="mobile-menu-inner" aria-label="Mobil navigasyon">
    <a href="/" class="mobile-logo" aria-label="Corpoth Anasayfa">
      <img src="/assets/images/corpoth-logo.png" alt="CORPOTH" class="site-logo h-16 w-auto"/>
    </a>
    <ul class="mobile-menu-list">
      <?php foreach ($navItems as $item): ?>
        <?php if ($item['type'] === 'link'): ?>
          <li>
            <a href="<?= e($item['href']) ?>" class="<?= $item['key'] === $current ? 'is-current' : '' ?>"><?= e($item['label']) ?></a>
          </li>
        <?php else: ?>
          <li class="mobile-acc<?= $isActive($item) ? ' is-open' : '' ?>" data-mobile-acc>
            <button type="button" class="mobile-acc-head">
              <span class="mobile-acc-label"><?= e($item['label']) ?></span>
              <span class="material-symbols-outlined mobile-acc-chev">expand_more</span>
            </button>
            <ul class="mobile-acc-body">
              <?php foreach ($item['items'] as $sub): ?>
                <li>
                  <a href="<?= e($sub['href']) ?>" class="mobile-acc-item<?= $sub['key'] === $current ? ' is-current' : '' ?>">
                    <span class="material-symbols-outlined"><?= e($sub['icon']) ?></span>
                    <span><?= e($sub['label']) ?></span>
                  </a>
                </li>
              <?php endforeach; ?>
            </ul>
          </li>
        <?php endif; ?>
      <?php endforeach; ?>
    </ul>
    <a href="/iletisim.php#form" class="mobile-menu-cta primary-gradient">
      <span class="material-symbols-outlined">send</span>
      Teklif Al
    </a>
    <div class="mobile-menu-foot">
      <span>© <?= date('Y') ?> CORPOTH</span>
    </div>
  </nav>
</div>
