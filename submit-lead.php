<?php
/**
 * Lead form endpoint. JSON response doner.
 *
 * Tum hatalari yakalar; client'a daima JSON doner, log dosyasina yazar.
 */

ini_set('display_errors', '0');
error_reporting(E_ALL);
header('Content-Type: application/json; charset=utf-8');

function lead_response(bool $ok, string $message, array $extra = [], int $code = 200): void
{
    http_response_code($code);
    echo json_encode(array_merge(['ok' => $ok, 'message' => $message], $extra), JSON_UNESCAPED_UNICODE);
    exit;
}

// Tum fatal hatalari yakala (PDOException dahil)
set_exception_handler(function (Throwable $e) {
    error_log('[submit-lead] Uncaught: ' . $e->getMessage() . ' @ ' . $e->getFile() . ':' . $e->getLine());
    lead_response(false, 'Sunucu hatasi olustu, lutfen birazdan tekrar deneyin.', [], 500);
});
set_error_handler(function ($severity, $message, $file, $line) {
    if (!(error_reporting() & $severity)) return false;
    throw new ErrorException($message, 0, $severity, $file, $line);
});

try {
    require_once __DIR__ . '/includes/bootstrap.php';
} catch (Throwable $e) {
    error_log('[submit-lead] Bootstrap failed: ' . $e->getMessage());
    lead_response(false, 'Yapilandirma hatasi.', [], 500);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    lead_response(false, 'Gecersiz istek metodu.', [], 405);
}

// Honeypot
if (!empty($_POST['website'])) {
    lead_response(true, 'Tesekkurler.');
}

$name    = trim((string) ($_POST['name']    ?? ''));
$email   = trim((string) ($_POST['email']   ?? ''));
$phone   = trim((string) ($_POST['phone']   ?? ''));
$company = trim((string) ($_POST['company'] ?? ''));
$emp     = trim((string) ($_POST['employees_range'] ?? ''));
$pos     = trim((string) ($_POST['position'] ?? ''));
$message = trim((string) ($_POST['message'] ?? ''));
$kvkk    = !empty($_POST['kvkk']);

$errors = [];
if ($name === '' || mb_strlen($name) > 190) {
    $errors['name'] = 'Ad Soyad zorunlu.';
}
if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Gecerli bir e-posta adresi girin.';
}
if ($company === '') {
    $errors['company'] = 'Sirket bilgisi zorunlu.';
}
if (!$kvkk) {
    $errors['kvkk'] = 'KVKK onayi gereklidir.';
}
if (mb_strlen($message) > 5000) {
    $errors['message'] = 'Mesaj cok uzun.';
}

if ($errors) {
    lead_response(false, 'Lutfen formdaki hatalari duzeltin.', ['errors' => $errors], 422);
}

// Kayit
$leadId = 0;
try {
    $leadId = lead_create([
        'name'            => $name,
        'email'           => $email,
        'phone'           => $phone,
        'company'         => $company,
        'employees_range' => $emp,
        'position'        => $pos,
        'message'         => $message,
        'ip'              => function_exists('client_ip') ? client_ip() : null,
        'user_agent'      => mb_substr((string) ($_SERVER['HTTP_USER_AGENT'] ?? ''), 0, 500),
        'referer'         => mb_substr((string) ($_SERVER['HTTP_REFERER']    ?? ''), 0, 500),
    ]);
} catch (Throwable $e) {
    error_log('[submit-lead] DB insert failed: ' . $e->getMessage());
    lead_response(false, 'Bir hata olustu, lutfen birazdan tekrar deneyin.', [], 500);
}

// Bildirim e-postasi (mail() yoksa veya disable ise sessizce gec)
try {
    $notifyTo = '';
    if (function_exists('setting')) {
        $notifyTo = setting('lead_notify_email', setting('contact_email'));
    }
    if ($notifyTo && function_exists('mail')) {
        $subject = '[Corpoth] Yeni teklif talebi: ' . $name . ' (' . $company . ')';
        $body  = "Yeni bir lead alindi.\n\n";
        $body .= "Ad Soyad : $name\n";
        $body .= "E-posta  : $email\n";
        $body .= "Telefon  : $phone\n";
        $body .= "Sirket   : $company\n";
        $body .= "Calisan  : $emp\n";
        $body .= "Pozisyon : $pos\n";
        $body .= "Mesaj    : $message\n\n";
        $body .= 'Admin panel: ' . rtrim((string) (function_exists('setting') ? setting('canonical_url', '') : ''), '/') . "/admin/leads.php\n";

        $fromEmail = $GLOBALS['CORPOTH_CONFIG']['mail']['from_email'] ?? 'no-reply@corpoth.com';
        $fromName  = $GLOBALS['CORPOTH_CONFIG']['mail']['from_name']  ?? 'Corpoth';

        $headers  = 'From: ' . $fromName . ' <' . $fromEmail . ">\r\n";
        $headers .= 'Reply-To: ' . $email . "\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

        @mail($notifyTo, '=?UTF-8?B?' . base64_encode($subject) . '?=', $body, $headers);
    }
} catch (Throwable $e) {
    error_log('[submit-lead] Mail send failed (non-fatal): ' . $e->getMessage());
}

lead_response(true, 'Tesekkurler! Talebiniz alindi, en kisa surede donus yapacagiz.', ['id' => $leadId]);
