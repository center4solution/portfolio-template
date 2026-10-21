<?php require_once VERSION_DIR . '/app/chain.php'; ?>
<a href="<?= url('/blogs/' . $blog['slug']) ?>" class="panel post-card reveal">
  <figure class="post-img"><img src="<?= e(url($blog['cover'])) ?>" alt="" loading="lazy"><span class="badge-ok mono"><i class="fas fa-circle-check"></i> Success</span></figure>
  <div class="post-body">
    <p class="kicker"><?= e($blog['category']) ?> · <?= e(date('M j, Y', strtotime($blog['date']))) ?></p>
    <h3><?= e($blog['title']) ?></h3>
    <p class="muted line-3"><?= e($blog['description']) ?></p>
    <span class="post-foot mono"><span class="muted"><?= e(short_hash(chain_hash('tx:' . $blog['slug']), 8, 4)) ?></span><span class="read">View txn <i class="fas fa-arrow-right"></i></span></span>
  </div>
</a>
