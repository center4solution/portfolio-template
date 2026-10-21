<?php
require_once VERSION_DIR . '/app/chain.php';
$p = data('profile');
$journey = data('journey');
usort($journey, fn($a, $b) => $a['year'] <=> $b['year']);
$places = data('places');
$skills = data('skills');
$latest = published_blogs(6);
$allSkills = array_merge(...array_column($skills, 'items'));
$years = (int) date('Y') - 2018;
$typeIcon = ['work' => 'fa-briefcase', 'travel' => 'fa-mountain-sun', 'study' => 'fa-graduation-cap'];
$groupColor = ['#059669', '#d97706', '#2563eb', '#db2777', '#7c3aed'];

// Blocks: each journey entry is a block that points at the previous one
$blocks = [];
$prev = '0x' . str_repeat('0', 64);
foreach ($journey as $i => $j) {
    $hash = chain_hash($j['year'] . $j['title']);
    $blocks[] = $j + ['height' => $i + 1, 'hash' => $hash, 'prev' => $prev, 'txs' => chain_num('txs' . $j['title'], 12, 240)];
    $prev = $hash;
}

// Wallet allocation donut: share of tokens per skill group
$total = count($allSkills);
$stops = [];
$acc = 0;
foreach ($skills as $i => $s) {
    $from = $acc / $total * 100;
    $acc += count($s['items']);
    $stops[] = $groupColor[$i % 5] . ' ' . round($from, 2) . '% ' . round($acc / $total * 100, 2) . '%';
}

// Node map: project lat/lng into a 1000x560 box and link each node to its 2 nearest
$lats = array_column($places, 1);
$lngs = array_column($places, 2);
[$minLat, $maxLat, $minLng, $maxLng] = [min($lats) - .6, max($lats) + .6, min($lngs) - .6, max($lngs) + .6];
$nodes = [];
foreach ($places as $i => [$name, $lat, $lng, $video]) {
    $nodes[] = ['i' => $i, 'name' => $name, 'video' => $video,
        'x' => round(40 + ($lng - $minLng) / ($maxLng - $minLng) * 920, 1),
        'y' => round(30 + ($maxLat - $lat) / ($maxLat - $minLat) * 500, 1)];
}
$links = [];
foreach ($nodes as $a) {
    $d = [];
    foreach ($nodes as $b) if ($a['i'] !== $b['i']) $d[$b['i']] = hypot($a['x'] - $b['x'], $a['y'] - $b['y']);
    asort($d);
    foreach (array_slice(array_keys($d), 0, 2) as $k) $links[min($a['i'], $k) . '-' . max($a['i'], $k)] = [$a['i'], $k];
}
?>

<!-- ============ OVERVIEW ============ -->
<section id="overview" class="hero">
  <div class="wrap">
    <div class="search reveal">
      <i class="fas fa-magnifying-glass"></i>
      <input type="search" id="chain-search" placeholder="Search blocks, tokens, nodes or posts…" aria-label="Search this page" autocomplete="off">
      <kbd class="mono">/</kbd>
    </div>

    <div class="hero-grid">
      <article class="panel account reveal">
        <div class="account-top">
          <div class="avatar"><img src="<?= e(url($p['photo'])) ?>" alt="<?= e($p['name']) ?>"></div>
          <div>
            <p class="kicker">Address</p>
            <h1 class="hero-title"><?= e($p['name']) ?><span class="verified" title="Verified"><i class="fas fa-circle-check"></i></span></h1>
            <button type="button" class="addr mono" data-copy="<?= e(wallet_address()) ?>" aria-label="Copy wallet address"><?= e(short_hash(wallet_address(), 12, 8)) ?> <i class="far fa-copy"></i></button>
          </div>
        </div>
        <p class="hero-intro"><?= e($p['intro']) ?></p>
        <p class="labels">
          <?php foreach ($p['roles'] as $r): ?><span class="label"><?= e($r) ?></span><?php endforeach; ?>
        </p>
        <div class="hero-cta">
          <a href="#blocks" class="btn btn-primary"><i class="fas fa-cubes"></i> Explore blocks</a>
          <a href="<?= url('/resume') ?>" class="btn btn-ghost"><i class="fas fa-id-card"></i> Resume</a>
        </div>
      </article>

      <div class="stats">
        <div class="panel stat reveal"><i class="fas fa-cube"></i><p class="stat-label">Blocks mined</p><p class="stat-val" data-count="<?= count($blocks) ?>">0</p><small class="mono">since 2012</small></div>
        <div class="panel stat reveal"><i class="fas fa-coins"></i><p class="stat-label">Tokens held</p><p class="stat-val" data-count="<?= $total ?>">0</p><small class="mono"><?= count($skills) ?> chains</small></div>
        <div class="panel stat reveal"><i class="fas fa-circle-nodes"></i><p class="stat-label">Nodes</p><p class="stat-val" data-count="<?= count($places) ?>">0</p><small class="mono"><?= count(array_filter(array_column($places, 3))) ?> with media</small></div>
        <div class="panel stat reveal"><i class="fas fa-right-left"></i><p class="stat-label">Transactions</p><p class="stat-val" data-count="<?= count(published_blogs()) ?>">0</p><small class="mono">all successful</small></div>
        <div class="panel stat stat-wide reveal"><i class="fas fa-server"></i><p class="stat-label">Uptime</p><p class="stat-val"><span data-count="<?= $years ?>">0</span>+ yrs</p><small class="mono">last block <span id="last-block">0</span>s ago</small></div>
      </div>
    </div>
  </div>
</section>

<!-- ============ ABOUT (account details) ============ -->
<section id="about" class="sec">
  <div class="wrap">
    <?= view('partials/section-title', ['icon' => 'fa-user-shield', 'eyebrow' => 'Account details', 'title' => 'Engineer by craft. Explorer by heart.']) ?>
    <div class="about-grid">
      <div class="panel about-copy reveal">
        <?php foreach ($p['about'] as $para): ?><p class="lead"><?= $para /* trusted copy from data/profile.php */ ?></p><?php endforeach; ?>
        <blockquote class="contract mono"><span class="muted">// smart contract comment</span><br><?= e(trim($p['quote'], '“”')) ?></blockquote>
      </div>
      <ul class="traits">
        <?php foreach ($p['highlights'] as $i => $h): ?>
          <li class="panel trait reveal" data-search="<?= e(strtolower($h['label'] . ' ' . $h['desc'])) ?>">
            <span class="trait-icon"><i class="fas <?= e($h['icon']) ?>"></i></span>
            <div><h3><?= e($h['label']) ?></h3><p class="muted"><?= e($h['desc']) ?></p></div>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
</section>

<!-- ============ BLOCKS (journey) ============ -->
<section id="blocks" class="sec">
  <div class="wrap">
    <?= view('partials/section-title', ['icon' => 'fa-cubes', 'eyebrow' => 'Blocks', 'title' => 'Every milestone, chained together', 'sub' => 'Each block links to the one before it. Newest on top.']) ?>
    <ol class="chain">
      <li class="block block-pending">
        <div class="panel block-card">
          <p class="block-top"><span class="height mono">#<?= count($blocks) + 1 ?></span><span class="status pending mono"><span class="spinner"></span> Mining…</span></p>
          <h3>Next milestone</h3>
          <p class="muted">Pending transactions are waiting for the next block.</p>
        </div>
      </li>
      <?php foreach (array_reverse($blocks) as $k => $b): ?>
        <li class="block reveal" data-type="<?= e($b['type']) ?>" data-search="<?= e(strtolower($b['title'] . ' ' . $b['desc'] . ' ' . $b['year'] . ' ' . $b['type'])) ?>">
          <div class="panel block-card">
            <p class="block-top">
              <span class="height mono">#<?= $b['height'] ?></span>
              <span class="type t-<?= e($b['type']) ?> mono"><i class="fas <?= e($typeIcon[$b['type']] ?? 'fa-star') ?>"></i> <?= e($b['type']) ?></span>
              <span class="status mono"><i class="fas fa-circle-check"></i> <?= $k === 0 ? 'Latest' : 'Finalized' ?></span>
            </p>
            <h3><span class="year"><?= (int) $b['year'] ?></span> <?= e($b['title']) ?></h3>
            <p class="muted"><?= e($b['desc']) ?></p>
            <dl class="hashes mono">
              <div><dt>Hash</dt><dd><?= e(short_hash($b['hash'], 14, 8)) ?></dd></div>
              <div><dt>Prev</dt><dd><?= e(short_hash($b['prev'], 14, 8)) ?></dd></div>
              <div><dt>Txns</dt><dd><?= $b['txs'] ?></dd></div>
            </dl>
          </div>
        </li>
      <?php endforeach; ?>
    </ol>
  </div>
</section>

<!-- ============ WALLET (skills) ============ -->
<section id="wallet" class="sec">
  <div class="wrap">
    <?= view('partials/section-title', ['icon' => 'fa-wallet', 'eyebrow' => 'Wallet', 'title' => 'Tokens in the stack', 'sub' => 'Every tool I work with, held across ' . count($skills) . ' chains.']) ?>
    <div class="wallet-grid">
      <div class="panel alloc reveal">
        <div class="donut" style="background: conic-gradient(<?= e(implode(', ', $stops)) ?>)"><div><b><?= $total ?></b><span class="mono">tokens</span></div></div>
        <ul class="alloc-legend">
          <?php foreach ($skills as $i => $s): ?>
            <li><span class="sw" style="background: <?= e($groupColor[$i % 5]) ?>"></span><?= e($s['title']) ?><span class="mono muted"><?= round(count($s['items']) / $total * 100) ?>%</span></li>
          <?php endforeach; ?>
        </ul>
      </div>
      <div class="tokens reveal" aria-label="Skill tokens">
        <?php foreach ($skills as $i => $s): ?>
          <div class="panel tok-group">
            <h3><i class="fas <?= e($s['icon']) ?>" style="color: <?= e($groupColor[$i % 5]) ?>"></i> <?= e($s['title']) ?><span class="mono muted"><?= count($s['items']) ?> tokens</span></h3>
            <ul class="tok-items">
              <?php foreach ($s['items'] as $item): $chg = token_change($item); ?>
                <li class="tok-row" data-search="<?= e(strtolower($item . ' ' . $s['title'])) ?>">
                  <span class="tok-name"><span class="tok-icon" style="--c: <?= e($groupColor[$i % 5]) ?>"><?= e(substr(token_symbol($item), 0, 2)) ?></span><span><b><?= e($item) ?></b><small class="mono">$<?= e(token_symbol($item)) ?></small></span></span>
                  <span class="tok-val mono"><?= e(token_balance($item)) ?><small class="<?= $chg >= 0 ? 'up' : 'down' ?>"><?= $chg >= 0 ? '+' : '−' ?><?= number_format(abs($chg), 1) ?>%</small></span>
                </li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
    <p class="mt-8">
      <a href="<?= url('/resume') ?>" class="btn btn-primary"><i class="fas fa-id-card"></i> Resume</a>
      <a href="<?= e($p['resume_url']) ?>" target="_blank" rel="noopener" class="btn btn-ghost">Dev profile</a>
    </p>
  </div>
</section>

<!-- ============ NODES (places + vlogs) ============ -->
<section id="nodes" class="sec">
  <div class="wrap">
    <?= view('partials/section-title', ['icon' => 'fa-circle-nodes', 'eyebrow' => 'Nodes', 'title' => count($places) . ' nodes on the travel network', 'sub' => 'Every place I have been, linked to its nearest neighbours. Gold nodes have a vlog.']) ?>
    <div class="nodes-grid">
      <div class="panel netmap reveal">
        <svg viewBox="0 0 1000 560" role="img" aria-label="Network map of visited places">
          <g class="links">
            <?php foreach ($links as $k => [$a, $b]): ?><line x1="<?= $nodes[$a]['x'] ?>" y1="<?= $nodes[$a]['y'] ?>" x2="<?= $nodes[$b]['x'] ?>" y2="<?= $nodes[$b]['y'] ?>" style="--d: <?= chain_num('l' . $k, 0, 40) / 10 ?>s"/><?php endforeach; ?>
          </g>
          <g class="nodes">
            <?php foreach ($nodes as $n): ?>
              <circle cx="<?= $n['x'] ?>" cy="<?= $n['y'] ?>" r="<?= $n['video'] ? 9 : 6 ?>" class="node <?= $n['video'] ? 'media' : '' ?>" data-node="<?= $n['i'] ?>"><title><?= e($n['name']) ?></title></circle>
            <?php endforeach; ?>
          </g>
        </svg>
        <p class="netmap-hud mono"><span id="node-target">Hover a node</span><span class="muted">north ↑</span></p>
      </div>
      <div class="panel node-list reveal">
        <div class="node-head mono"><span>Node</span><span>Ping</span></div>
        <ul>
          <?php foreach ($places as $i => [$name, $lat, $lng, $video]): ?>
            <li data-node="<?= $i ?>" data-search="<?= e(strtolower($name)) ?>">
              <?php if ($video): ?><a href="<?= e($video) ?>" target="_blank" rel="noopener"><?php else: ?><span class="node-row"><?php endif; ?>
                <span class="node-name"><span class="dot <?= $video ? 'media' : '' ?>"></span><?= e($name) ?><small class="mono"><?= number_format($lat, 2) ?>, <?= number_format($lng, 2) ?></small></span>
                <span class="mono <?= $video ? 'gold' : 'muted' ?>"><?= $video ? '▶ vlog' : chain_num('p' . $name, 8, 180) . 'ms' ?></span>
              <?php if ($video): ?></a><?php else: ?></span><?php endif; ?>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>

    <h3 class="sub-head reveal"><i class="fas fa-photo-film"></i> Media contracts</h3>
    <div class="vlogs">
      <?php foreach (data('vlogs') as $v): ?>
        <article class="panel vlog reveal" data-search="<?= e(strtolower($v['title'] . ' ' . $v['desc'])) ?>">
          <button type="button" class="yt-lite" data-id="<?= e($v['id']) ?>" aria-label="Play: <?= e($v['title']) ?>">
            <img src="https://i.ytimg.com/vi/<?= e($v['id']) ?>/hqdefault.jpg" alt="" loading="lazy">
            <span class="yt-play"><i class="fas fa-play"></i></span>
          </button>
          <div class="vlog-body">
            <p class="mono muted small"><?= e(short_hash(chain_hash('nft:' . $v['id']), 8, 4)) ?></p>
            <h3><?= e($v['title']) ?></h3>
            <p class="muted"><?= e($v['desc']) ?></p>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
    <p class="center mt-8"><a href="<?= e($p['youtube']) ?>" target="_blank" rel="noopener" class="btn btn-ghost"><i class="fab fa-youtube"></i> Full channel</a></p>
  </div>
</section>

<!-- ============ TRANSACTIONS (blogs) ============ -->
<section id="txns" class="sec">
  <div class="wrap">
    <?= view('partials/section-title', ['icon' => 'fa-right-left', 'eyebrow' => 'Latest transactions', 'title' => 'Posts on the chain', 'sub' => 'Notes on work, AI and the road.']) ?>
    <?= view('partials/tx-list', ['blogs' => $latest]) ?>
    <p class="center mt-8"><a href="<?= url('/blogs') ?>" class="btn btn-ghost"><i class="fas fa-list"></i> View all transactions</a></p>
  </div>
</section>
