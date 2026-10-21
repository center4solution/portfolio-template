<?php
require_once VERSION_DIR . '/app/chain.php';
// Blogs as a transaction feed: one row per post
?>
<div class="panel tx-list reveal" role="table" aria-label="Latest transactions">
  <div class="tx-head" role="row">
    <span role="columnheader">Txn hash</span>
    <span role="columnheader">Post</span>
    <span role="columnheader" class="hide-sm">Method</span>
    <span role="columnheader" class="hide-sm">Age</span>
  </div>
  <?php foreach ($blogs as $b): $h = chain_hash('tx:' . $b['slug']); ?>
    <a href="<?= url('/blogs/' . $b['slug']) ?>" class="tx-row" role="row" data-category="<?= e($b['category']) ?>" data-search="<?= e(strtolower($b['title'] . ' ' . $b['category'] . ' ' . implode(' ', $b['tags']))) ?>">
      <span role="cell" class="tx-hash mono"><span class="tx-icon"><i class="fas fa-file-lines"></i></span><?= e(short_hash($h, 10, 4)) ?></span>
      <span role="cell" class="tx-title"><b><?= e($b['title']) ?></b><small class="mono">from <span class="hl"><?= e(OWNER_INITIALS) ?></span> → <?= e($b['category']) ?></small></span>
      <span role="cell" class="hide-sm"><span class="method"><?= e(strtolower(explode(' ', $b['category'])[0])) ?>()</span></span>
      <span role="cell" class="hide-sm mono muted"><time datetime="<?= e($b['date']) ?>"><?= e(time_ago($b['date'])) ?></time></span>
    </a>
  <?php endforeach; ?>
  <p class="tx-empty muted" hidden>No transactions match your search.</p>
</div>
