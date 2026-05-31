<?php
/**
 * Scenarios model.
 */

function scenarios_active(): array
{
    return db()->query('SELECT * FROM scenarios WHERE active = 1 ORDER BY sort_order, id')->fetchAll();
}

function scenarios_all(): array
{
    return db()->query('SELECT * FROM scenarios ORDER BY sort_order, id')->fetchAll();
}

function scenario_get(int $id): ?array
{
    $stmt = db()->prepare('SELECT * FROM scenarios WHERE id = ?');
    $stmt->execute([$id]);
    return $stmt->fetch() ?: null;
}

function scenario_create(array $d): int
{
    $stmt = db()->prepare('INSERT INTO scenarios (title, description, image_path, image_alt, is_text_card, icon, sort_order, active) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
    $stmt->execute([
        $d['title'] ?? '',
        $d['description'] ?? null,
        $d['image_path'] ?? null,
        $d['image_alt'] ?? null,
        !empty($d['is_text_card']) ? 1 : 0,
        $d['icon'] ?? null,
        (int) ($d['sort_order'] ?? 0),
        !empty($d['active']) ? 1 : 0,
    ]);
    return (int) db()->lastInsertId();
}

function scenario_update(int $id, array $d): void
{
    $stmt = db()->prepare('UPDATE scenarios SET title=?, description=?, image_path=?, image_alt=?, is_text_card=?, icon=?, sort_order=?, active=? WHERE id=?');
    $stmt->execute([
        $d['title'] ?? '',
        $d['description'] ?? null,
        $d['image_path'] ?? null,
        $d['image_alt'] ?? null,
        !empty($d['is_text_card']) ? 1 : 0,
        $d['icon'] ?? null,
        (int) ($d['sort_order'] ?? 0),
        !empty($d['active']) ? 1 : 0,
        $id,
    ]);
}

function scenario_delete(int $id): void
{
    db()->prepare('DELETE FROM scenarios WHERE id = ?')->execute([$id]);
}
