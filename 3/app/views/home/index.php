<?php
$p = data('profile');
$journey = data('journey');
usort($journey, fn($a, $b) => $a['year'] <=> $b['year']);
$places = data('places');
$blogs = published_blogs();
$skills = data('skills');
$issue = date('F Y');
$months = ['JAN','FEB','MAR','APR','MAY','JUN','JUL','AUG','SEP','OCT','NOV','DEC'];

// Group places into rough continents for the passport stamps (tune these to your own places)
$regions = ['Europe' => [], 'Asia' => [], 'Americas' => [], 'Africa' => [], 'Oceania' => []];
foreach ($places as $pl) {
    [$name, $lat, $lng] = $pl;
    $region = match (true) {
        $lng < -30 => 'Americas',
        $lat < -10 && $lng > 110 => 'Oceania',
        $lat < 35 && $lng < 52 => 'Africa',
        $lng < 40 => 'Europe',
        default => 'Asia',
    };
    $regions[$region][] = $pl;
}
$regions = array_filter($regions);
$stampColors = ['#c2410c', '#0f766e', '#7c3aed', '#be123c', '#1d4ed8', '#a16207'];

$passIcon = ['work' => 'fa-laptop-code', 'travel' => 'fa-plane-departure', 'study' => 'fa-graduation-cap'];
$passFrom = ['work' => 'DESK', 'travel' => 'HOME', 'study' => 'CLASS'];
?>

<!-- ============ COVER ============ -->
<section class="cover" id="hero">
  <div class="wrap">
    <div class="cover-top hero-in">
      <span>Vol. 03</span>
      <span class="hide-sm">Engineering · Photography · Travel</span>
      <span><?= e($issue) ?></span>
    </div>
    <h1 class="cover-title hero-in" style="--d:.1s">
      <span class="ct-line"><?= e(OWNER_FIRST_NAME) ?></span>
      <span class="ct-line ct-indent"><em><?= e(OWNER_LAST_NAME) ?></em></span>
    </h1>

    <div class="cover-grid">
      <figure class="cover-photo hero-in" style="--d:.2s">
        <img src="<?= e(url($p['hero_bg'])) ?>" alt="<?= e(OWNER_NAME) ?> on the road">
        <figcaption><span class="mono">fig. 01</span> On the road, somewhere between a commit and a summit.</figcaption>
      </figure>

      <aside class="cover-lines">
        <div class="cover-line hero-in" style="--d:.3s">
          <span class="cl-num">01</span>
          <a href="#chapter-1"><b>The Engineer</b> Why good code starts by understanding the business behind it.</a>
        </div>
        <div class="cover-line hero-in" style="--d:.38s">
          <span class="cl-num">02</span>
          <a href="#chapter-2"><b>Boarding passes</b> 2012 to now: the career moves and the trips that mattered.</a>
        </div>
        <div class="cover-line hero-in" style="--d:.46s">
          <span class="cl-num">03</span>
          <a href="#chapter-3"><b><?= count($places) ?> stamps</b> A passport of places, from mountain valleys to old forts.</a>
        </div>
        <div class="cover-line hero-in" style="--d:.54s">
          <span class="cl-num">04</span>
          <a href="#chapter-4"><b>$ whoami --stack</b> The tools behind the work, straight from the terminal.</a>
        </div>
        <p class="cover-quote hero-in" style="--d:.62s"><?= e($p['tagline']) ?></p>
      </aside>
    </div>
  </div>
  <div class="ticker" aria-hidden="true">
    <div class="ticker-track">
      <?php for ($i = 0; $i < 2; $i++): foreach (array_merge($p['roles'], array_column(array_slice($places, 0, 12), 0)) as $t): ?>
        <span><?= e($t) ?></span><span class="dot">✦</span>
      <?php endforeach; endfor; ?>
    </div>
  </div>
</section>

<!-- ============ CHAPTER I ============ -->
<section id="chapter-1" class="chapter">
  <div class="wrap">
    <?= view('partials/section-title', ['num' => 'I', 'eyebrow' => 'Chapter one', 'title' => 'The Engineer who wanders']) ?>
    <div class="essay">
      <div class="essay-text reveal">
        <?php foreach ($p['about'] as $i => $para): ?>
          <p class="<?= $i === 0 ? 'dropcap' : '' ?>"><?= $para /* trusted copy from data/profile.php */ ?></p>
        <?php endforeach; ?>
        <blockquote class="pull-quote"><?= e($p['quote']) ?></blockquote>
      </div>
      <aside class="essay-side reveal">
        <figure class="tape-photo">
          <img src="<?= e(url($p['photo'])) ?>" alt="<?= e($p['name']) ?>" loading="lazy">
          <figcaption class="hand">that's me!</figcaption>
        </figure>
        <div class="field-notes">
          <p class="kicker">Field notes</p>
          <ul>
            <?php foreach ($p['highlights'] as $h): ?>
              <li><i class="fas <?= e($h['icon']) ?>"></i> <?= e($h['label']) ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      </aside>
    </div>
  </div>
</section>

<!-- ============ CHAPTER II ============ -->
<section id="chapter-2" class="chapter chapter-alt">
  <div class="wrap">
    <?= view('partials/section-title', ['num' => 'II', 'eyebrow' => 'Chapter two', 'title' => 'Boarding passes', 'sub' => 'Every big move, at work or on the road, starts with a ticket.']) ?>
    <div class="passes">
      <?php foreach ($journey as $i => $j): $code = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $j['title']), 0, 3)); ?>
        <article class="pass reveal" data-type="<?= e($j['type']) ?>" style="--tilt:<?= ($i % 2 ? 1 : -1) * 0.6 ?>deg">
          <div class="pass-main">
            <div class="pass-top">
              <span class="kicker"><?= e(ucfirst($j['type'])) ?> class</span>
              <i class="fas <?= e($passIcon[$j['type']] ?? 'fa-star') ?>"></i>
            </div>
            <div class="pass-route">
              <div><b><?= e($passFrom[$j['type']] ?? 'HOME') ?></b><small>from</small></div>
              <span class="pass-line"><i class="fas fa-plane"></i></span>
              <div><b><?= e($code) ?></b><small>to</small></div>
            </div>
            <h3><?= e($j['title']) ?></h3>
            <p class="muted"><?= e($j['desc']) ?></p>
          </div>
          <div class="pass-stub">
            <span class="kicker">Year</span>
            <b class="pass-year"><?= (int) $j['year'] ?></b>
            <span class="kicker">Seat</span>
            <b><?= sprintf('%02d', $i + 1) ?>A</b>
            <span class="barcode" aria-hidden="true"></span>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ============ CHAPTER III ============ -->
<section id="chapter-3" class="chapter">
  <div class="wrap">
    <?= view('partials/section-title', ['num' => 'III', 'eyebrow' => 'Chapter three', 'title' => 'The passport', 'sub' => 'Places stamped so far. Stamps with a ▶ have a vlog, so click to watch.']) ?>

    <div class="passport reveal">
      <?php foreach ($regions as $region => $list): ?>
        <div class="passport-page">
          <p class="kicker"><?= e($region) ?> · <?= count($list) ?></p>
          <div class="stamps">
            <?php foreach ($list as $k => [$name, $lat, $lng, $video]):
              $seed = crc32($name);
              $color = $stampColors[$seed % count($stampColors)];
              $rot = ($seed % 17) - 8;
              $shape = ['round', 'rect', 'oval'][$seed % 3];
              $tag = $video ? 'a' : 'span'; ?>
              <<?= $tag ?> class="stamp stamp-<?= $shape ?>" style="--c:<?= $color ?>;--r:<?= $rot ?>deg" <?= $video ? 'href="' . e($video) . '" target="_blank" rel="noopener"' : '' ?> title="<?= e($name) ?>">
                <small><?= e($months[$seed % 12]) ?> · IN</small>
                <b><?= e($name) ?></b>
                <?php if ($video): ?><small>▶ vlog</small><?php endif; ?>
              </<?= $tag ?>>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <div class="map-wrap reveal">
      <div class="map-label"><span class="mono">fig. 02</span> All the stamps on one map</div>
      <div id="travel-map" class="map-frame" data-places='<?= e(json_encode($places, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)) ?>'></div>
    </div>

    <div class="reels">
      <?php foreach (data('vlogs') as $i => $v): ?>
        <article class="reel reveal">
          <button type="button" class="yt-lite" data-id="<?= e($v['id']) ?>" aria-label="Play: <?= e($v['title']) ?>">
            <img src="https://i.ytimg.com/vi/<?= e($v['id']) ?>/hqdefault.jpg" alt="" loading="lazy">
            <span class="yt-play"><i class="fas fa-play"></i></span>
          </button>
          <p class="reel-cap"><span class="mono">reel <?= sprintf('%02d', $i + 1) ?></span> <b><?= e($v['title']) ?></b> — <?= e($v['desc']) ?></p>
        </article>
      <?php endforeach; ?>
    </div>
    <p class="center mt-8"><a href="<?= e($p['youtube']) ?>" target="_blank" rel="noopener" class="btn btn-ink"><i class="fab fa-youtube"></i> The full channel</a></p>
  </div>
</section>

<!-- ============ CHAPTER IV ============ -->
<section id="chapter-4" class="chapter chapter-alt">
  <div class="wrap">
    <?= view('partials/section-title', ['num' => 'IV', 'eyebrow' => 'Chapter four', 'title' => 'The stack', 'sub' => 'Straight from the terminal.']) ?>
    <div class="terminal reveal">
      <div class="term-bar"><i></i><i></i><i></i><span><?= e(strtolower(OWNER_FIRST_NAME)) ?>@logbook: ~</span></div>
      <div class="term-body" id="terminal">
        <p><span class="pr">$</span> <span class="cmd">whoami --stack --verbose</span></p>
        <?php foreach ($skills as $s): ?>
          <div class="term-group">
            <p class="term-key">▸ <?= e($s['title']) ?></p>
            <p class="term-vals"><?php foreach ($s['items'] as $item): ?><span><?= e($item) ?></span><?php endforeach; ?></p>
          </div>
        <?php endforeach; ?>
        <p class="term-ok">✔ <?= count(array_merge(...array_column($skills, 'items'))) ?> tools loaded · since 2018</p>
        <p><span class="pr">$</span> <span class="term-cursor"></span></p>
      </div>
    </div>
    <p class="center mt-8">
      <a href="<?= url('/resume') ?>" class="btn btn-primary">Open the resume</a>
      <a href="<?= e($p['resume_url']) ?>" target="_blank" rel="noopener" class="btn btn-ink">Developer profile</a>
    </p>
  </div>
</section>

<!-- ============ CONTENTS ============ -->
<section id="writing" class="chapter">
  <div class="wrap">
    <?= view('partials/section-title', ['eyebrow' => 'In this issue', 'title' => 'Contents', 'sub' => 'Essays on work, AI and the road.']) ?>
    <ol class="toc">
      <?php foreach (array_slice($blogs, 0, 6) as $i => $b): ?>
        <li class="reveal">
          <a href="<?= url('/blogs/' . $b['slug']) ?>" class="toc-row" data-preview="<?= e(url($b['cover'])) ?>">
            <span class="toc-num"><?= sprintf('%02d', $i + 1) ?></span>
            <span class="toc-title"><?= e($b['title']) ?><small><?= e($b['category']) ?></small></span>
            <span class="toc-dots" aria-hidden="true"></span>
            <span class="toc-date mono"><?= e(date('d.m.y', strtotime($b['date']))) ?></span>
          </a>
        </li>
      <?php endforeach; ?>
    </ol>
    <img class="toc-preview" alt="" aria-hidden="true">
    <p class="center mt-8"><a href="<?= url('/blogs') ?>" class="btn btn-ink">Every article →</a></p>
  </div>
</section>
