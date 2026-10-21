<div class="board reveal" role="table" aria-label="Departures board">
  <div class="board-head" role="row">
    <span role="columnheader">Date</span>
    <span role="columnheader">Destination</span>
    <span role="columnheader" class="hide-sm">Platform</span>
    <span role="columnheader" class="hide-sm">Status</span>
  </div>
  <?php foreach ($blogs as $b): ?>
    <a href="<?= url('/blogs/' . $b['slug']) ?>" class="board-row" role="row" data-category="<?= e($b['category']) ?>">
      <span role="cell" class="flap"><?= e(strtoupper(date('d M', strtotime($b['date'])))) ?></span>
      <span role="cell" class="dest"><?= e($b['title']) ?></span>
      <span role="cell" class="hide-sm plat"><?= e($b['category']) ?></span>
      <span role="cell" class="hide-sm status"><?= reading_time(blog_content($b)) ?> min · Boarding</span>
    </a>
  <?php endforeach; ?>
</div>
