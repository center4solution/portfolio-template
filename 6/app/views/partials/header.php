<?php
require_once VERSION_DIR . '/app/chain.php';
// The ticker lists every skill as a token with a (made-up, stable) price move
$tokens = array_merge(...array_column(data('skills'), 'items'));
$nav = [
    'Overview'     => url('/#overview'),
    'Blocks'       => url('/#blocks'),
    'Wallet'       => url('/#wallet'),
    'Nodes'        => url('/#nodes'),
    'Transactions' => url('/blogs'),
    'Send'         => url('/#send'),
];
?>
<div class="ticker" aria-hidden="true">
  <div class="ticker-track">
    <?php for ($copy = 0; $copy < 2; $copy++): foreach ($tokens as $t): $chg = token_change($t); ?>
      <span class="tick"><b>$<?= e(token_symbol($t)) ?></b> <?= e(token_balance($t)) ?> <em class="<?= $chg >= 0 ? 'up' : 'down' ?>"><?= $chg >= 0 ? '▲' : '▼' ?> <?= number_format(abs($chg), 1) ?>%</em></span>
    <?php endforeach; endfor; ?>
  </div>
</div>
<header class="topbar" id="site-header">
  <div class="wrap topbar-inner">
    <a href="<?= url('/') ?>" class="brand" aria-label="<?= e(OWNER_INITIALS) ?> Chain — home">
      <span class="brand-mark" aria-hidden="true"><i class="fas fa-link"></i></span>
      <span><?= e(OWNER_INITIALS) ?><b>Chain</b></span>
    </a>
    <nav class="nav" id="nav" aria-label="Main">
      <?php foreach ($nav as $label => $href): ?><a href="<?= e($href) ?>"><?= e($label) ?></a><?php endforeach; ?>
    </nav>
    <div class="topbar-tools">
      <span class="net-pill mono" title="Network status"><span class="pulse"></span> Mainnet</span>
      <button type="button" class="icon-btn menu-btn" data-menu aria-label="Open menu" aria-expanded="false" aria-controls="nav"><i class="fas fa-bars"></i></button>
    </div>
  </div>
</header>
