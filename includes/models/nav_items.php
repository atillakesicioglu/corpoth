<?php
/**
 * nav_items model + render helper'lari.
 *
 * Hiyerarsik (2 seviye) navigasyon menusu.
 * - parent_id NULL = top-level item
 * - parent_id N    = N'in alt menu ogesi
 *
 * nav_tree() public sayfalar icin: aktif olanlari nested dizi seklinde doner.
 * Tablo yoksa veya bos ise null doner; nav.php fallback hardcoded array kullanir.
 */

function nav_tree(): ?array
{
    try {
        $rows = db()->query(
            'SELECT id, parent_id, label, href, icon, description, key_slug,
                    is_dropdown_parent, sort_order, is_active
               FROM nav_items
              WHERE is_active = 1
              ORDER BY COALESCE(parent_id, 0) ASC, sort_order ASC, id ASC'
        )->fetchAll();
    } catch (Throwable $e) {
        error_log('nav_tree: ' . $e->getMessage());
        return null;
    }

    if (!$rows) return null;

    $byParent = [];
    foreach ($rows as $r) {
        $key = $r['parent_id'] === null ? 0 : (int)$r['parent_id'];
        $byParent[$key] = $byParent[$key] ?? [];
        $byParent[$key][] = $r;
    }

    $top = $byParent[0] ?? [];
    $tree = [];

    foreach ($top as $item) {
        $children = $byParent[(int)$item['id']] ?? [];

        if ((int)$item['is_dropdown_parent'] === 1 && $children) {
            $tree[] = [
                'type'          => 'dropdown',
                'key'           => (string) ($item['key_slug'] ?? ('nav-' . $item['id'])),
                'label'         => (string) $item['label'],
                'children_keys' => array_map(fn($c) => (string) ($c['key_slug'] ?? ('nav-' . $c['id'])), $children),
                'items'         => array_map(function ($c) {
                    return [
                        'icon'  => (string) ($c['icon'] ?? ''),
                        'label' => (string) $c['label'],
                        'href'  => (string) ($c['href'] ?? '#'),
                        'desc'  => (string) ($c['description'] ?? ''),
                        'key'   => (string) ($c['key_slug'] ?? ('nav-' . $c['id'])),
                    ];
                }, $children),
            ];
        } else {
            $tree[] = [
                'type'  => 'link',
                'key'   => (string) ($item['key_slug'] ?? ('nav-' . $item['id'])),
                'label' => (string) $item['label'],
                'href'  => (string) ($item['href'] ?? '#'),
            ];
        }
    }

    return $tree;
}

/* ---------------- Admin CRUD helper'lari ---------------- */

function nav_all_with_children(): array
{
    try {
        $rows = db()->query(
            'SELECT * FROM nav_items
              ORDER BY COALESCE(parent_id, 0) ASC, sort_order ASC, id ASC'
        )->fetchAll();
    } catch (Throwable $e) {
        return [];
    }

    $byParent = [];
    foreach ($rows as $r) {
        $key = $r['parent_id'] === null ? 0 : (int)$r['parent_id'];
        $byParent[$key][] = $r;
    }

    $tops = $byParent[0] ?? [];
    foreach ($tops as &$t) {
        $t['children'] = $byParent[(int)$t['id']] ?? [];
    }
    unset($t);
    return $tops;
}

function nav_get(int $id): ?array
{
    try {
        $stmt = db()->prepare('SELECT * FROM nav_items WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    } catch (Throwable $e) {
        return null;
    }
}

function nav_top_level_options(): array
{
    try {
        return db()->query(
            'SELECT id, label FROM nav_items
              WHERE parent_id IS NULL
              ORDER BY sort_order ASC, id ASC'
        )->fetchAll();
    } catch (Throwable $e) {
        return [];
    }
}

function nav_create(array $d): int
{
    $stmt = db()->prepare(
        'INSERT INTO nav_items (parent_id, label, href, icon, description, key_slug, is_dropdown_parent, sort_order, is_active)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)'
    );
    $stmt->execute([
        !empty($d['parent_id']) ? (int)$d['parent_id'] : null,
        (string) ($d['label'] ?? ''),
        (string) ($d['href']  ?? '#'),
        $d['icon']        !== null && $d['icon']        !== '' ? (string)$d['icon']        : null,
        $d['description'] !== null && $d['description'] !== '' ? (string)$d['description'] : null,
        $d['key_slug']    !== null && $d['key_slug']    !== '' ? (string)$d['key_slug']    : null,
        !empty($d['is_dropdown_parent']) ? 1 : 0,
        (int) ($d['sort_order'] ?? 0),
        !empty($d['is_active']) ? 1 : 0,
    ]);
    return (int) db()->lastInsertId();
}

function nav_update(int $id, array $d): void
{
    $stmt = db()->prepare(
        'UPDATE nav_items
            SET parent_id = ?, label = ?, href = ?, icon = ?, description = ?, key_slug = ?,
                is_dropdown_parent = ?, sort_order = ?, is_active = ?
          WHERE id = ?'
    );
    $stmt->execute([
        !empty($d['parent_id']) ? (int)$d['parent_id'] : null,
        (string) ($d['label'] ?? ''),
        (string) ($d['href']  ?? '#'),
        $d['icon']        !== null && $d['icon']        !== '' ? (string)$d['icon']        : null,
        $d['description'] !== null && $d['description'] !== '' ? (string)$d['description'] : null,
        $d['key_slug']    !== null && $d['key_slug']    !== '' ? (string)$d['key_slug']    : null,
        !empty($d['is_dropdown_parent']) ? 1 : 0,
        (int) ($d['sort_order'] ?? 0),
        !empty($d['is_active']) ? 1 : 0,
        $id,
    ]);
}

function nav_delete(int $id): void
{
    db()->prepare('DELETE FROM nav_items WHERE id = ?')->execute([$id]);
}

function nav_set_sort(int $id, int $order): void
{
    db()->prepare('UPDATE nav_items SET sort_order = ? WHERE id = ?')->execute([$order, $id]);
}

function nav_toggle_active(int $id): void
{
    db()->prepare('UPDATE nav_items SET is_active = 1 - is_active WHERE id = ?')->execute([$id]);
}
