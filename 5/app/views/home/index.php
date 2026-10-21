<?php
$p = data('profile');
$journey = data('journey');
usort($journey, fn($a, $b) => $a['year'] <=> $b['year']);
$places = data('places');
$skills = data('skills');
$departures = published_blogs(5);
$allSkills = array_merge(...array_column($skills, 'items'));
$typeLine = ['work' => 'red', 'travel' => 'green', 'study' => 'blue'];
$typeIcon = ['work' => 'fa-briefcase', 'travel' => 'fa-mountain-sun', 'study' => 'fa-graduation-cap'];
$skillLine = ['red', 'blue', 'orange', 'green', 'purple'];
$years = (int) date('Y') - 2018;

// Hero network map: each line runs from Central to its terminus. [colour, svg path, terminus x, y, label, href, label anchor]
$network = [
    ['red',    'M300 210 H130 L70 150',        70, 150, 'Origin',     '#origin',  'end'],
    ['blue',   'M300 210 V110 L370 40 H480',  480,  40, 'Route',      '#route',   'start'],
    ['green',  'M300 210 H470 L540 280 V350', 540, 350, 'Travel',     '#travel',  'end'],
    ['orange', 'M300 210 V320 L240 380 H120', 120, 380, 'Skills',     '#skills',  'end'],
    ['purple', 'M300 210 L200 110 V40',       200,  40, 'Departures', '#board',   'end'],
    ['pink',   'M300 210 L380 290 V390',      380, 390, 'Tickets',    '#contact', 'start'],
];
?>

<!-- ============ HERO / NETWORK MAP ============ -->
<section id="hero" class="hero">
  <div class="wrap hero-grid">
    <div class="hero-copy">
      <p class="sign"><span class="line-badge sm c-red">1</span> Now arriving at</p>
      <h1 class="hero-title"><?= e($p['name']) ?></h1>
      <p class="hero-roles">
        <?php foreach ($p['roles'] as $i => $r): ?><span class="role c-<?= e($skillLine[$i % 5]) ?>"><span class="dot"></span><?= e($r) ?></span><?php endforeach; ?>
      </p>
      <p class="hero-intro"><?= e($p['intro']) ?></p>
      <div class="hero-cta">
        <a href="#origin" class="btn btn-primary"><i class="fas fa-train-subway"></i> Start the ride</a>
        <a href="<?= url('/resume') ?>" class="btn btn-ghost"><i class="fas fa-id-card"></i> Resume</a>
      </div>
      <dl class="hero-stats">
        <div><dt>Years in service</dt><dd><span data-count="<?= $years ?>">0</span>+</dd></div>
        <div><dt>Stations visited</dt><dd><span data-count="<?= count($places) ?>">0</span></dd></div>
        <div><dt>Skills on the network</dt><dd><span data-count="<?= count($allSkills) ?>">0</span></dd></div>
      </dl>
    </div>

    <figure class="network reveal" aria-label="Network map of this site. Each line leads to a section.">
      <svg viewBox="0 0 600 430" role="group">
        <g class="lines">
          <?php foreach ($network as [$c, $d]): ?><path class="ln s-<?= e($c) ?>" d="<?= e($d) ?>" pathLength="1"/><?php endforeach; ?>
        </g>
        <?php foreach ($network as $i => [$c, $d, $x, $y, $label, $href, $anchor]):
          $tx = $anchor === 'end' ? $x - 16 : $x + 16; ?>
          <a href="<?= e($href) ?>" class="term" style="--i:<?= $i ?>">
            <circle cx="<?= $x ?>" cy="<?= $y ?>" r="11" class="term-dot s-<?= e($c) ?>"/>
            <text x="<?= $tx ?>" y="<?= $y + 5 ?>" text-anchor="<?= e($anchor) ?>"><?= e($label) ?></text>
          </a>
        <?php endforeach; ?>
        <g class="central">
          <rect x="268" y="188" width="64" height="44" rx="22"/>
          <text x="300" y="215" text-anchor="middle"><?= e(OWNER_INITIALS) ?></text>
        </g>
        <text x="300" y="258" text-anchor="middle" class="central-lbl">Central</text>
      </svg>
      <figcaption class="mono small muted">Tap a terminus to jump to that line</figcaption>
    </figure>
  </div>
</section>

<!-- ============ ORIGIN (ABOUT) ============ -->
<section id="origin" class="sec" data-station="0">
  <div class="wrap">
    <?= view('partials/section-title', ['code' => '1', 'color' => 'red', 'eyebrow' => 'Origin station', 'title' => 'Engineer by craft. Explorer by heart.']) ?>
    <div class="origin-grid">
      <figure class="origin-photo reveal">
        <img src="<?= e(url($p['photo'])) ?>" alt="<?= e($p['name']) ?>">
        <figcaption><span class="roundel sm" aria-hidden="true"><span><?= e(OWNER_INITIALS) ?></span></span> <?= e(OWNER_NAME) ?> · <?= e(data('profile')['location']) ?></figcaption>
      </figure>
      <div class="origin-copy reveal">
        <?php foreach ($p['about'] as $para): ?><p class="lead"><?= $para /* trusted copy from data/profile.php */ ?></p><?php endforeach; ?>
        <blockquote class="notice"><span class="notice-tag">Passenger notice</span><?= e(trim($p['quote'], '“”')) ?></blockquote>
      </div>
    </div>
    <ul class="traits">
      <?php foreach ($p['highlights'] as $i => $h): ?>
        <li class="card trait reveal" style="transition-delay:<?= $i * 0.05 ?>s">
          <span class="trait-icon c-<?= e($skillLine[$i % 5]) ?>"><i class="fas <?= e($h['icon']) ?>"></i></span>
          <h3><?= e($h['label']) ?></h3>
          <p class="muted"><?= e($h['desc']) ?></p>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
</section>

<!-- ============ ROUTE (JOURNEY) ============ -->
<section id="route" class="sec sec-alt" data-station="1">
  <div class="wrap">
    <?= view('partials/section-title', ['code' => '2', 'color' => 'blue', 'eyebrow' => 'The route so far', 'title' => 'Every stop that made me', 'sub' => 'Blue for study, red for work, green for travel. Scroll and the train follows you.']) ?>
    <div class="legend">
      <?php foreach ($typeLine as $type => $c): ?><span><span class="dot c-<?= e($c) ?>"></span><?= e(ucfirst($type)) ?></span><?php endforeach; ?>
    </div>
    <ol class="route" id="route-line">
      <li class="train" aria-hidden="true"><i class="fas fa-train-subway"></i></li>
      <?php foreach ($journey as $i => $j): $c = $typeLine[$j['type']] ?? 'blue'; ?>
        <li class="route-stop c-<?= e($c) ?> reveal">
          <span class="route-dot" aria-hidden="true"></span>
          <div class="card route-card">
            <p class="route-meta"><span class="year"><?= (int) $j['year'] ?></span><span class="tag c-<?= e($c) ?>"><i class="fas <?= e($typeIcon[$j['type']] ?? 'fa-star') ?>"></i> <?= e(ucfirst($j['type'])) ?></span></p>
            <h3><?= e($j['title']) ?></h3>
            <p class="muted"><?= e($j['desc']) ?></p>
          </div>
        </li>
      <?php endforeach; ?>
      <li class="route-stop route-next">
        <span class="route-dot" aria-hidden="true"></span>
        <div class="card route-card"><p class="route-meta"><span class="year">Next</span></p><h3>Line extension under construction</h3><p class="muted">Service to new stations coming soon.</p></div>
      </li>
    </ol>
  </div>
</section>

<!-- ============ TRAVEL LINE (PLACES + VLOGS) ============ -->
<section id="travel" class="sec" data-station="2">
  <div class="wrap">
    <?= view('partials/section-title', ['code' => '3', 'color' => 'green', 'eyebrow' => 'The travel line', 'title' => count($places) . ' stations and counting', 'sub' => 'Stations with a ▶ are interchanges with my YouTube channel.']) ?>
    <ol class="strip reveal">
      <?php foreach ($places as [$name, $lat, $lng, $video]): ?>
        <li class="<?= $video ? 'has-vlog' : '' ?>">
          <?php if ($video): ?>
            <a href="<?= e($video) ?>" target="_blank" rel="noopener"><span class="stn"><?= e($name) ?></span><span class="vlog-tag"><i class="fas fa-play"></i> vlog</span></a>
          <?php else: ?>
            <span class="stn"><?= e($name) ?></span>
          <?php endif; ?>
          <small class="mono muted"><?= number_format($lat, 2) ?>°N <?= number_format($lng, 2) ?>°E</small>
        </li>
      <?php endforeach; ?>
    </ol>

    <h3 class="sub-head reveal"><i class="fas fa-tv"></i> On the platform screens</h3>
    <div class="screens">
      <?php foreach (data('vlogs') as $i => $v): ?>
        <article class="card screen reveal">
          <button type="button" class="yt-lite" data-id="<?= e($v['id']) ?>" aria-label="Play: <?= e($v['title']) ?>">
            <img src="https://i.ytimg.com/vi/<?= e($v['id']) ?>/hqdefault.jpg" alt="" loading="lazy">
            <span class="yt-play"><i class="fas fa-play"></i></span>
            <span class="platform mono">Platform <?= $i + 1 ?></span>
          </button>
          <div class="screen-body">
            <h3><?= e($v['title']) ?></h3>
            <p class="muted"><?= e($v['desc']) ?></p>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
    <p class="center mt-8"><a href="<?= e($p['youtube']) ?>" target="_blank" rel="noopener" class="btn btn-ghost"><i class="fab fa-youtube"></i> Full channel</a></p>
  </div>
</section>

<!-- ============ SKILL LINES ============ -->
<section id="skills" class="sec sec-alt" data-station="3">
  <div class="wrap">
    <?= view('partials/section-title', ['code' => '4', 'color' => 'orange', 'eyebrow' => 'Skill lines', 'title' => 'The tech stack, line by line', 'sub' => 'Like the diagram above a train door: every stop is a tool I work with.']) ?>
    <div class="skill-lines">
      <?php foreach ($skills as $i => $s): $c = $skillLine[$i % 5]; ?>
        <article class="card skill-line c-<?= e($c) ?> reveal">
          <header><span class="line-badge sm c-<?= e($c) ?>"><i class="fas <?= e($s['icon']) ?>"></i></span><h3><?= e($s['title']) ?></h3><span class="mono muted small"><?= count($s['items']) ?> stops</span></header>
          <ol class="stops">
            <?php foreach ($s['items'] as $item): ?><li><span class="stop" aria-hidden="true"></span><span><?= e($item) ?></span></li><?php endforeach; ?>
          </ol>
        </article>
      <?php endforeach; ?>
    </div>
    <p class="mt-8">
      <a href="<?= url('/resume') ?>" class="btn btn-primary"><i class="fas fa-id-card"></i> Resume</a>
      <a href="<?= e($p['resume_url']) ?>" target="_blank" rel="noopener" class="btn btn-ghost">Dev profile</a>
    </p>
  </div>
</section>

<!-- ============ DEPARTURES (BLOGS) ============ -->
<section id="board" class="sec" data-station="4">
  <div class="wrap">
    <?= view('partials/section-title', ['code' => '5', 'color' => 'purple', 'eyebrow' => 'Departures', 'title' => 'Latest posts leaving now', 'sub' => 'Notes on work, AI and the road.']) ?>
    <?= view('partials/board', ['blogs' => $departures]) ?>
    <p class="center mt-8"><a href="<?= url('/blogs') ?>" class="btn btn-ghost"><i class="fas fa-list"></i> All departures</a></p>
  </div>
</section>
