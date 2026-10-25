<?php
$nav = [
    'Launch'        => url('/#launch'),
    'Missions'      => url('/#missions'),
    'Orbits'        => url('/#orbits'),
    'Star map'      => url('/#starmap'),
    'Transmissions' => url('/blogs'),
    'Comms'         => url('/#comms'),
];
?>
<header class="topbar" id="site-header">
  <div class="wrap topbar-inner">
    <a href="<?= url('/') ?>" class="brand" aria-label="<?= e(OWNER_INITIALS) ?> Orbit — home">
      <span class="brand-mark" aria-hidden="true"><span class="brand-moon"></span></span>
      <span><?= e(OWNER_INITIALS) ?><b>Orbit</b></span>
    </a>
    <nav class="nav" id="nav" aria-label="Main">
      <?php foreach ($nav as $label => $href): ?><a href="<?= e($href) ?>"><?= e($label) ?></a><?php endforeach; ?>
    </nav>
    <div class="topbar-tools">
      <span class="met mono" title="Mission elapsed time since the first launch in 2018"><span class="pulse"></span> MET <span id="met" data-since="2018-01-01T00:00:00Z">--</span></span>
      <button type="button" class="icon-btn menu-btn" data-menu aria-label="Open menu" aria-expanded="false" aria-controls="nav"><i class="fas fa-bars"></i></button>
    </div>
  </div>
</header>
