<?php
$nav = [
    'I. Engineer' => url('/#chapter-1'),
    'II. Journey' => url('/#chapter-2'),
    'III. Road'   => url('/#chapter-3'),
    'IV. Stack'   => url('/#chapter-4'),
    'Writing'     => url('/blogs'),
    'Postcard'    => url('/#contact'),
];
?>
<header class="masthead-bar" id="site-header">
  <div class="wrap bar-inner">
    <a href="<?= url('/') ?>" class="bar-logo" aria-label="The <?= e(OWNER_INITIALS) ?> Logbook — home">The&nbsp;<?= e(OWNER_INITIALS) ?>&nbsp;<em>Logbook</em></a>
    <nav class="bar-nav" aria-label="Main">
      <?php foreach ($nav as $label => $href): ?><a href="<?= e($href) ?>"><?= e($label) ?></a><?php endforeach; ?>
    </nav>
    <div class="bar-tools">
      <button type="button" id="theme-toggle" class="icon-btn" aria-label="Switch between paper and night theme"><i class="fas fa-moon"></i></button>
      <button type="button" id="menu-toggle" class="icon-btn bar-burger" aria-label="Open menu" aria-expanded="false" aria-controls="mobile-menu"><i class="fas fa-bars"></i></button>
    </div>
  </div>
  <nav id="mobile-menu" class="mobile-menu" hidden aria-label="Mobile">
    <?php foreach ($nav as $label => $href): ?><a href="<?= e($href) ?>"><?= e($label) ?></a><?php endforeach; ?>
  </nav>
</header>
