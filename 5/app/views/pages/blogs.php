<?php $categories = array_values(array_unique(array_column($blogs, 'category'))); ?>
<section class="sec page-top">
  <div class="wrap">
    <?= view('partials/section-title', ['code' => '5', 'color' => 'purple', 'eyebrow' => 'All departures', 'title' => 'Every post on the board', 'sub' => 'Thoughts, lessons and experiences, newest first. Pick a platform to filter.']) ?>
    <div class="filters" id="blog-filters">
      <button type="button" class="chip is-active" data-filter="all">All platforms</button>
      <?php foreach ($categories as $cat): ?><button type="button" class="chip" data-filter="<?= e($cat) ?>"><?= e($cat) ?></button><?php endforeach; ?>
    </div>
    <?= view('partials/board', ['blogs' => $blogs]) ?>
    <div class="post-grid mt-8">
      <?php foreach ($blogs as $blog): ?>
        <div data-category="<?= e($blog['category']) ?>"><?= view('partials/blog-card', ['blog' => $blog]) ?></div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
