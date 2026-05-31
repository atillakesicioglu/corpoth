<?php
/**
 * Media (uploads) model.
 */

function media_create(array $d): int
{
    $stmt = db()->prepare('INSERT INTO media (filename, path, mime, size, width, height, uploaded_by) VALUES (?, ?, ?, ?, ?, ?, ?)');
    $stmt->execute([
        $d['filename'] ?? '',
        $d['path'] ?? '',
        $d['mime'] ?? null,
        $d['size'] ?? null,
        $d['width'] ?? null,
        $d['height'] ?? null,
        $d['uploaded_by'] ?? null,
    ]);
    return (int) db()->lastInsertId();
}

function media_list(int $limit = 100, int $offset = 0): array
{
    $sql = 'SELECT * FROM media ORDER BY created_at DESC LIMIT ' . (int) $limit . ' OFFSET ' . (int) $offset;
    return db()->query($sql)->fetchAll();
}

function media_get(int $id): ?array
{
    $stmt = db()->prepare('SELECT * FROM media WHERE id = ?');
    $stmt->execute([$id]);
    return $stmt->fetch() ?: null;
}

function media_delete(int $id): void
{
    $row = media_get($id);
    if (!$row) return;

    $absPath = CORPOTH_ROOT . $row['path'];
    if (is_file($absPath)) {
        @unlink($absPath);
    }
    db()->prepare('DELETE FROM media WHERE id = ?')->execute([$id]);
}

/**
 * Yuklenen $_FILES dosyasini kaydeder, public path dondurur.
 * Hata olursa exception firlatir.
 */
function media_handle_upload(array $file, ?int $uploaderId = null): array
{
    if (!is_array($file) || empty($file['name'])) {
        throw new RuntimeException('Geçerli bir dosya yüklenmedi.');
    }

    if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Dosya yükleme hatası (kod: ' . ($file['error'] ?? '?') . ').');
    }

    $config = $GLOBALS['CORPOTH_CONFIG']['upload'];

    if ($file['size'] > $config['max_size_bytes']) {
        throw new RuntimeException('Dosya boyutu ' . round($config['max_size_bytes'] / 1024 / 1024, 1) . ' MB sınırını aşıyor.');
    }

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime  = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mime, $config['allowed_mimes'], true)) {
        throw new RuntimeException('İzin verilmeyen dosya türü: ' . $mime);
    }

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $ext = preg_replace('/[^a-z0-9]/', '', $ext);
    if ($ext === '') {
        $ext = match ($mime) {
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/webp' => 'webp',
            'image/gif'  => 'gif',
            'image/svg+xml' => 'svg',
            default => 'bin',
        };
    }

    $base = preg_replace('/[^a-z0-9\-]+/i', '-', pathinfo($file['name'], PATHINFO_FILENAME));
    $base = trim($base, '-');
    if ($base === '') {
        $base = 'file';
    }
    $base = mb_substr($base, 0, 60);

    $filename = $base . '-' . date('Ymd-His') . '-' . bin2hex(random_bytes(3)) . '.' . $ext;

    $dir = $config['directory'];
    if (!is_dir($dir)) {
        mkdir($dir, 0775, true);
    }

    $absPath = $dir . '/' . $filename;
    if (!move_uploaded_file($file['tmp_name'], $absPath)) {
        throw new RuntimeException('Dosya hedef klasöre taşınamadı.');
    }

    $publicPath = rtrim($config['url_path'], '/') . '/' . $filename;

    [$w, $h] = @getimagesize($absPath) ?: [null, null];

    $id = media_create([
        'filename'    => $filename,
        'path'        => $publicPath,
        'mime'        => $mime,
        'size'        => $file['size'],
        'width'       => $w,
        'height'      => $h,
        'uploaded_by' => $uploaderId,
    ]);

    return [
        'id'       => $id,
        'filename' => $filename,
        'path'     => $publicPath,
        'mime'     => $mime,
        'size'     => $file['size'],
        'width'    => $w,
        'height'   => $h,
    ];
}
