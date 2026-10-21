
<!-- About Section -->
<section id="about" class="py-10 bg-gray-50 text-gray-800">
  <div class="max-w-5xl mx-auto px-6 text-center">
    <!-- Section Header -->
    <h2 class="text-3xl md:text-4xl font-bold mb-4">About Me</h2>
    <div class="w-20 h-1 bg-emerald-400 mx-auto rounded mb-6"></div>

    <!-- Profile Image -->
    <img src="<?= e(url(data('profile')['photo'])) ?>" alt="<?= e(OWNER_NAME) ?>"
      class="w-32 h-32 mx-auto rounded-full border-4 border-emerald-400 object-cover shadow mb-6" />

    <!-- About Paragraphs -->
    <p class="text-lg text-gray-600 leading-relaxed mb-4">
      I'm <span class="font-semibold text-gray-900"><?= e(OWNER_NAME) ?></span> — a Software Engineer who believes good code solves real problems, and great experiences are born from curiosity. Whether I’m building interfaces or capturing mountain light, I approach both with the same drive to create something meaningful.
    </p>
    <p class="text-lg text-gray-600 leading-relaxed mb-6">
      I'm known for <span class="font-semibold">efficient multitasking</span>, understanding both technical and non-technical workflows with ease. I like to <span class="font-semibold">think beyond the expected</span>, bring passion to my work, and grasp the <span class="font-semibold">business logic behind products</span>. These qualities help me deliver more value, faster.
    </p>

    <!-- Visual Trait Highlights -->
    <div class="grid grid-cols-2 sm:grid-cols-3 gap-6 mt-6 text-sm text-gray-700">
      <?php foreach (data('profile')['highlights'] as $trait): ?>
        <div class="flex flex-col items-center">
          <i class="fas <?= e($trait['icon']) ?> text-2xl <?= $trait['color'] ?> mb-2"></i>
          <span class="text-center"><?= $trait['label'] ?></span>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Personal Quote -->
    <p class="italic text-gray-500 mt-10">
      “I build with logic, capture with heart, and travel to stay curious.”
    </p>
  </div>
</section>
