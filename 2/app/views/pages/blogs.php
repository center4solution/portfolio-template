<?php $categories = array_values(array_unique(array_column($blogs, 'category'))); ?>
<section class="page-top section">
  <div class="max-w-6xl mx-auto px-4 sm:px-6">
    <?= view('partials/section-title', ['eyebrow' => 'Writing', 'title' => 'All Blogs', 'sub' => 'Thoughts, lessons, and experiences shared through writing.']) ?>

    <div class="flex flex-wrap justify-center gap-2 mb-10" id="blog-filters">
      <button type="button" class="filter-btn is-active" data-filter="all">All</button>
      <?php foreach ($categories as $cat): ?>
        <button type="button" class="filter-btn" data-filter="<?= e($cat) ?>"><?= e($cat) ?></button>
      <?php endforeach; ?>
    </div>

    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <?php foreach ($blogs as $blog): ?>
        <div data-category="<?= e($blog['category']) ?>" class="flex"><?= view('partials/blog-card', ['blog' => $blog]) ?></div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
