<?php
/**
 * Breadcrumb partial.
 * Beklenen degisken: $breadcrumbs - array of ['label' => string, 'href' => string|null]
 * Sonuncu eleman aktif sayfa olarak isaretlenir (href verilmese de olur).
 */
$crumbs = $breadcrumbs ?? [];
if (!$crumbs) return;
?>
<nav class="breadcrumb" aria-label="Sayfa konumu">
  <ol>
    <li>
      <a href="/">
        <span class="material-symbols-outlined">home</span>
        <span class="sr-only">Anasayfa</span>
      </a>
    </li>
    <?php foreach ($crumbs as $i => $c):
      $isLast = $i === count($crumbs) - 1;
    ?>
    <li class="breadcrumb-sep" aria-hidden="true"><span class="material-symbols-outlined">chevron_right</span></li>
    <li<?= $isLast ? ' aria-current="page"' : '' ?>>
      <?php if (!$isLast && !empty($c['href'])): ?>
        <a href="<?= e($c['href']) ?>"><?= e($c['label']) ?></a>
      <?php else: ?>
        <span><?= e($c['label']) ?></span>
      <?php endif; ?>
    </li>
    <?php endforeach; ?>
  </ol>
</nav>
