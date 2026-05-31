<?php
/**
 * Admin users model.
 */

function user_find_by_username(string $username): ?array
{
    $stmt = db()->prepare('SELECT * FROM admin_users WHERE username = ? LIMIT 1');
    $stmt->execute([$username]);
    return $stmt->fetch() ?: null;
}

function user_get(int $id): ?array
{
    $stmt = db()->prepare('SELECT * FROM admin_users WHERE id = ?');
    $stmt->execute([$id]);
    return $stmt->fetch() ?: null;
}

function user_record_login(int $id): void
{
    db()->prepare('UPDATE admin_users SET last_login_at = NOW(), failed_attempts = 0 WHERE id = ?')->execute([$id]);
}

function user_increment_failed(int $id): void
{
    db()->prepare('UPDATE admin_users SET failed_attempts = failed_attempts + 1 WHERE id = ?')->execute([$id]);
}

function user_set_password(int $id, string $newPasswordHash): void
{
    db()->prepare('UPDATE admin_users SET password_hash = ?, must_change_password = 0 WHERE id = ?')->execute([$newPasswordHash, $id]);
}

/** Login deneme kaydi (rate limit icin) */
function login_attempt_record(string $ip, ?string $username, bool $success): void
{
    db()->prepare('INSERT INTO login_attempts (ip, username, success) VALUES (?, ?, ?)')->execute([$ip, $username, $success ? 1 : 0]);
}

/** Son N dakikadaki basarisiz deneme sayisi */
function login_attempt_recent_failures(string $ip, int $minutes = 15): int
{
    $stmt = db()->prepare('SELECT COUNT(*) FROM login_attempts WHERE ip = ? AND success = 0 AND created_at >= NOW() - INTERVAL ? MINUTE');
    $stmt->execute([$ip, $minutes]);
    return (int) $stmt->fetchColumn();
}
