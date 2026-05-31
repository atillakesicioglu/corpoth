<?php
/**
 * Corpoth - Tum public/admin sayfalarinin yukledigi cekirdek bootstrap.
 * Config yuklenir, hata raporlama, timezone ve PDO baglantisi hazirlanir.
 */

if (!defined('CORPOTH_ROOT')) {
    define('CORPOTH_ROOT', dirname(__DIR__));
}

$configPath = CORPOTH_ROOT . '/includes/config.php';
if (!file_exists($configPath)) {
    http_response_code(500);
    echo '<h1>Yapılandırma eksik</h1><p><code>includes/config.php</code> dosyası bulunamadı. Lütfen <code>config.example.php</code> dosyasını kopyalayıp ayarlarınızı girin.</p>';
    exit;
}

$CONFIG = require $configPath;

if (!is_array($CONFIG) || empty($CONFIG['db'])) {
    http_response_code(500);
    echo '<h1>Yapılandırma hatası</h1><p>config.php geçerli bir dizi döndürmüyor.</p>';
    exit;
}

date_default_timezone_set($CONFIG['app']['timezone'] ?? 'Europe/Istanbul');

if (!empty($CONFIG['app']['debug'])) {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
}

require_once CORPOTH_ROOT . '/includes/db.php';
require_once CORPOTH_ROOT . '/includes/helpers.php';

// Tum modeller (light-weight olduklari icin hep beraber yuklenir)
foreach (glob(CORPOTH_ROOT . '/includes/models/*.php') as $modelFile) {
    require_once $modelFile;
}

$GLOBALS['CORPOTH_CONFIG'] = $CONFIG;
