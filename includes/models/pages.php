<?php
/**
 * pages model.
 */

function page_get(string $slug): ?array
{
    try {
        $stmt = db()->prepare('SELECT * FROM pages WHERE slug = ? LIMIT 1');
        $stmt->execute([$slug]);
        return $stmt->fetch() ?: null;
    } catch (Throwable $e) {
        error_log('page_get: ' . $e->getMessage());
        return null;
    }
}

function page_all(): array
{
    try {
        return db()->query('SELECT * FROM pages ORDER BY title')->fetchAll();
    } catch (Throwable $e) {
        error_log('page_all: ' . $e->getMessage());
        return [];
    }
}

function page_create(array $d): int
{
    $stmt = db()->prepare('INSERT INTO pages (slug, title, hero_eyebrow, hero_subtitle, hero_image, hero_overlay_opacity, hero_blur, content_html, meta_title, meta_description, og_image, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
    $stmt->execute([
        $d['slug'] ?? '',
        $d['title'] ?? '',
        $d['hero_eyebrow'] ?? null,
        $d['hero_subtitle'] ?? null,
        $d['hero_image'] ?? null,
        max(0, min(100, (int)($d['hero_overlay_opacity'] ?? 50))),
        max(0, min(30,  (int)($d['hero_blur'] ?? 0))),
        $d['content_html'] ?? null,
        $d['meta_title'] ?? null,
        $d['meta_description'] ?? null,
        $d['og_image'] ?? null,
        !empty($d['is_active']) ? 1 : 0,
    ]);
    return (int) db()->lastInsertId();
}

function page_update(int $id, array $d): void
{
    $stmt = db()->prepare('UPDATE pages SET slug=?, title=?, hero_eyebrow=?, hero_subtitle=?, hero_image=?, hero_overlay_opacity=?, hero_blur=?, content_html=?, meta_title=?, meta_description=?, og_image=?, is_active=? WHERE id=?');
    $stmt->execute([
        $d['slug'] ?? '',
        $d['title'] ?? '',
        $d['hero_eyebrow'] ?? null,
        $d['hero_subtitle'] ?? null,
        $d['hero_image'] ?? null,
        max(0, min(100, (int)($d['hero_overlay_opacity'] ?? 50))),
        max(0, min(30,  (int)($d['hero_blur'] ?? 0))),
        $d['content_html'] ?? null,
        $d['meta_title'] ?? null,
        $d['meta_description'] ?? null,
        $d['og_image'] ?? null,
        !empty($d['is_active']) ? 1 : 0,
        $id,
    ]);
}

function page_delete(int $id): void
{
    db()->prepare('DELETE FROM pages WHERE id = ?')->execute([$id]);
}

function page_get_by_id(int $id): ?array
{
    $stmt = db()->prepare('SELECT * FROM pages WHERE id = ?');
    $stmt->execute([$id]);
    return $stmt->fetch() ?: null;
}
