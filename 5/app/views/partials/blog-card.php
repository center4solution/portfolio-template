<a href="<?= url('/blogs/' . $blog['slug']) ?>" class="card post-card reveal">
  <figure class="post-img"><img src="<?= e(url($blog['cover'])) ?>" alt="" loading="lazy"></figure>
  <div class="post-body">
    <p class="kicker"><span class="dot c-purple"></span><?= e($blog['category']) ?> · <?= e(date('M j, Y', strtotime($blog['date']))) ?></p>
    <h3><?= e($blog['title']) ?></h3>
    <p class="muted line-3"><?= e($blog['description']) ?></p>
    <span class="read-more">Board this post <i class="fas fa-arrow-right"></i></span>
  </div>
</a>
