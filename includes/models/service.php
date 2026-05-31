<?php
/**
 * Service block + service features.
 */

function service_block_get(): array
{
    $row = db()->query('SELECT * FROM service_block WHERE id = 1 LIMIT 1')->fetch();
    return $row ?: [];
}

function service_block_update(array $data): void
{
    $allowed = ['title', 'description', 'image_path', 'image_alt'];
    $sets = []; $params = [];
    foreach ($allowed as $f) {
        if (array_key_exists($f, $data)) {
            $sets[] = "`$f` = :$f";
            $params[":$f"] = $data[$f];
        }
    }
    if (!$sets) return;
    $stmt = db()->prepare('UPDATE service_block SET ' . implode(', ', $sets) . ' WHERE id = 1');
    $stmt->execute($params);
}

function service_features_active(): array
{
    return db()->query('SELECT * FROM service_features WHERE active = 1 ORDER BY sort_order, id')->fetchAll();
}

function service_features_all(): array
{
    return db()->query('SELECT * FROM service_features ORDER BY sort_order, id')->fetchAll();
}

function service_feature_get(int $id): ?array
{
    $stmt = db()->prepare('SELECT * FROM service_features WHERE id = ?');
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    return $row ?: null;
}

function service_feature_create(array $data): int
{
    $stmt = db()->prepare('INSERT INTO service_features (icon, title, description, sort_order, active) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([
        $data['icon'] ?? 'check_circle',
        $data['title'] ?? '',
        $data['description'] ?? '',
        (int) ($data['sort_order'] ?? 0),
        !empty($data['active']) ? 1 : 0,
    ]);
    return (int) db()->lastInsertId();
}

function service_feature_update(int $id, array $data): void
{
    $stmt = db()->prepare('UPDATE service_features SET icon=?, title=?, description=?, sort_order=?, active=? WHERE id=?');
    $stmt->execute([
        $data['icon'] ?? 'check_circle',
        $data['title'] ?? '',
        $data['description'] ?? '',
        (int) ($data['sort_order'] ?? 0),
        !empty($data['active']) ? 1 : 0,
        $id,
    ]);
}

function service_feature_delete(int $id): void
{
    db()->prepare('DELETE FROM service_features WHERE id = ?')->execute([$id]);
}
