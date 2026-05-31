<?php
/**
 * Hero (singleton) model.
 */

function hero_get(): array
{
    $row = db()->query('SELECT * FROM hero WHERE id = 1 LIMIT 1')->fetch();
    return $row ?: [];
}

function hero_update(array $data): void
{
    $allowed = [
        'eyebrow', 'title_html', 'description', 'image_path', 'image_alt',
        'primary_cta_text', 'primary_cta_href',
        'secondary_cta_text', 'secondary_cta_href',
        'badge_value', 'badge_text',
    ];
    $sets = [];
    $params = [];
    foreach ($allowed as $field) {
        if (array_key_exists($field, $data)) {
            $sets[] = "`$field` = :$field";
            $params[":$field"] = $data[$field];
        }
    }
    if (!$sets) {
        return;
    }
    $sql = 'UPDATE hero SET ' . implode(', ', $sets) . ' WHERE id = 1';
    $stmt = db()->prepare($sql);
    $stmt->execute($params);
}
