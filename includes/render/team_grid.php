<?php
/**
 * Team grid - tum aktif uyeleri kart olarak listeler.
 *
 * Beklenen degisken (opsiyonel):
 *   $team_members - manuel veri seti (varsayilan team_active())
 */
$members = $team_members ?? (function_exists('team_active') ? team_active() : []);
if (!$members) return;
?>
<section class="py-20 md:py-28 px-6 md:px-12">
  <div class="max-w-screen-2xl mx-auto">
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 md:gap-8">
      <?php foreach ($members as $i => $m): ?>
      <article class="team-card" data-animate="fade-up" data-animate-delay="<?= 80 * $i ?>">
        <a href="<?= e('/ekip/' . $m['slug']) ?>" class="block no-underline color-inherit">
          <div class="team-card-photo">
            <?php if (!empty($m['photo'])): ?>
              <img src="<?= e($m['photo']) ?>" alt="<?= e($m['full_name']) ?>" loading="lazy"/>
            <?php else: ?>
              <div class="w-full h-full flex items-center justify-center text-primary/30">
                <span class="material-symbols-outlined" style="font-size:5rem">person</span>
              </div>
            <?php endif; ?>
          </div>
          <div class="team-card-body">
            <h3 class="team-card-name"><?= e($m['full_name']) ?></h3>
            <?php if (!empty($m['title'])): ?>
            <p class="team-card-title"><?= e($m['title']) ?></p>
            <?php endif; ?>
            <div class="team-card-social">
              <?php if (!empty($m['linkedin'])): ?>
              <a href="<?= e($m['linkedin']) ?>" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn" onclick="event.stopPropagation()">
                <span class="material-symbols-outlined">work</span>
              </a>
              <?php endif; ?>
              <?php if (!empty($m['email'])): ?>
              <a href="mailto:<?= e($m['email']) ?>" aria-label="E-posta" onclick="event.stopPropagation()">
                <span class="material-symbols-outlined">mail</span>
              </a>
              <?php endif; ?>
            </div>
          </div>
        </a>
      </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
