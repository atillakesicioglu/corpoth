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
      <?php foreach ($members as $i => $m):
        $hasSocial = !empty($m['linkedin']) || !empty($m['email']) || !empty($m['phone']);
      ?>
      <article class="team-card" data-animate="fade-up" data-animate-delay="<?= 80 * $i ?>">
        <a href="<?= e('/ekip/' . $m['slug']) ?>" class="team-card-main block no-underline color-inherit">
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
          </div>
        </a>

        <?php if ($hasSocial): ?>
        <div class="team-card-social">
          <?php if (!empty($m['linkedin'])): ?>
          <a href="<?= e($m['linkedin']) ?>" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn">
            <svg class="team-social-svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
              <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-2.007 0-3.63-1.624-3.63-3.63 0-2.007 1.623-3.63 3.63-3.63 2.007 0 3.63 1.623 3.63 3.63 0 2.006-1.623 3.63-3.63 3.63zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
            </svg>
          </a>
          <?php endif; ?>
          <?php if (!empty($m['email'])): ?>
          <a href="mailto:<?= e($m['email']) ?>" aria-label="E-posta">
            <span class="material-symbols-outlined">mail</span>
          </a>
          <?php endif; ?>
          <?php if (!empty($m['phone'])): ?>
          <a href="tel:<?= e(tel_link($m['phone'])) ?>" aria-label="Telefon">
            <span class="material-symbols-outlined">call</span>
          </a>
          <?php endif; ?>
        </div>
        <?php endif; ?>
      </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
