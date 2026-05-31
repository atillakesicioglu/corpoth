<?php
require_once __DIR__ . '/includes/bootstrap.php';

$page_title       = 'Gizlilik Politikası | CORPOTH';
$page_description = 'CORPOTH gizlilik politikası — sitemizdeki kişisel veri uygulamalarımız.';
$is_subpage       = true;

require __DIR__ . '/includes/render/head.php';
?>
<body class="bg-surface text-on-surface font-body">
<a href="#main" class="skip-link">İçeriğe geç</a>
<?php require __DIR__ . '/includes/render/nav.php'; ?>
<main id="main" class="pt-28 pb-24 px-6 md:px-12">
  <article class="max-w-3xl mx-auto prose prose-slate prose-headings:tracking-tight">
    <?= safe_html(setting('privacy_html')) ?>
  </article>
</main>
<?php require __DIR__ . '/includes/render/footer.php'; ?>
<?php require __DIR__ . '/includes/render/cookie_banner.php'; ?>
<script src="/assets/js/main.js" defer></script>
</body>
</html>
