<?php
require_once __DIR__ . '/includes/bootstrap.php';

header('Content-Type: application/json; charset=utf-8');

$wantsJson = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

function lead_response(bool $ok, string $message, array $extra = [], int $code = 200): void
{
    http_response_code($code);
    echo json_encode(array_merge(['ok' => $ok, 'message' => $message], $extra), JSON_UNESCAPED_UNICODE);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    lead_response(false, 'Geçersiz istek metodu.', [], 405);
}

// Honeypot
if (!empty($_POST['website'])) {
    lead_response(true, 'Teşekkürler.');
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
    $errors['email'] = 'Geçerli bir e-posta adresi girin.';
}
if ($company === '') {
    $errors['company'] = 'Şirket bilgisi zorunlu.';
}
if (!$kvkk) {
    $errors['kvkk'] = 'KVKK onayı gereklidir.';
}
if (mb_strlen($message) > 5000) {
    $errors['message'] = 'Mesaj çok uzun.';
}

if ($errors) {
    lead_response(false, 'Lütfen formdaki hataları düzeltin.', ['errors' => $errors], 422);
}

try {
    $leadId = lead_create([
        'name'            => $name,
        'email'           => $email,
        'phone'           => $phone,
        'company'         => $company,
        'employees_range' => $emp,
        'position'        => $pos,
        'message'         => $message,
        'ip'              => client_ip(),
        'user_agent'      => mb_substr((string) ($_SERVER['HTTP_USER_AGENT'] ?? ''), 0, 500),
        'referer'         => mb_substr((string) ($_SERVER['HTTP_REFERER']    ?? ''), 0, 500),
    ]);
} catch (Throwable $e) {
    error_log('Lead create failed: ' . $e->getMessage());
    lead_response(false, 'Bir hata oluştu, lütfen birazdan tekrar deneyin.', [], 500);
}

// Bildirim e-postasi (opsiyonel - mail() fonksiyonu kullanir)
$notifyTo = setting('lead_notify_email', setting('contact_email'));
if ($notifyTo) {
    $subject = '[Corpoth] Yeni teklif talebi: ' . $name . ' (' . $company . ')';
    $body  = "Yeni bir lead alindi.\n\n";
    $body .= "Ad Soyad : $name\n";
    $body .= "E-posta  : $email\n";
    $body .= "Telefon  : $phone\n";
    $body .= "Sirket   : $company\n";
    $body .= "Calisan  : $emp\n";
    $body .= "Pozisyon : $pos\n";
    $body .= "Mesaj    : $message\n\n";
    $body .= "Admin panel: " . rtrim(setting('canonical_url', ''), '/') . "/admin/leads.php\n";

    $headers  = 'From: ' . ($GLOBALS['CORPOTH_CONFIG']['mail']['from_name'] ?? 'Corpoth') . ' <' . ($GLOBALS['CORPOTH_CONFIG']['mail']['from_email'] ?? 'no-reply@corpoth.com') . ">\r\n";
    $headers .= 'Reply-To: ' . $email . "\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    @mail($notifyTo, '=?UTF-8?B?' . base64_encode($subject) . '?=', $body, $headers);
}

lead_response(true, 'Teşekkürler! Talebiniz alındı, en kısa sürede dönüş yapacağız.', ['id' => $leadId]);
