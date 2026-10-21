<?php $shareUrl = SITE_URL . '/blogs/' . $blog['slug']; ?>
<?= schema_blog($blog, $content) ?>

<!-- Blog Cover -->
<?php if (!empty($blog['cover'])): ?>
<section class="relative h-[45vh] sm:h-[60vh] bg-cover bg-center flex items-center justify-center"
         style="background-image: url('<?= e(url($blog['cover'])) ?>');">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>
    <div class="relative text-center text-white px-6 z-10 max-w-3xl mx-auto">
        <h1 class="text-3xl sm:text-5xl font-bold mb-4 leading-tight"><?= htmlspecialchars($blog['title']) ?></h1>
        <p class="text-sm sm:text-base text-gray-200">
            <?= htmlspecialchars($blog['date']) ?> • 
            <span class="uppercase bg-white/20 px-2 py-1 rounded text-xs sm:text-sm">
                <?= htmlspecialchars($blog['category']) ?>
            </span>
        </p>
    </div>
</section>
<?php endif; ?>

<!-- Blog Content -->
<section class="max-w-4xl mx-auto py-10 px-4 sm:px-6 text-gray-800">

    <!-- Blog Article -->
    <article class="prose prose-gray max-w-none prose-lg prose-headings:text-gray-900 prose-a:text-blue-600 prose-img:rounded-lg mb-10">
        <?= $content /* trusted HTML from content/blogs */ ?>
    </article>

    <!-- Tags -->
    <?php if (!empty($blog['tags'])): ?>
        <div class="mt-10 flex flex-wrap gap-2 text-sm text-gray-600">
            <?php foreach ($blog['tags'] as $tag): ?>
                <span class="bg-gray-100 px-3 py-1 rounded-full">#<?= htmlspecialchars($tag) ?></span>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>



    <!-- Share Buttons at Bottom -->
    <div class="border-t pt-6 mt-12">
        <h3 class="text-lg font-semibold text-gray-700 mb-3">Share this blog:</h3>
        <div class="flex items-center gap-4">
            <?php $encodedUrl = urlencode($shareUrl); ?>

            <a aria-label="share Blog <?= $blog['title']; ?> on whatsapp" href="https://wa.me/?text=<?= $encodedUrl ?>" title="Share on WhatsApp" target="_blank" class="text-2xl text-green-500 hover:opacity-80 transition">
                <i class="fab fa-whatsapp w-6 h-6"></i>
            </a>
            
            <a aria-label="share Blog <?= $blog['title']; ?> on facebook" href="https://www.facebook.com/sharer/sharer.php?u=<?= $encodedUrl ?>"  title="Share on Facebook" target="_blank" class="text-2xl text-blue-600 hover:opacity-80 transition">
                <i class="fab fa-facebook w-6 h-6"></i>
            </a>
            
            <a aria-label="share Blog <?= $blog['title']; ?> on twitter" href="https://x.com/intent/tweet?url=<?= $encodedUrl ?>&text=<?= urlencode($blog['title']) ?>"  title="Share on X" target="_blank" class="text-2xl text-black hover:opacity-80 transition">
                <i class="fab fa-x-twitter w-6 h-6"></i>
            </a>
            <a  aria-label="share Blog <?= $blog['title']; ?> on limkedin" href="https://www.linkedin.com/sharing/share-offsite/?url=<?= $encodedUrl ?>"  title="Share on LinkedIn" target="_blank" class="text-2xl text-[#0077B5] hover:opacity-80 transition">
                <i class="fab fa-linkedin w-6 h-6"></i>
            </a>

            

        </div>
    </div>

</section>

<!-- Other Blogs Section -->

<?php if (!empty($related)): ?>
<section class="bg-gray-50 py-16">
    <div class="max-w-6xl mx-auto px-6">
        <div class="text-center mb-10">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-2">Other Blogs</h2>
            <p class="text-gray-600">Explore more stories and insights</p>
            <div class="w-16 h-1 bg-blue-400 mx-auto mt-4 rounded"></div>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <?php foreach ($related as $otherBlog): ?>
                <a href="<?= url('/blogs/') ?><?= htmlspecialchars($otherBlog['slug']) ?>" aria-label="Read blog: <?= htmlspecialchars($otherBlog['title']) ?>" class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition group">
                    <?php if (!empty($otherBlog['cover'])): ?>
                        <img src="<?= url($otherBlog['cover']) ?>" alt="<?= htmlspecialchars($otherBlog['title']) ?>" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                    <?php endif; ?>
                    <div class="p-5">
                        <h3 class="text-xl font-semibold mb-2 text-gray-800 group-hover:text-blue-600 transition">
                            <?= htmlspecialchars($otherBlog['title']) ?>
                        </h3>
                        <p class="text-gray-600 text-sm mb-3 line-clamp-2">
                            <?= htmlspecialchars($otherBlog['description']) ?>
                        </p>
                        <div class="flex items-center justify-between text-sm text-gray-500">
                            <span><?= htmlspecialchars($otherBlog['date']) ?></span>
                            <span class="text-blue-600 font-medium group-hover:underline">Read More →</span>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>

        <div class="text-center mt-10">
            <a href="<?= url('/blogs') ?>" aria-label="View all blogs" class="inline-block px-6 py-2 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition">
                View All Blogs
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

