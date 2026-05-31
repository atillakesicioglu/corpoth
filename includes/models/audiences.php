<?php
/**
 * Audiences ("Kimler Icin?") model.
 */

function audiences_active(): array
{
    return db()->query('SELECT * FROM audiences WHERE active = 1 ORDER BY sort_order, id')->fetchAll();
}

function audiences_all(): array
{
    return db()->query('SELECT * FROM audiences ORDER BY sort_order, id')->fetchAll();
}

function audience_get(int $id): ?array
{
    $stmt = db()->prepare('SELECT * FROM audiences WHERE id = ?');
    $stmt->execute([$id]);
    return $stmt->fetch() ?: null;
}

function audience_create(array $d): int
{
    $stmt = db()->prepare('INSERT INTO audiences (icon, title, description, sort_order, active) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([
        $d['icon'] ?? 'groups',
        $d['title'] ?? '',
        $d['description'] ?? '',
        (int) ($d['sort_order'] ?? 0),
        !empty($d['active']) ? 1 : 0,
    ]);
    return (int) db()->lastInsertId();
}

function audience_update(int $id, array $d): void
{
    $stmt = db()->prepare('UPDATE audiences SET icon=?, title=?, description=?, sort_order=?, active=? WHERE id=?');
    $stmt->execute([
        $d['icon'] ?? 'groups',
        $d['title'] ?? '',
        $d['description'] ?? '',
        (int) ($d['sort_order'] ?? 0),
        !empty($d['active']) ? 1 : 0,
        $id,
    ]);
}

function audience_delete(int $id): void
{
    db()->prepare('DELETE FROM audiences WHERE id = ?')->execute([$id]);
}
