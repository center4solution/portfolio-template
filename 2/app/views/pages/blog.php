<?php
$shareUrl = rawurlencode(SITE_URL . '/blogs/' . $blog['slug']);
$shareTitle = rawurlencode($blog['title']);
?>
<?= schema_blog($blog, $content) ?>

<article>
  <header class="relative min-h-[55vh] flex items-end text-white bg-ink">
    <img src="<?= e(url($blog['cover'])) ?>" alt="" class="absolute inset-0 w-full h-full object-cover opacity-40">
    <div class="absolute inset-0 bg-gradient-to-t from-ink via-ink/60 to-transparent"></div>
    <div class="relative max-w-3xl mx-auto w-full px-4 sm:px-6 pb-12 pt-28">
      <nav class="text-sm text-white/60 mb-4" aria-label="Breadcrumb">
        <a href="<?= url('/') ?>" class="hover:text-white">Home</a> / <a href="<?= url('/blogs') ?>" class="hover:text-white">Blogs</a>
      </nav>
      <p class="eyebrow text-brand"><?= e($blog['category']) ?></p>
      <h1 class="font-serif text-3xl sm:text-5xl font-semibold leading-tight mt-2"><?= e($blog['title']) ?></h1>
      <p class="mt-4 text-sm text-white/70">
        <time datetime="<?= e($blog['date']) ?>"><?= e(date('F j, Y', strtotime($blog['date']))) ?></time>
        · <?= reading_time($content) ?> min read
      </p>
    </div>
  </header>

  <div class="max-w-3xl mx-auto px-4 sm:px-6 py-12">
    <div class="prose-body"><?= $content /* trusted HTML from content/blogs */ ?></div>

    <div class="mt-10 flex flex-wrap gap-2">
      <?php foreach ($blog['tags'] as $tag): ?><span class="tag">#<?= e($tag) ?></span><?php endforeach; ?>
    </div>

    <div class="mt-8 pt-6 border-t border-slate-200 flex flex-wrap items-center gap-3 text-sm">
      <span class="font-medium text-slate-600">Share:</span>
      <a class="social-chip" target="_blank" rel="noopener" aria-label="Share on LinkedIn" href="https://www.linkedin.com/sharing/share-offsite/?url=<?= $shareUrl ?>"><i class="fab fa-linkedin"></i></a>
      <a class="social-chip" target="_blank" rel="noopener" aria-label="Share on X" href="https://x.com/intent/tweet?url=<?= $shareUrl ?>&text=<?= $shareTitle ?>"><i class="fab fa-x-twitter"></i></a>
      <a class="social-chip" target="_blank" rel="noopener" aria-label="Share on WhatsApp" href="https://wa.me/?text=<?= $shareTitle ?>%20<?= $shareUrl ?>"><i class="fab fa-whatsapp"></i></a>
      <a class="social-chip" target="_blank" rel="noopener" aria-label="Share on Facebook" href="https://www.facebook.com/sharer/sharer.php?u=<?= $shareUrl ?>"><i class="fab fa-facebook"></i></a>
      <button type="button" class="social-chip" data-copy="<?= e(SITE_URL . '/blogs/' . $blog['slug']) ?>" aria-label="Copy link"><i class="fas fa-link"></i></button>
    </div>
  </div>
</article>

<?php if ($related): ?>
<section class="section bg-slate-50">
  <div class="max-w-6xl mx-auto px-4 sm:px-6">
    <h2 class="font-serif text-2xl font-semibold text-ink mb-8 text-center">Keep reading</h2>
    <div class="grid md:grid-cols-3 gap-6">
      <?php foreach ($related as $r): ?><?= view('partials/blog-card', ['blog' => $r]) ?><?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>
