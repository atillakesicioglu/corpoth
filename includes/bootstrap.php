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
    if (PHP_SAPI === 'cli') {
        fwrite(STDERR, "Hata: includes/config.php bulunamadi. install.php uzerinden kurulum yapin.\n");
        exit(1);
    }
    // AJAX/JSON istekleri icin JSON, browser icin HTML don
    $accept = $_SERVER['HTTP_ACCEPT'] ?? '';
    $xrw    = $_SERVER['HTTP_X_REQUESTED_WITH'] ?? '';
    $isJson = strtolower($xrw) === 'xmlhttprequest' || str_contains($accept, 'application/json');

    http_response_code(500);
    if ($isJson) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'ok' => false,
            'message' => 'Yapilandirma eksik. Sunucuda includes/config.php henuz olusturulmamis. install.php calistirin.',
        ], JSON_UNESCAPED_UNICODE);
    } else {
        echo '<h1>Yapılandırma eksik</h1><p><code>includes/config.php</code> dosyası bulunamadı. Lütfen <a href="/install.php">install.php</a> üzerinden kurulumu tamamlayın.</p>';
    }
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

foreach (glob(CORPOTH_ROOT . '/includes/models/*.php') as $modelFile) {
    require_once $modelFile;
}

$GLOBALS['CORPOTH_CONFIG'] = $CONFIG;
