<a href="<?= url('/blogs/' . $blog['slug']) ?>" class="hud data-card article-card reveal" data-tilt>
  <figure class="article-img"><img src="<?= e(url($blog['cover'])) ?>" alt="" loading="lazy"><span class="img-scan"></span></figure>
  <div class="article-body">
    <p class="kicker"><?= e($blog['category']) ?> <span class="muted">// <?= e(date('Y.m.d', strtotime($blog['date']))) ?></span></p>
    <h3><?= e($blog['title']) ?></h3>
    <p class="muted line-3"><?= e($blog['description']) ?></p>
    <span class="read-more mono">open_file() →</span>
  </div>
</a>
