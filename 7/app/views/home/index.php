<?php
require_once VERSION_DIR . '/app/orbit.php';
$p = data('profile');
$journey = data('journey');
usort($journey, fn($a, $b) => $a['year'] <=> $b['year']);
$places = data('places');
$skills = data('skills');
$latest = published_blogs(6);
$allSkills = array_merge(...array_column($skills, 'items'));
$years = (int) date('Y') - 2018;
$typeIcon = ['work' => 'fa-rocket', 'travel' => 'fa-shuttle-space', 'study' => 'fa-satellite'];
$typeLabel = ['work' => 'Launch', 'travel' => 'Expedition', 'study' => 'Training'];
$planetColor = ['#ff8a3d', '#4cc9f0', '#b388ff', '#5eead4', '#f472b6'];
?>

<!-- ============ LAUNCH (hero) ============ -->
<section id="launch" class="hero">
  <div class="wrap hero-grid">
    <div class="hero-copy reveal">
      <p class="kicker mono"><span class="pulse"></span> Ground control · all systems go</p>
      <h1 class="hero-title"><?= e($p['name']) ?></h1>
      <p class="hero-roles mono">
        <?php foreach ($p['roles'] as $r): ?><span><?= e($r) ?></span><?php endforeach; ?>
      </p>
      <p class="hero-intro"><?= e($p['intro']) ?></p>
      <div class="hero-cta">
        <a href="#missions" class="btn btn-primary"><i class="fas fa-rocket"></i> Mission log</a>
        <a href="<?= url('/resume') ?>" class="btn btn-ghost"><i class="fas fa-id-badge"></i> Crew file</a>
      </div>
      <dl class="telemetry mono">
        <div><dt>Missions</dt><dd data-count="<?= count($journey) ?>">0</dd></div>
        <div><dt>Satellites</dt><dd data-count="<?= count($allSkills) ?>">0</dd></div>
        <div><dt>Stars charted</dt><dd data-count="<?= count($places) ?>">0</dd></div>
        <div><dt>Years in flight</dt><dd><span data-count="<?= $years ?>">0</span>+</dd></div>
      </dl>
    </div>

    <!-- Orrery: the photo is the planet, every skill group is an orbit -->
    <div class="orrery reveal" aria-label="Skills orbiting around <?= e($p['name']) ?>">
      <?php foreach ($skills as $i => $s): ?>
        <div class="ring" style="--r: <?= 34 + $i * 15 ?>%; --t: <?= 22 + $i * 9 ?>s; --c: <?= e($planetColor[$i % 5]) ?>; --start: <?= orbit_num('o' . $s['title'], 0, 359) ?>deg">
          <span class="moon" title="<?= e($s['title']) ?>"><i class="fas <?= e($s['icon']) ?>"></i></span>
        </div>
      <?php endforeach; ?>
      <div class="planet"><img src="<?= e(url($p['photo'])) ?>" alt="<?= e($p['name']) ?>"></div>
    </div>
  </div>
  <a href="#crew" class="scroll-cue mono" aria-label="Scroll down">T-minus scroll <i class="fas fa-angles-down"></i></a>
</section>

<!-- ============ CREW (about) ============ -->
<section id="crew" class="sec">
  <div class="wrap">
    <?= view('partials/section-title', ['icon' => 'fa-user-astronaut', 'eyebrow' => 'Crew profile', 'title' => 'Engineer by craft. Explorer by heart.']) ?>
    <div class="about-grid">
      <div class="panel about-copy reveal">
        <?php foreach ($p['about'] as $para): ?><p class="lead"><?= $para /* trusted copy from data/profile.php */ ?></p><?php endforeach; ?>
        <blockquote class="radio mono"><span class="muted">&gt; incoming message</span><br><?= e(trim($p['quote'], '“”')) ?></blockquote>
      </div>
      <ul class="patches">
        <?php foreach ($p['highlights'] as $i => $h): ?>
          <li class="panel patch reveal">
            <span class="patch-badge" style="--c: <?= e($planetColor[$i % 5]) ?>"><i class="fas <?= e($h['icon']) ?>"></i></span>
            <div><h3><?= e($h['label']) ?></h3><p class="muted"><?= e($h['desc']) ?></p></div>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
</section>

<!-- ============ MISSIONS (journey) ============ -->
<?php
// Each milestone is a planet on its own tilted orbit around the origin star.
// The last orbit is the next mission, still empty.
$missions = $journey;
$missions[] = ['year' => 0, 'type' => 'next', 'title' => 'Next mission', 'desc' => 'Fuelled up and waiting on the pad. Got a destination in mind?'];
$orbitCount = count($missions);
$typeColor = ['work' => '#ff8a3d', 'travel' => '#4cc9f0', 'study' => '#b388ff', 'next' => '#9aa3c7'];
?>
<section id="missions" class="sec">
  <div class="wrap">
    <?= view('partials/section-title', ['icon' => 'fa-rocket', 'eyebrow' => 'Mission log', 'title' => 'Every milestone is a world in orbit', 'sub' => 'The first launch sits closest to the origin, the newest furthest out. The latest mission is open. Pick any planet to see its file.']) ?>
    <div class="orbit-log reveal" id="orbit-log">
      <div class="panel orbit-stage">
        <svg viewBox="0 0 1000 460" role="img" aria-label="Career and travel milestones as planets orbiting a star">
          <defs>
            <radialGradient id="sun-glow"><stop offset="0" stop-color="#fff3d6"/><stop offset=".35" stop-color="#ffc46b"/><stop offset=".7" stop-color="#ff8a3d" stop-opacity=".5"/><stop offset="1" stop-color="#ff8a3d" stop-opacity="0"/></radialGradient>
          </defs>
          <g class="orbit-paths">
            <?php foreach ($missions as $i => $m): $rx = 95 + $i * 46; ?>
              <ellipse cx="500" cy="230" rx="<?= $rx ?>" ry="<?= round($rx * .4, 1) ?>" class="<?= $m['type'] === 'next' ? 'is-next' : '' ?>" data-orbit="<?= $i ?>"/>
            <?php endforeach; ?>
          </g>
          <line class="beam" id="beam" x1="500" y1="230" x2="500" y2="230"/>
          <circle cx="500" cy="230" r="70" fill="url(#sun-glow)" class="sun-glow"/>
          <circle cx="500" cy="230" r="22" class="sun"/>
          <text x="500" y="282" class="sun-label">ORIGIN · BIKANER</text>
          <g class="mplanets">
            <?php foreach ($missions as $i => $m): $rx = 95 + $i * 46; $a = orbit_num('a' . $m['title'], 0, 359); ?>
              <g class="mplanet<?= $i === $orbitCount - 2 ? ' is-active' : '' ?>" data-i="<?= $i ?>" data-rx="<?= $rx ?>" data-ry="<?= round($rx * .4, 1) ?>" data-a="<?= $a ?>"
                 transform="translate(<?= round(500 + $rx * cos(deg2rad($a)), 1) ?> <?= round(230 + $rx * .4 * sin(deg2rad($a)), 1) ?>)"
                 style="--c: <?= $typeColor[$m['type']] ?? '#fff' ?>" tabindex="0" role="button"
                 aria-label="<?= e(($m['year'] ? $m['year'] . ': ' : '') . $m['title']) ?>">
                <circle r="19" class="sel"/>
                <circle r="<?= $m['type'] === 'next' ? 7 : 9 ?>" class="body"/>
                <text y="-17"><?= $m['year'] ? (int) $m['year'] : '?' ?></text>
              </g>
            <?php endforeach; ?>
          </g>
        </svg>
        <p class="stage-legend mono">
          <span><i style="--c: #ff8a3d"></i> Launch (work)</span>
          <span><i style="--c: #4cc9f0"></i> Expedition (travel)</span>
          <span><i style="--c: #b388ff"></i> Training (study)</span>
        </p>
      </div>

      <div class="panel console" aria-live="polite">
        <p class="console-head mono">
          <span><span class="pulse"></span> Mission file</span>
          <span class="console-nav">
            <button type="button" class="icon-btn" data-step="-1" aria-label="Previous mission"><i class="fas fa-chevron-left"></i></button>
            <button type="button" class="icon-btn" data-step="1" aria-label="Next mission"><i class="fas fa-chevron-right"></i></button>
          </span>
        </p>
        <?php foreach ($missions as $i => $m): $next = $m['type'] === 'next'; ?>
          <article class="m-file" data-i="<?= $i ?>" style="--c: <?= $typeColor[$m['type']] ?? '#fff' ?>">
            <p class="m-code mono"><?= e(mission_code($i + 1)) ?> · orbit <?= $i + 1 ?> of <?= $orbitCount ?></p>
            <p class="m-year"><?= $next ? 'T-minus' : (int) $m['year'] ?></p>
            <p class="m-type mono"><i class="fas <?= $next ? 'fa-hourglass-half' : e($typeIcon[$m['type']] ?? 'fa-star') ?>"></i> <?= $next ? 'Countdown' : e($typeLabel[$m['type']] ?? $m['type']) ?></p>
            <h3><?= e($m['title']) ?></h3>
            <p class="muted"><?= e($m['desc']) ?><?php if ($next): ?> <a href="#comms">Open comms</a>.<?php endif; ?></p>
            <dl class="m-stats mono">
              <div><dt>Distance</dt><dd><?= number_format((95 + $i * 46) / 95, 2) ?> AU</dd></div>
              <div><dt>Period</dt><dd><?= number_format(pow((95 + $i * 46) / 95, 1.5), 1) ?> yr</dd></div>
              <div><dt>Status</dt><dd class="<?= $next ? 'wait' : 'ok' ?>"><?= $next ? 'On the pad' : ((int) $m['year'] > (int) date('Y') ? 'Scheduled' : 'Complete') ?></dd></div>
            </dl>
          </article>
        <?php endforeach; ?>
        <div class="m-dots" aria-hidden="true"><?php foreach ($missions as $i => $m): ?><span data-i="<?= $i ?>"></span><?php endforeach; ?></div>
      </div>
    </div>

    <div class="year-strip reveal" role="tablist" aria-label="Missions by year">
      <?php foreach ($missions as $i => $m): ?>
        <button type="button" role="tab" data-i="<?= $i ?>" style="--c: <?= $typeColor[$m['type']] ?? '#fff' ?>"><b><?= $m['year'] ? (int) $m['year'] : 'Next' ?></b><small><?= e($m['title']) ?></small></button>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ============ ORBITS (skills) ============ -->
<?php
// Each skill group is a satellite constellation flying a sine-wave ground track
// across a flat world chart (1000x600). Every tool is one satellite.
$tracks = [];
foreach ($skills as $i => $s) {
    $amp = 90 + $i * 42;
    $phase = orbit_num('ph' . $s['title'], 0, 628) / 100;
    $pts = [];
    for ($x = 0; $x <= 1000; $x += 20) $pts[] = $x . ',' . round(300 + $amp * sin($x / 1000 * 2 * M_PI + $phase), 1);
    $tracks[] = ['amp' => $amp, 'phase' => $phase, 'path' => implode(' ', $pts)];
}
?>
<section id="orbits" class="sec">
  <div class="wrap">
    <?= view('partials/section-title', ['icon' => 'fa-satellite', 'eyebrow' => 'Satellite tracking', 'title' => 'Satellites in the stack', 'sub' => count($allSkills) . ' tools flying in ' . count($skills) . ' constellations. Lock onto one to see every satellite in it.']) ?>
    <div class="tracker reveal" id="tracker">
      <div class="panel track-screen">
        <p class="screen-top mono"><span><span class="pulse"></span> Ground track · live</span><span class="muted" id="track-utc">UTC --:--:--</span></p>
        <svg viewBox="0 0 1000 600" preserveAspectRatio="xMidYMid slice" role="img" aria-label="Skill groups shown as satellite ground tracks">
          <g class="grid">
            <?php for ($x = 0; $x <= 1000; $x += 100): ?><line x1="<?= $x ?>" y1="0" x2="<?= $x ?>" y2="600"/><?php endfor; ?>
            <?php for ($y = 0; $y <= 600; $y += 100): ?><line x1="0" y1="<?= $y ?>" x2="1000" y2="<?= $y ?>"/><?php endfor; ?>
            <line x1="0" y1="300" x2="1000" y2="300" class="equator"/>
          </g>
          <g class="ground-tracks">
            <?php foreach ($tracks as $i => $t): ?><polyline points="<?= $t['path'] ?>" data-g="<?= $i ?>" style="--c: <?= e($planetColor[$i % 5]) ?>"/><?php endforeach; ?>
          </g>
          <g class="sats">
            <?php foreach ($skills as $i => $s): $n = count($s['items']); foreach ($s['items'] as $k => $item): $x0 = round(($k + .5) / $n * 1000 + $i * 37) % 1000; ?>
              <g class="tsat" data-g="<?= $i ?>" data-x="<?= $x0 ?>" data-amp="<?= $tracks[$i]['amp'] ?>" data-ph="<?= $tracks[$i]['phase'] ?>" style="--c: <?= e($planetColor[$i % 5]) ?>"
                 transform="translate(<?= $x0 ?> <?= round(300 + $tracks[$i]['amp'] * sin($x0 / 1000 * 2 * M_PI + $tracks[$i]['phase']), 1) ?>)">
                <circle r="14" class="cone"/>
                <rect x="-9" y="-2" width="6" height="4" class="wing"/><rect x="3" y="-2" width="6" height="4" class="wing"/>
                <circle r="3.5" class="core"/>
                <text x="10" y="-9"><?= e($item) ?></text>
              </g>
            <?php endforeach; endforeach; ?>
          </g>
          <g class="station" transform="translate(500 300)" aria-hidden="true">
            <circle r="60" class="sweep-range"/>
            <path d="M0 0 L60 0 A60 60 0 0 0 42.4 -42.4 Z" class="sweep"/>
            <circle r="4" class="dish"/>
          </g>
        </svg>
      </div>

      <div class="panel track-table">
        <div class="groups" role="tablist" aria-label="Skill constellations">
          <?php foreach ($skills as $i => $s): ?>
            <button type="button" role="tab" data-g="<?= $i ?>" class="<?= $i === 0 ? 'is-active' : '' ?>" style="--c: <?= e($planetColor[$i % 5]) ?>">
              <i class="fas <?= e($s['icon']) ?>"></i><span><?= e($s['title']) ?></span><small class="mono"><?= count($s['items']) ?></small>
            </button>
          <?php endforeach; ?>
        </div>
        <?php foreach ($skills as $i => $s): ?>
          <div class="sat-table" data-g="<?= $i ?>" style="--c: <?= e($planetColor[$i % 5]) ?>">
            <h3><i class="fas <?= e($s['icon']) ?>"></i> <?= e($s['title']) ?></h3>
            <div class="sat-head mono"><span>Sat</span><span>Name</span><span>Alt</span><span>Signal</span></div>
            <ul>
              <?php foreach ($s['items'] as $k => $item): $sig = orbit_num('sig' . $item, 3, 5); ?>
                <li>
                  <span class="mono muted"><?= chr(65 + $i) ?>-<?= str_pad((string) ($k + 1), 2, '0', STR_PAD_LEFT) ?></span>
                  <b><?= e($item) ?></b>
                  <span class="mono muted"><?= orbit_num('alt' . $item, 400, 36000) ?> km</span>
                  <span class="bars" aria-label="Signal <?= $sig ?> of 5"><?php for ($b = 1; $b <= 5; $b++): ?><i class="<?= $b <= $sig ? 'on' : '' ?>"></i><?php endfor; ?></span>
                </li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
    <p class="mt-8">
      <a href="<?= url('/resume') ?>" class="btn btn-primary"><i class="fas fa-id-badge"></i> Crew file</a>
      <a href="<?= e($p['resume_url']) ?>" target="_blank" rel="noopener" class="btn btn-ghost">Dev profile</a>
    </p>
  </div>
</section>

<!-- ============ STAR MAP (places + vlogs) ============ -->
<section id="starmap" class="sec">
  <div class="wrap">
    <?= view('partials/section-title', ['icon' => 'fa-satellite', 'eyebrow' => 'Landing sites', 'title' => count($places) . ' places charted so far', 'sub' => 'The Earth rolls toward you while satellites orbit below. Each place lights up as it passes, and the telescope follows it. Gold ones have footage, click to watch.']) ?>
    <div class="earth-view reveal" id="earth-view" data-places="<?= e(json_encode(array_map(fn($p) => [$p[0], (float) $p[1], (float) $p[2], $p[3]], $places), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)) ?>">
      <canvas id="earth-canvas" role="img" aria-label="Places I have visited, shown as city lights on a turning Earth, watched by a telescope"></canvas>
      <p class="earth-hud mono"><span><b id="earth-name">Watching the ground roll in</b></span><span id="earth-hint">Places pass by as the Earth turns</span></p>
    </div>
    <ul class="sr-only">
      <?php foreach ($places as [$name, $lat, $lng, $video]): ?><li><?php if ($video): ?><a href="<?= e($video) ?>" target="_blank" rel="noopener"><?= e($name) ?> (video)</a><?php else: ?><?= e($name) ?><?php endif; ?></li><?php endforeach; ?>
    </ul>

    <h3 class="sub-head reveal"><i class="fab fa-youtube"></i> Travel vlogs</h3>
    <div class="vlogs">
      <?php foreach (data('vlogs') as $v): ?>
        <article class="panel vlog reveal">
          <button type="button" class="yt-lite" data-id="<?= e($v['id']) ?>" aria-label="Play: <?= e($v['title']) ?>">
            <img src="https://i.ytimg.com/vi/<?= e($v['id']) ?>/hqdefault.jpg" alt="" loading="lazy">
            <span class="yt-play"><i class="fas fa-play"></i></span>
          </button>
          <div class="vlog-body">
            <h3><?= e($v['title']) ?></h3>
            <p class="muted"><?= e($v['desc']) ?></p>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
    <p class="center mt-8"><a href="<?= e($p['youtube']) ?>" target="_blank" rel="noopener" class="btn btn-ghost"><i class="fab fa-youtube"></i> Full channel</a></p>
  </div>
</section>

<!-- ============ TRANSMISSIONS (blogs) ============ -->
<section id="transmissions" class="sec">
  <div class="wrap">
    <?= view('partials/section-title', ['icon' => 'fa-tower-broadcast', 'eyebrow' => 'Latest transmissions', 'title' => 'Signals from the road', 'sub' => 'Notes on work, AI and travel.']) ?>
    <div class="post-grid">
      <?php foreach ($latest as $blog): ?><div><?= view('partials/blog-card', ['blog' => $blog]) ?></div><?php endforeach; ?>
    </div>
    <p class="center mt-8"><a href="<?= url('/blogs') ?>" class="btn btn-ghost"><i class="fas fa-satellite-dish"></i> All transmissions</a></p>
  </div>
</section>
