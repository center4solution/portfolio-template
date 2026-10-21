<?php
require_once VERSION_DIR . '/app/chain.php';
$link = SITE_URL . '/blogs/' . $blog['slug'];
$shareUrl = rawurlencode($link);
$shareTitle = rawurlencode($blog['title']);
$txHash = chain_hash('tx:' . $blog['slug']);
?>
<?= schema_blog($blog, $content) ?>

<article class="page-top">
  <header class="wrap story-head">
    <nav class="crumbs mono" aria-label="Breadcrumb">
      <a href="<?= url('/') ?>">Explorer</a> / <a href="<?= url('/blogs') ?>">Transactions</a> / <span aria-current="page"><?= e(short_hash($txHash, 8, 4)) ?></span>
    </nav>
    <p class="kicker"><?= e($blog['category']) ?></p>
    <h1 class="story-title"><?= e($blog['title']) ?></h1>
    <p class="story-dek"><?= e($blog['description']) ?></p>

    <dl class="panel tx-details mono">
      <div><dt>Txn hash</dt><dd class="break"><?= e($txHash) ?> <button type="button" class="copy-mini" data-copy="<?= e($txHash) ?>" aria-label="Copy transaction hash"><i class="far fa-copy"></i></button></dd></div>
      <div><dt>Status</dt><dd><span class="chip-ok"><i class="fas fa-circle-check"></i> Success</span></dd></div>
      <div><dt>Timestamp</dt><dd><time datetime="<?= e($blog['date']) ?>"><?= e(date('F j, Y', strtotime($blog['date']))) ?></time> <span class="muted">(<?= e(time_ago($blog['date'])) ?>)</span></dd></div>
      <div><dt>From</dt><dd><?= e(OWNER_NAME) ?> <span class="muted"><?= e(short_hash(wallet_address(), 8, 4)) ?></span></dd></div>
      <div><dt>Method</dt><dd><span class="method"><?= e(strtolower(explode(' ', $blog['category'])[0])) ?>()</span></dd></div>
      <div><dt>Read time</dt><dd><?= reading_time($content) ?> min</dd></div>
    </dl>
  </header>
  <figure class="wrap story-cover"><img src="<?= e(url($blog['cover'])) ?>" alt=""></figure>

  <div class="story-body prose"><?= $content /* trusted HTML from content/blogs */ ?></div>

  <footer class="story-foot">
    <div class="tags"><?php foreach ($blog['tags'] as $tag): ?><span class="chip">#<?= e($tag) ?></span><?php endforeach; ?></div>
    <div class="share">
      <span class="kicker">Broadcast</span>
      <a class="icon-btn" target="_blank" rel="noopener" aria-label="Share on LinkedIn" href="https://www.linkedin.com/sharing/share-offsite/?url=<?= $shareUrl ?>"><i class="fab fa-linkedin"></i></a>
      <a class="icon-btn" target="_blank" rel="noopener" aria-label="Share on X" href="https://x.com/intent/tweet?url=<?= $shareUrl ?>&text=<?= $shareTitle ?>"><i class="fab fa-x-twitter"></i></a>
      <a class="icon-btn" target="_blank" rel="noopener" aria-label="Share on WhatsApp" href="https://wa.me/?text=<?= $shareTitle ?>%20<?= $shareUrl ?>"><i class="fab fa-whatsapp"></i></a>
      <a class="icon-btn" target="_blank" rel="noopener" aria-label="Share on Facebook" href="https://www.facebook.com/sharer/sharer.php?u=<?= $shareUrl ?>"><i class="fab fa-facebook"></i></a>
      <button type="button" class="icon-btn" data-copy="<?= e($link) ?>" aria-label="Copy link"><i class="fas fa-link"></i></button>
    </div>
  </footer>
</article>

<?php if ($related): ?>
<section class="sec">
  <div class="wrap">
    <?= view('partials/section-title', ['icon' => 'fa-diagram-project', 'eyebrow' => 'Related transactions', 'title' => 'More posts on the chain']) ?>
    <div class="post-grid">
      <?php foreach ($related as $r): ?><div><?= view('partials/blog-card', ['blog' => $r]) ?></div><?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>
