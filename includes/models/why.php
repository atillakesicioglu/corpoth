<?php
/**
 * Why block + value props.
 */

function why_block_get(): array
{
    $row = db()->query('SELECT * FROM why_block WHERE id = 1 LIMIT 1')->fetch();
    return $row ?: [];
}

function why_block_update(array $d): void
{
    $allowed = ['title', 'image_path', 'image_alt'];
    $sets = []; $params = [];
    foreach ($allowed as $f) {
        if (array_key_exists($f, $d)) {
            $sets[] = "`$f` = :$f";
            $params[":$f"] = $d[$f];
        }
    }
    if (!$sets) return;
    db()->prepare('UPDATE why_block SET ' . implode(', ', $sets) . ' WHERE id = 1')->execute($params);
}

function value_props_active(): array
{
    return db()->query('SELECT * FROM value_props WHERE active = 1 ORDER BY sort_order, id')->fetchAll();
}

function value_props_all(): array
{
    return db()->query('SELECT * FROM value_props ORDER BY sort_order, id')->fetchAll();
}

function value_prop_get(int $id): ?array
{
    $stmt = db()->prepare('SELECT * FROM value_props WHERE id = ?');
    $stmt->execute([$id]);
    return $stmt->fetch() ?: null;
}

function value_prop_create(array $d): int
{
    $stmt = db()->prepare('INSERT INTO value_props (icon, title, description, sort_order, active) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([
        $d['icon'] ?? 'task_alt',
        $d['title'] ?? '',
        $d['description'] ?? '',
        (int) ($d['sort_order'] ?? 0),
        !empty($d['active']) ? 1 : 0,
    ]);
    return (int) db()->lastInsertId();
}

function value_prop_update(int $id, array $d): void
{
    $stmt = db()->prepare('UPDATE value_props SET icon=?, title=?, description=?, sort_order=?, active=? WHERE id=?');
    $stmt->execute([
        $d['icon'] ?? 'task_alt',
        $d['title'] ?? '',
        $d['description'] ?? '',
        (int) ($d['sort_order'] ?? 0),
        !empty($d['active']) ? 1 : 0,
        $id,
    ]);
}

function value_prop_delete(int $id): void
{
    db()->prepare('DELETE FROM value_props WHERE id = ?')->execute([$id]);
}
