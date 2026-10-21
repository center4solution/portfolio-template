<?php $categories = array_values(array_unique(array_column($blogs, 'category'))); ?>
<section class="chapter page-top">
  <div class="wrap">
    <?= view('partials/section-title', ['eyebrow' => 'The archive', 'title' => 'Every article', 'sub' => 'Thoughts, lessons and experiences, newest first.']) ?>
    <div class="filters" id="blog-filters">
      <button type="button" class="chip is-active" data-filter="all">All</button>
      <?php foreach ($categories as $cat): ?><button type="button" class="chip" data-filter="<?= e($cat) ?>"><?= e($cat) ?></button><?php endforeach; ?>
    </div>
    <div class="article-grid">
      <?php foreach ($blogs as $blog): ?>
        <div data-category="<?= e($blog['category']) ?>"><?= view('partials/blog-card', ['blog' => $blog]) ?></div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
