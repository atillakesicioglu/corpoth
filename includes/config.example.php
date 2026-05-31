<?php
/**
 * Corpoth - Yapilandirma sablonu
 * Bu dosyayi config.php olarak kopyalayin ve gercek bilgileri girin.
 * config.php .gitignore'da oldugu icin commitlenmez.
 */

return [
    'db' => [
        'host'     => 'localhost',
        'name'     => 'corpoth_db',
        'user'     => 'corpoth_user',
        'pass'     => 'STRONG_PASSWORD_HERE',
        'charset'  => 'utf8mb4',
    ],

    'app' => [
        'name'        => 'Corpoth',
        'base_url'    => 'https://www.corpoth.com',
        'environment' => 'production', // 'development' veya 'production'
        'timezone'    => 'Europe/Istanbul',
        'debug'       => false,
    ],

    'mail' => [
        // PHP mail() fonksiyonu kullanilir; SMTP icin PHPMailer entegrasyonu eklenebilir
        'from_email' => 'no-reply@corpoth.com',
        'from_name'  => 'Corpoth Web',
    ],

    'security' => [
        'session_name'             => 'corpoth_admin',
        'login_max_attempts'       => 5,
        'login_lockout_minutes'    => 15,
        'csrf_token_lifetime_min'  => 60,
    ],

    'upload' => [
        'max_size_bytes' => 5 * 1024 * 1024,
        'allowed_mimes'  => [
            'image/jpeg',
            'image/png',
            'image/webp',
            'image/svg+xml',
            'image/gif',
        ],
        'directory' => __DIR__ . '/../uploads',
        'url_path'  => '/uploads',
    ],
];
