<?php
$link = SITE_URL . '/blogs/' . $blog['slug'];
$shareUrl = rawurlencode($link);
$shareTitle = rawurlencode($blog['title']);
?>
<?= schema_blog($blog, $content) ?>

<article class="page-top">
  <header class="wrap story-head">
    <nav class="crumbs" aria-label="Breadcrumb">
      <a href="<?= url('/') ?>">Central</a><span class="crumb-line"></span><a href="<?= url('/blogs') ?>">Departures</a><span class="crumb-line"></span><span aria-current="page">This post</span>
    </nav>
    <p class="kicker"><span class="dot c-purple"></span><?= e($blog['category']) ?></p>
    <h1 class="story-title"><?= e($blog['title']) ?></h1>
    <p class="story-dek"><?= e($blog['description']) ?></p>
    <p class="story-meta mono">
      By <?= e(OWNER_NAME) ?> · <time datetime="<?= e($blog['date']) ?>"><?= e(date('F j, Y', strtotime($blog['date']))) ?></time> · <?= reading_time($content) ?> min ride
    </p>
  </header>
  <figure class="wrap story-cover"><img src="<?= e(url($blog['cover'])) ?>" alt=""></figure>

  <div class="story-body prose"><?= $content /* trusted HTML from content/blogs */ ?></div>

  <footer class="story-foot">
    <div class="tags"><?php foreach ($blog['tags'] as $tag): ?><span class="chip">#<?= e($tag) ?></span><?php endforeach; ?></div>
    <div class="share">
      <span class="kicker">Share this ride</span>
      <a class="icon-btn" target="_blank" rel="noopener" aria-label="Share on LinkedIn" href="https://www.linkedin.com/sharing/share-offsite/?url=<?= $shareUrl ?>"><i class="fab fa-linkedin"></i></a>
      <a class="icon-btn" target="_blank" rel="noopener" aria-label="Share on X" href="https://x.com/intent/tweet?url=<?= $shareUrl ?>&text=<?= $shareTitle ?>"><i class="fab fa-x-twitter"></i></a>
      <a class="icon-btn" target="_blank" rel="noopener" aria-label="Share on WhatsApp" href="https://wa.me/?text=<?= $shareTitle ?>%20<?= $shareUrl ?>"><i class="fab fa-whatsapp"></i></a>
      <a class="icon-btn" target="_blank" rel="noopener" aria-label="Share on Facebook" href="https://www.facebook.com/sharer/sharer.php?u=<?= $shareUrl ?>"><i class="fab fa-facebook"></i></a>
      <button type="button" class="icon-btn" data-copy="<?= e($link) ?>" aria-label="Copy link"><i class="fas fa-link"></i></button>
    </div>
  </footer>
</article>

<?php if ($related): ?>
<section class="sec sec-alt">
  <div class="wrap">
    <?= view('partials/section-title', ['code' => '5', 'color' => 'purple', 'eyebrow' => 'Change here for', 'title' => 'More posts on this line']) ?>
    <div class="post-grid">
      <?php foreach ($related as $r): ?><div><?= view('partials/blog-card', ['blog' => $r]) ?></div><?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>
