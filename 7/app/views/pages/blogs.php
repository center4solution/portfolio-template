<?php $categories = array_values(array_unique(array_column($blogs, 'category'))); ?>
<section class="sec page-top">
  <div class="wrap">
    <?= view('partials/section-title', ['icon' => 'fa-satellite-dish', 'eyebrow' => 'All transmissions', 'title' => 'Every signal received', 'sub' => 'Thoughts, lessons and experiences, newest first. Tune by channel.']) ?>
    <div class="filters" id="blog-filters">
      <button type="button" class="chip is-active" data-filter="all">All channels</button>
      <?php foreach ($categories as $cat): ?><button type="button" class="chip" data-filter="<?= e($cat) ?>"><?= e($cat) ?></button><?php endforeach; ?>
    </div>
    <div class="post-grid">
      <?php foreach ($blogs as $blog): ?>
        <div data-category="<?= e($blog['category']) ?>"><?= view('partials/blog-card', ['blog' => $blog]) ?></div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
