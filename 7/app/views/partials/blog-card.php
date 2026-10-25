<?php require_once VERSION_DIR . '/app/orbit.php'; ?>
<a href="<?= url('/blogs/' . $blog['slug']) ?>" class="panel post-card reveal">
  <figure class="post-img"><img src="<?= e(url($blog['cover'])) ?>" alt="" loading="lazy"><span class="freq mono"><i class="fas fa-wave-square"></i> <?= e(signal_freq($blog['slug'])) ?></span></figure>
  <div class="post-body">
    <p class="kicker"><?= e($blog['category']) ?> · <?= e(date('M j, Y', strtotime($blog['date']))) ?></p>
    <h3><?= e($blog['title']) ?></h3>
    <p class="muted line-3"><?= e($blog['description']) ?></p>
    <span class="post-foot mono"><span class="muted">received <?= e(days_ago($blog['date'])) ?></span><span class="read">Decode <i class="fas fa-arrow-right"></i></span></span>
  </div>
</a>
