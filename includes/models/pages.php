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

/** Slug -> public PHP dosya yolu (admin onizleme icin). */
function page_public_url(string $slug): string
{
    static $map = [
        'hakkimizda'   => '/hakkimizda.php',
        'hizmet-detay' => '/ne-yapiyoruz.php',
        'ne-yapiyoruz' => '/ne-yapiyoruz.php',
        'ekip'         => '/ekip.php',
        'referanslar'  => '/referanslar.php',
        'sss'          => '/sss.php',
        'iletisim'     => '/iletisim.php',
        'blog'         => '/blog.php',
    ];
    return $map[$slug] ?? ('/' . $slug . '.php');
}

/**
 * Hero degiskenlerini DB (pages) + varsayilan + override ile birlestirir.
 * Donus: hero_eyebrow, hero_title, hero_subtitle, hero_image,
 *         hero_overlay_opacity, hero_blur
 */
function page_hero_load(string $slug, array $defaults, array $overrides = []): array
{
    $page = page_get($slug);

    $vars = [
        'hero_eyebrow'         => $defaults['hero_eyebrow'] ?? null,
        'hero_title'           => $defaults['hero_title'] ?? '',
        'hero_subtitle'        => $defaults['hero_subtitle'] ?? null,
        'hero_image'           => $defaults['hero_image'] ?? null,
        'hero_overlay_opacity' => (int) ($defaults['hero_overlay_opacity'] ?? 50),
        'hero_blur'            => (int) ($defaults['hero_blur'] ?? 0),
    ];

    if ($page) {
        if (($page['hero_eyebrow'] ?? '') !== '') {
            $vars['hero_eyebrow'] = $page['hero_eyebrow'];
        }
        if (($page['title'] ?? '') !== '') {
            $vars['hero_title'] = $page['title'];
        }
        if (($page['hero_subtitle'] ?? '') !== '') {
            $vars['hero_subtitle'] = $page['hero_subtitle'];
        }
        if (($page['hero_image'] ?? '') !== '') {
            $vars['hero_image'] = $page['hero_image'];
        }
        if (array_key_exists('hero_overlay_opacity', $page) && $page['hero_overlay_opacity'] !== null && $page['hero_overlay_opacity'] !== '') {
            $vars['hero_overlay_opacity'] = max(0, min(100, (int) $page['hero_overlay_opacity']));
        }
        if (array_key_exists('hero_blur', $page) && $page['hero_blur'] !== null && $page['hero_blur'] !== '') {
            $vars['hero_blur'] = max(0, min(30, (int) $page['hero_blur']));
        }
    }

    foreach ($overrides as $k => $v) {
        if ($v !== null && $v !== '') {
            $vars[$k] = $v;
        }
    }

    return $vars;
}
