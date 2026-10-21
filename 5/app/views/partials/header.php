<?php
// Each nav item is a station on the header line: [label, href, line colour]
$stations = [
    ['Origin',     url('/#origin'),   'red'],
    ['Route',      url('/#route'),    'blue'],
    ['Travel',     url('/#travel'),   'green'],
    ['Skills',     url('/#skills'),   'orange'],
    ['Departures', url('/blogs'),     'purple'],
    ['Tickets',    url('/#contact'),  'pink'],
];
?>
<header class="topbar" id="site-header">
  <div class="wrap topbar-inner">
    <a href="<?= url('/') ?>" class="brand" aria-label="<?= e(OWNER_INITIALS) ?> Transit — home">
      <span class="roundel" aria-hidden="true"><span><?= e(OWNER_INITIALS) ?></span></span>
      <span class="brand-text">Transit<small><?= e(OWNER_NAME) ?> network</small></span>
    </a>
    <nav class="line-nav" id="line-nav" aria-label="Main">
      <ol>
        <?php foreach ($stations as [$label, $href, $color]): ?>
          <li class="c-<?= e($color) ?>"><a href="<?= e($href) ?>"><span class="stop" aria-hidden="true"></span><span class="lbl"><?= e($label) ?></span></a></li>
        <?php endforeach; ?>
      </ol>
    </nav>
    <div class="topbar-tools">
      <button type="button" class="icon-btn" data-theme-toggle aria-label="Switch day / night service"><i class="fas fa-moon"></i></button>
      <button type="button" class="icon-btn menu-btn" data-menu aria-label="Open menu" aria-expanded="false" aria-controls="line-nav"><i class="fas fa-bars"></i></button>
    </div>
  </div>
  <div class="ride-progress" aria-hidden="true"><span></span></div>
</header>
