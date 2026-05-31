<?php
/**
 * References (logo bandi) model.
 */

function references_active(): array
{
    return db()->query('SELECT * FROM references_logos WHERE active = 1 ORDER BY sort_order, id')->fetchAll();
}

function references_all(): array
{
    return db()->query('SELECT * FROM references_logos ORDER BY sort_order, id')->fetchAll();
}

function reference_get(int $id): ?array
{
    $stmt = db()->prepare('SELECT * FROM references_logos WHERE id = ?');
    $stmt->execute([$id]);
    return $stmt->fetch() ?: null;
}

function reference_create(array $d): int
{
    $stmt = db()->prepare('INSERT INTO references_logos (name, logo_path, url, sort_order, active) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([
        $d['name'] ?? '',
        $d['logo_path'] ?? null,
        $d['url'] ?? null,
        (int) ($d['sort_order'] ?? 0),
        !empty($d['active']) ? 1 : 0,
    ]);
    return (int) db()->lastInsertId();
}

function reference_update(int $id, array $d): void
{
    $stmt = db()->prepare('UPDATE references_logos SET name=?, logo_path=?, url=?, sort_order=?, active=? WHERE id=?');
    $stmt->execute([
        $d['name'] ?? '',
        $d['logo_path'] ?? null,
        $d['url'] ?? null,
        (int) ($d['sort_order'] ?? 0),
        !empty($d['active']) ? 1 : 0,
        $id,
    ]);
}

function reference_delete(int $id): void
{
    db()->prepare('DELETE FROM references_logos WHERE id = ?')->execute([$id]);
}
