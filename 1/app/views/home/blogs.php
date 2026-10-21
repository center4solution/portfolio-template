<!-- Blogs Section -->
<section id="blogs" class="py-16 bg-gray-50 text-gray-800">
   <div class="max-w-6xl mx-auto px-4">
      <div class="text-center mb-12">
         <h2 class="text-3xl md:text-4xl font-bold">Latest Blogs</h2>
         <p class="text-gray-600 mt-2">Stories, travels, and thoughts freshly penned</p>
         <div class="w-16 h-1 bg-blue-400 mx-auto mt-4 rounded"></div>
      </div>

      <div class="grid md:grid-cols-3 gap-8">
         <?php foreach (published_blogs(3) as $blog): ?>
            <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition">
               <img src="<?= e(url($blog['cover'])) ?>" alt="<?= e($blog['title']) ?>" class="w-full h-48 object-cover">
               <div class="p-5">
                  <h3 class="text-xl font-semibold mb-2"><?= e($blog['title']) ?></h3>
                  <p class="text-gray-600 text-sm mb-3"><?= e($blog['description']) ?></p>
                  <a aria-label="Read full blog: <?= e($blog['title']) ?>" href="<?= url('/blogs/' . $blog['slug']) ?>" class="text-blue-600 font-medium hover:underline">Read More</a>

               </div>
            </div>
         <?php endforeach; ?>
      </div>

      <div class="text-center mt-10">
         <a aria-label="Read All blogs By <?= e(OWNER_NAME) ?>" href="<?= url('/blogs') ?>" class="inline-block px-6 py-2 bg-blue-600 text-white rounded-full hover:bg-blue-700">See All Blogs</a>
      </div>
   </div>
</section>
