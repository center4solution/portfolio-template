   <!-- Vlogs Section: videos come from shared/app/data/vlogs.php -->
   <section id="vlogs" class="py-6 sm:py-6 md:py-10 bg-gray-100 text-gray-900">
      <div class="max-w-6xl mx-auto px-6">
         <!-- Section Header -->
         <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold">Vlogs & Visual Journeys</h2>
            <p class="text-gray-600 mt-2">Stories captured in motion — raw, real, and immersive.</p>
            <div class="w-16 h-1 bg-red-600 mx-auto mt-4 rounded"></div>
         </div>

         <!-- Video Grid -->
         <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach (data('vlogs') as $vlog): ?>
            <div class="rounded-xl overflow-hidden shadow-lg">
               <iframe class="w-full h-60" src="https://www.youtube.com/embed/<?= htmlspecialchars($vlog['id']) ?>" title="<?= htmlspecialchars($vlog['title']) ?>"
                  frameborder="0"
                  allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                  allowfullscreen></iframe>
               <div class="p-4 bg-white">
                  <h3 class="text-lg font-semibold"><?= htmlspecialchars($vlog['title']) ?></h3>
                  <p class="text-sm text-gray-600"><?= htmlspecialchars($vlog['desc']) ?></p>
               </div>
            </div>
            <?php endforeach; ?>
         </div>

         <!-- CTA -->
         <div class="text-center mt-10">
            <a aria-label="watch vlogs created by <?= e(OWNER_NAME) ?>" href="<?= e(data('profile')['youtube']) ?>" target="_blank"
               class="inline-block bg-red-600 text-white px-6 py-2 rounded-md font-semibold hover:bg-red-700 transition">
               Watch All on YouTube
            </a>
         </div>
      </div>
   </section>
