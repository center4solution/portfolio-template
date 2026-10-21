<a href="<?= url('/blogs/' . $blog['slug']) ?>" class="card article-card reveal">
  <figure class="article-img"><img src="<?= e(url($blog['cover'])) ?>" alt="" loading="lazy"></figure>
  <div class="article-body">
    <p class="kicker"><?= e($blog['category']) ?> · <?= e(date('M j, Y', strtotime($blog['date']))) ?></p>
    <h3><?= e($blog['title']) ?></h3>
    <p class="muted line-3"><?= e($blog['description']) ?></p>
    <span class="read-more">Read the story →</span>
  </div>
</a>
