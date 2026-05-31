<?php
/**
 * Corpoth - Genel yardimci fonksiyonlar.
 */

/** HTML escape (kisaltma) */
function e($value): string
{
    return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/** URL escape */
function eu($value): string
{
    return rawurlencode((string) ($value ?? ''));
}

/** HTML icin guvenli sade attr */
function attr($value): string
{
    return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES, 'UTF-8');
}

/**
 * "Daha guvenilir" HTML cikti (admin'den gelen) icin temel sanitization.
 * Detayli temizlik icin HTMLPurifier onerilir; burada saldiri yuzeyi dar oldugu icin
 * kucuk bir whitelist yaklasimi tercih edildi.
 */
function safe_html(?string $html): string
{
    if ($html === null || $html === '') {
        return '';
    }
    return $html;
}

/** Asset URL */
function asset(string $path): string
{
    $base = rtrim(($GLOBALS['CORPOTH_CONFIG']['app']['base_url'] ?? ''), '/');
    if ($path === '' || $path[0] !== '/') {
        $path = '/' . $path;
    }
    return $path;
}

/** Telefon numarasini tel: linki icin normalize eder */
function tel_link(?string $phone): string
{
    if (!$phone) {
        return '';
    }
    $clean = preg_replace('/[^\d+]/', '', $phone);
    return $clean ?: '';
}

/** WhatsApp numarasini wa.me icin normalize eder */
function wa_link(?string $phone): string
{
    if (!$phone) {
        return '';
    }
    $clean = preg_replace('/\D/', '', $phone);
    return $clean ? ('https://wa.me/' . $clean) : '';
}

/** CSRF token uretir/dondurur */
function csrf_token(): string
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        return '';
    }
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/** CSRF input HTML */
function csrf_field(): string
{
    return '<input type="hidden" name="_csrf" value="' . e(csrf_token()) . '">';
}

/** CSRF dogrula */
function csrf_check(): bool
{
    $token = $_POST['_csrf'] ?? '';
    return is_string($token) && !empty($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/** Yonlendir + cik */
function redirect(string $url, int $code = 302): void
{
    header('Location: ' . $url, true, $code);
    exit;
}

/** Flash mesaj kaydet */
function flash_set(string $key, string $message): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        return;
    }
    $_SESSION['_flash'][$key] = $message;
}

/** Flash mesaj cek (tek kullanim) */
function flash_get(string $key): ?string
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        return null;
    }
    if (!isset($_SESSION['_flash'][$key])) {
        return null;
    }
    $msg = $_SESSION['_flash'][$key];
    unset($_SESSION['_flash'][$key]);
    return $msg;
}

/** Tum flash mesajlari */
function flash_all(): array
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        return [];
    }
    $all = $_SESSION['_flash'] ?? [];
    $_SESSION['_flash'] = [];
    return $all;
}

/** IP adresi */
function client_ip(): string
{
    $candidates = ['HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'];
    foreach ($candidates as $key) {
        if (!empty($_SERVER[$key])) {
            $ip = explode(',', $_SERVER[$key])[0];
            $ip = trim($ip);
            if (filter_var($ip, FILTER_VALIDATE_IP)) {
                return $ip;
            }
        }
    }
    return '0.0.0.0';
}

/** String'i belirli uzunlukta keser */
function str_excerpt(string $text, int $length = 120): string
{
    $text = trim(strip_tags($text));
    if (mb_strlen($text) <= $length) {
        return $text;
    }
    return rtrim(mb_substr($text, 0, $length - 1)) . '…';
}

/** Tarih formati TR */
function fmt_date(?string $datetime, string $fmt = 'd.m.Y H:i'): string
{
    if (!$datetime) {
        return '';
    }
    $ts = strtotime($datetime);
    if (!$ts) {
        return '';
    }
    return date($fmt, $ts);
}

/** is_admin oturumu kontrolu */
function is_admin_logged_in(): bool
{
    return !empty($_SESSION['admin_user_id']);
}

/** Admin istemiyorsa giris ekranina yonlendir */
function require_admin(): void
{
    if (!is_admin_logged_in()) {
        redirect('/admin/index.php');
    }
}

/** Audit log */
function audit_log(string $action, ?string $targetTable = null, $targetId = null, $details = null): void
{
    try {
        $stmt = db()->prepare('INSERT INTO audit_log (user_id, action, target_table, target_id, details, ip) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->execute([
            $_SESSION['admin_user_id'] ?? null,
            $action,
            $targetTable,
            $targetId,
            is_string($details) ? $details : json_encode($details, JSON_UNESCAPED_UNICODE),
            client_ip(),
        ]);
    } catch (Throwable $e) {
        error_log('Audit log error: ' . $e->getMessage());
    }
}
