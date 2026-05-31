<?php
/**
 * Corpoth - PDO baglantisi (singleton).
 */

function db(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $config = $GLOBALS['CORPOTH_CONFIG']['db'] ?? null;
    if (!$config) {
        throw new RuntimeException('Database config eksik.');
    }

    $dsn = sprintf(
        'mysql:host=%s;dbname=%s;charset=%s',
        $config['host'],
        $config['name'],
        $config['charset'] ?? 'utf8mb4'
    );

    try {
        $pdo = new PDO($dsn, $config['user'], $config['pass'], [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8mb4' COLLATE 'utf8mb4_unicode_ci'",
        ]);
    } catch (PDOException $e) {
        if (!empty($GLOBALS['CORPOTH_CONFIG']['app']['debug'])) {
            throw $e;
        }
        error_log('Corpoth DB error: ' . $e->getMessage());
        throw new RuntimeException('Veritabanina ulasilamadi.');
    }

    return $pdo;
}
