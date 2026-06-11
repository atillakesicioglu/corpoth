<?php
/**
 * <head> rendering. Hem index.php hem KVKK / gizlilik sayfalarinda kullanilir.
 *
 * Beklenen degiskenler (opsiyonel):
 *   $page_title        - bu sayfaya ozel <title>
 *   $page_description  - bu sayfaya ozel meta description
 *   $page_canonical    - bu sayfaya ozel canonical URL
 *   $page_og_image     - bu sayfaya ozel og:image
 *   $extra_head        - ek head HTML (string)
 */

$siteTitle    = setting('site_title', 'CORPOTH | Kurumsal Esenlik ve Terapi');
$siteDesc     = setting('site_description', '');
$canonical    = $page_canonical ?? setting('canonical_url', '');
$ogImage      = $page_og_image ?? setting('og_image', '/assets/images/cover.png');
$ogImageAbs   = (preg_match('#^https?://#', $ogImage)) ? $ogImage : rtrim(setting('canonical_url', ''), '/') . $ogImage;
$titleTag     = $page_title ?? $siteTitle;
$descTag      = $page_description ?? $siteDesc;
$gaId         = trim(setting('ga_id')) ?: 'G-MMYN1054NB';
$clarityId    = trim(setting('clarity_id'));
$contactPhone = setting('contact_phone');
$contactEmail = setting('contact_email');
?>
<!DOCTYPE html>
<html class="scroll-smooth no-js" lang="tr">
<head>
<?php if ($gaId): ?>
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=<?= e($gaId) ?>"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', '<?= e($gaId) ?>');
</script>
<?php endif; ?>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title><?= e($titleTag) ?></title>
<meta name="description" content="<?= e($descTag) ?>"/>
<?php if (setting('site_keywords')): ?>
<meta name="keywords" content="<?= e(setting('site_keywords')) ?>"/>
<?php endif; ?>
<?php if ($canonical): ?>
<link rel="canonical" href="<?= e($canonical) ?>"/>
<?php endif; ?>

<!-- Favicon -->
<link rel="icon" type="image/png" sizes="32x32" href="/assets/images/corpoth-logo-icon.png"/>
<link rel="apple-touch-icon" href="/assets/images/corpoth-logo-icon.png"/>

<!-- Open Graph -->
<meta property="og:type" content="website"/>
<meta property="og:title" content="<?= e($titleTag) ?>"/>
<meta property="og:description" content="<?= e($descTag) ?>"/>
<?php if ($canonical): ?>
<meta property="og:url" content="<?= e($canonical) ?>"/>
<?php endif; ?>
<meta property="og:image" content="<?= e($ogImageAbs) ?>"/>
<meta property="og:locale" content="tr_TR"/>
<meta property="og:site_name" content="CORPOTH"/>

<!-- Twitter -->
<meta name="twitter:card" content="summary_large_image"/>
<meta name="twitter:title" content="<?= e($titleTag) ?>"/>
<meta name="twitter:description" content="<?= e($descTag) ?>"/>
<meta name="twitter:image" content="<?= e($ogImageAbs) ?>"/>

<!-- Tailwind (CDN; production icin CLI build kullanilabilir) -->
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>

<script id="tailwind-config">
  tailwind.config = {
    darkMode: "class",
    theme: {
      extend: {
        colors: {
          "secondary-fixed-dim": "#abcae5",
          "on-error-container": "#93000a",
          "surface-container-high": "#eae8e7",
          "on-tertiary-fixed": "#00201e",
          "surface-container-low": "#f5f3f3",
          "surface-dim": "#dbd9d9",
          "surface-container": "#f0eded",
          "secondary-container": "#c4e3ff",
          "tertiary": "#002c2a",
          "surface-variant": "#e4e2e2",
          "tertiary-container": "#004441",
          "surface-container-highest": "#e4e2e2",
          "on-primary": "#ffffff",
          "inverse-on-surface": "#f2f0f0",
          "background": "#fbf9f8",
          "inverse-surface": "#303030",
          "on-tertiary": "#ffffff",
          "on-primary-fixed": "#001a41",
          "on-surface": "#1b1c1c",
          "on-primary-container": "#8aa7e1",
          "on-tertiary-container": "#71b2ad",
          "tertiary-fixed": "#acefe9",
          "on-secondary-fixed": "#001e2f",
          "secondary": "#446279",
          "on-tertiary-fixed-variant": "#00504c",
          "on-background": "#1b1c1c",
          "tertiary-fixed-dim": "#91d2cd",
          "outline": "#747780",
          "on-surface-variant": "#43474f",
          "on-secondary-container": "#48667d",
          "primary-container": "#1b3b6f",
          "inverse-primary": "#acc7ff",
          "surface-tint": "#415e94",
          "primary": "#002555",
          "primary-fixed": "#d8e2ff",
          "error": "#ba1a1a",
          "primary-fixed-dim": "#acc7ff",
          "secondary-fixed": "#cae6ff",
          "on-secondary": "#ffffff",
          "outline-variant": "#c4c6d1",
          "surface-container-lowest": "#ffffff",
          "on-secondary-fixed-variant": "#2b4a60",
          "surface-bright": "#fbf9f8",
          "on-error": "#ffffff",
          "error-container": "#ffdad6",
          "on-primary-fixed-variant": "#28467b",
          "surface": "#fbf9f8"
        },
        borderRadius: { "DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "1.5rem", "full": "9999px" },
        fontFamily: { "headline": ["Inter"], "body": ["Inter"], "label": ["Inter"] }
      }
    }
  }
</script>

<style>
  html { scroll-padding-top: 5.5rem; }
  body { font-family: 'Inter', sans-serif; }
  .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
  .editorial-shadow { box-shadow: 0 12px 42px -4px rgba(27, 28, 28, 0.06); }
  .primary-gradient { background: linear-gradient(135deg, #002555 0%, #1b3b6f 100%); }
  .skip-link {
    position: absolute; left: -9999px; top: 0; z-index: 100;
    background: #002555; color: #fff; padding: .75rem 1rem; border-radius: 0 0 .5rem 0;
    text-decoration: none; font-weight: 600;
  }
  .skip-link:focus { left: 0; }
</style>
<?php
$assetRoot = __DIR__ . '/../../assets';
$cssV = @filemtime($assetRoot . '/css/animations.css') ?: time();
$siteV = @filemtime($assetRoot . '/css/site.css') ?: $cssV;
?>
<link rel="stylesheet" href="/assets/css/site.css?v=<?= $siteV ?>"/>
<link rel="stylesheet" href="/assets/css/animations.css?v=<?= $cssV ?>"/>

<?php
// Schema.org JSON-LD
$siteUrl = rtrim(setting('canonical_url', ''), '/');
$jsonLd = [
    '@context' => 'https://schema.org',
    '@graph'   => [
        [
            '@type'       => 'Organization',
            '@id'         => $siteUrl . '/#organization',
            'name'        => 'CORPOTH',
            'url'         => setting('canonical_url'),
            'logo'        => $siteUrl . '/assets/images/corpoth-logo.png',
            'sameAs'      => array_values(array_filter([
                setting('contact_linkedin'),
            ])),
            'contactPoint' => [
                '@type'       => 'ContactPoint',
                'telephone'   => $contactPhone,
                'email'       => $contactEmail,
                'contactType' => 'customer service',
                'areaServed'  => 'TR',
                'availableLanguage' => ['tr'],
            ],
        ],
        [
            '@type'       => 'Service',
            'serviceType' => 'Kurumsal Omurga Terapisi',
            'provider'    => [ '@id' => $siteUrl . '/#organization' ],
            'areaServed'  => ['Istanbul', 'Ankara', 'Izmir', 'Bursa', 'Kocaeli'],
            'description' => $siteDesc,
        ],
    ],
];

// Sayfaya ozel breadcrumb JSON-LD (BreadcrumbList)
if (!empty($page_breadcrumb) && is_array($page_breadcrumb)) {
    $bcItems = [];
    $pos = 1;
    $bcItems[] = [
        '@type'    => 'ListItem',
        'position' => $pos++,
        'name'     => 'Anasayfa',
        'item'     => $siteUrl . '/',
    ];
    foreach ($page_breadcrumb as $c) {
        $bcItems[] = [
            '@type'    => 'ListItem',
            'position' => $pos++,
            'name'     => $c['label'] ?? '',
            'item'     => !empty($c['href']) ? ($siteUrl . $c['href']) : null,
        ];
    }
    $jsonLd['@graph'][] = [
        '@type'           => 'BreadcrumbList',
        'itemListElement' => array_values(array_filter($bcItems, function ($x) {
            return !($x['position'] > 1 && empty($x['item']));
        })),
    ];
}

// Sayfaya ozel ek schema (BlogPosting, AboutPage vs.) - $page_jsonld parametresi ile
if (!empty($page_jsonld) && is_array($page_jsonld)) {
    if (isset($page_jsonld['@type'])) {
        $jsonLd['@graph'][] = $page_jsonld;
    } else {
        foreach ($page_jsonld as $entity) {
            $jsonLd['@graph'][] = $entity;
        }
    }
}

// FAQ schema sadece anasayfada veya /sss sayfasinda
if (!empty($include_faq_schema) && function_exists('faq_active')) {
    $faqRows = faq_active();
    if ($faqRows) {
        $faqEntities = [];
        foreach ($faqRows as $f) {
            $faqEntities[] = [
                '@type' => 'Question',
                'name'  => $f['question'],
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text'  => strip_tags($f['answer']),
                ],
            ];
        }
        $jsonLd['@graph'][] = [
            '@type'      => 'FAQPage',
            'mainEntity' => $faqEntities,
        ];
    }
}
?>
<script type="application/ld+json"><?= json_encode($jsonLd, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?></script>

<?php if ($clarityId): ?>
<!-- Microsoft Clarity -->
<script>
  (function(c,l,a,r,i,t,y){
    c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
    t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
    y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
  })(window, document, "clarity", "script", "<?= e($clarityId) ?>");
</script>
<?php endif; ?>

<?php if (!empty($extra_head)) echo $extra_head; ?>
</head>
