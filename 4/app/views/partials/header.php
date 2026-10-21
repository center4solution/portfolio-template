<?php
$nav = [
    'Identity' => url('/#identity'),
    'Log'      => url('/#log'),
    'Globe'    => url('/#globe'),
    'Systems'  => url('/#systems'),
    'Archive'  => url('/blogs'),
    'Contact'  => url('/#contact'),
];
?>
<header class="dock" id="site-header">
  <a href="<?= url('/') ?>" class="dock-logo" aria-label="<?= e(OWNER_INITIALS) ?>//OS — home"><span class="logo-mark">◆</span><?= e(OWNER_INITIALS) ?><span class="slash">//</span>OS</a>
  <nav class="dock-nav" aria-label="Main">
    <?php foreach ($nav as $label => $href): ?><a href="<?= e($href) ?>"><?= e($label) ?></a><?php endforeach; ?>
  </nav>
  <div class="dock-tools">
    <span class="dock-clock mono" id="clock" aria-hidden="true">--:--:-- IST</span>
    <button type="button" class="cmd-btn" data-open-palette aria-label="Open command palette"><i class="fas fa-magnifying-glass"></i><kbd>Ctrl K</kbd></button>
  </div>
</header>
