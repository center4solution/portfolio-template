
<!-- All Blogs Section -->
<section class="py-20 bg-gray-50 text-gray-800">
  <div class="max-w-6xl mx-auto px-4">
    <!-- Section Header -->
    <div class="text-center mb-12">
      <h2 class="text-3xl md:text-4xl font-bold">All Blogs</h2>
      <p class="text-gray-600 mt-2">Explore thoughts, lessons, and experiences shared through writing.</p>
      <div class="w-16 h-1 bg-blue-400 mx-auto mt-4 rounded"></div>
    </div>

    <!-- Blog Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
      <?php foreach ($blogs as $blog): ?>
        <a aria-label="Read blog: <?= e($blog['title']) ?>" href="<?= url('/blogs/' . $blog['slug']) ?>" class="block bg-white rounded-lg shadow-md hover:shadow-xl transition overflow-hidden">
          <?php if (!empty($blog['cover'])): ?>
            <img src="<?= e(url($blog['cover'])) ?>" alt="<?= htmlspecialchars($blog['title']) ?>" class="w-full h-48 object-cover">
          <?php endif; ?>
          <div class="p-6">
            <h3 class="text-lg font-semibold text-blue-600 mb-2"><?= htmlspecialchars($blog['title']) ?></h3>
            <p class="text-sm text-gray-500 mb-1"><?= htmlspecialchars($blog['date']) ?> · <?= htmlspecialchars($blog['category']) ?></p>
            <p class="text-gray-700 text-sm"><?= htmlspecialchars($blog['description']) ?></p>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

