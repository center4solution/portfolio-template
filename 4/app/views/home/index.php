<?php
$p = data('profile');
$journey = data('journey');
usort($journey, fn($a, $b) => $a['year'] <=> $b['year']);
$places = data('places');
$skills = data('skills');
$blogs = published_blogs(3);
$allSkills = array_merge(...array_column($skills, 'items'));
$typeIcon = ['work' => 'fa-microchip', 'travel' => 'fa-shuttle-space', 'study' => 'fa-graduation-cap'];
$years = (int) date('Y') - 2018;
?>

<!-- ============ HERO ============ -->
<section id="hero" class="hero">
  <div class="wrap hero-grid">
    <div class="hero-copy">
      <p class="status-pill hero-in"><span class="led"></span> ONLINE · open to interesting work</p>
      <h1 class="hero-title hero-in" style="--d:.1s">
        <span class="ht-small mono">// hello_world, I am</span>
        <span class="ht-name" data-scramble data-text="<?= e(strtoupper($p['name'])) ?>"><?= e(strtoupper($p['name'])) ?></span>
      </h1>
      <p class="hero-roles hero-in" style="--d:.2s">
        <?php foreach ($p['roles'] as $r): ?><span class="role-tag"><?= e($r) ?></span><?php endforeach; ?>
      </p>
      <p class="hero-intro hero-in" style="--d:.3s"><?= e($p['intro']) ?></p>
      <div class="hero-cta hero-in" style="--d:.4s">
        <a href="#identity" class="btn btn-primary"><i class="fas fa-power-off"></i> Initialize</a>
        <button type="button" class="btn btn-ink" data-open-palette><i class="fas fa-terminal"></i> Command palette <kbd>Ctrl K</kbd></button>
      </div>
      <dl class="hero-stats hero-in" style="--d:.5s">
        <div><dt>Uptime</dt><dd><span data-count="<?= $years ?>">0</span>+ yrs</dd></div>
        <div><dt>Locations</dt><dd><span data-count="<?= count($places) ?>">0</span></dd></div>
        <div><dt>Modules</dt><dd><span data-count="<?= count($allSkills) ?>">0</span></dd></div>
      </dl>
    </div>

    <div class="hero-visual hero-in" style="--d:.25s">
      <div class="orb" data-tilt>
        <div class="ring r1"></div><div class="ring r2"></div><div class="ring r3"></div>
        <div class="avatar"><img src="<?= e(url($p['photo'])) ?>" alt="<?= e($p['name']) ?>"></div>
        <span class="sat s1"><i class="fas fa-code"></i></span>
        <span class="sat s2"><i class="fas fa-camera"></i></span>
        <span class="sat s3"><i class="fas fa-mountain"></i></span>
      </div>
      <div class="hud readout mono">
        <p><span class="muted">ID</span> <?= e(OWNER_INITIALS) ?>-0x2018</p>
        <p><span class="muted">LOC</span> 30.73°N 76.78°E</p>
        <p><span class="muted">MODE</span> <span class="neon">build + wander</span></p>
      </div>
    </div>
  </div>
  <a href="#identity" class="scroll-hint mono" aria-label="Scroll down">scroll <i class="fas fa-angle-down"></i></a>
</section>

<!-- ============ IDENTITY ============ -->
<section id="identity" class="sec">
  <div class="wrap">
    <?= view('partials/section-title', ['num' => '01', 'eyebrow' => 'Identity', 'title' => 'Engineer by craft. Explorer by heart.']) ?>
    <div class="bento">
      <article class="hud cell cell-lg reveal" data-spotlight>
        <p class="kicker">profile.md</p>
        <?php foreach ($p['about'] as $para): ?><p class="lead"><?= $para /* trusted copy from data/profile.php */ ?></p><?php endforeach; ?>
      </article>
      <article class="hud cell cell-quote reveal" data-spotlight>
        <i class="fas fa-quote-left neon"></i>
        <p><?= e(trim($p['quote'], '“”')) ?></p>
      </article>
    </div>
    <div class="traits">
      <?php foreach ($p['highlights'] as $i => $h): ?>
        <article class="hud trait <?= $i < 2 ? 'trait-wide' : '' ?> reveal" data-spotlight style="transition-delay:<?= $i * 0.06 ?>s">
          <div class="trait-top">
            <span class="trait-icon"><i class="fas <?= e($h['icon']) ?>"></i></span>
            <span class="mono muted">0<?= $i + 1 ?></span>
          </div>
          <h3><?= e($h['label']) ?></h3>
          <?php if (!empty($h['desc'])): ?><p class="muted"><?= e($h['desc']) ?></p><?php endif; ?>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ============ MISSION LOG ============ -->
<section id="log" class="sec">
  <div class="wrap">
    <?= view('partials/section-title', ['num' => '02', 'eyebrow' => 'Mission log', 'title' => 'Moments that made me', 'sub' => 'Every launch, every landing. Drag or scroll sideways.']) ?>
  </div>
  <p class="wrap log-hint mono" aria-hidden="true"><i class="fas fa-arrow-left"></i> earlier missions</p>
  <div class="log-rail" data-drag-scroll data-start-end>
    <ol class="log-track">
      <?php foreach ($journey as $i => $j): ?>
        <li class="hud log-card reveal" data-type="<?= e($j['type']) ?>">
          <div class="log-top">
            <span class="mono muted">MSN-<?= sprintf('%03d', $i + 1) ?></span>
            <span class="log-icon"><i class="fas <?= e($typeIcon[$j['type']] ?? 'fa-star') ?>"></i></span>
          </div>
          <p class="log-year"><?= (int) $j['year'] ?></p>
          <h3><?= e($j['title']) ?></h3>
          <p class="muted"><?= e($j['desc']) ?></p>
          <span class="log-type mono"><?= e(strtoupper($j['type'])) ?></span>
        </li>
      <?php endforeach; ?>
      <li class="log-card log-next"><span class="mono">MSN-<?= sprintf('%03d', count($journey) + 1) ?></span><p>Next launch<br>loading…</p><span class="loader"></span></li>
    </ol>
  </div>
</section>

<!-- ============ GLOBE ============ -->
<section id="globe" class="sec">
  <div class="wrap">
    <?= view('partials/section-title', ['num' => '03', 'eyebrow' => 'Geo telemetry', 'title' => 'Where these stories happened', 'sub' => count($places) . ' coordinates logged. Drag the globe, or hover a location to lock on.']) ?>
    <div class="globe-grid">
      <div class="hud globe-frame reveal">
        <canvas id="globe-canvas" data-places='<?= e(json_encode($places, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)) ?>' aria-label="Rotating globe showing visited places" role="img"></canvas>
        <div class="globe-hud mono">
          <span id="globe-target">TARGET: —</span>
          <span class="muted">drag to rotate · scroll to zoom</span>
        </div>
        <div class="globe-zoom">
          <button type="button" data-zoom="in" aria-label="Zoom in"><i class="fas fa-plus"></i></button>
          <button type="button" data-zoom="out" aria-label="Zoom out"><i class="fas fa-minus"></i></button>
          <button type="button" data-zoom="reset" aria-label="Reset view"><i class="fas fa-crosshairs"></i></button>
        </div>
      </div>
      <div class="hud loc-list reveal">
        <div class="loc-head mono"><span>LOCATION</span><span>SIGNAL</span></div>
        <ul>
          <?php foreach ($places as $i => [$name, $lat, $lng, $video]): ?>
            <li data-index="<?= $i ?>">
              <?php if ($video): ?><a href="<?= e($video) ?>" target="_blank" rel="noopener"><?php else: ?><span class="loc-row"><?php endif; ?>
                <span class="loc-name"><?= e($name) ?><small class="mono"><?= number_format($lat, 2) ?>°N <?= number_format($lng, 2) ?>°E</small></span>
                <span class="loc-sig <?= $video ? 'on' : '' ?>"><?= $video ? '▶ VLOG' : '· · ·' ?></span>
              <?php if ($video): ?></a><?php else: ?></span><?php endif; ?>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
  </div>
</section>

<!-- ============ VLOGS ============ -->
<section id="vlogs" class="sec">
  <div class="wrap">
    <?= view('partials/section-title', ['num' => '04', 'eyebrow' => 'Transmissions', 'title' => 'Vlogs & visual journeys']) ?>
    <div class="vlog-grid">
      <?php foreach (data('vlogs') as $i => $v): ?>
        <article class="hud vlog reveal" data-tilt>
          <button type="button" class="yt-lite" data-id="<?= e($v['id']) ?>" aria-label="Play: <?= e($v['title']) ?>">
            <img src="https://i.ytimg.com/vi/<?= e($v['id']) ?>/hqdefault.jpg" alt="" loading="lazy">
            <span class="yt-play"><i class="fas fa-play"></i></span>
            <span class="rec mono"><span class="led red"></span> REC <?= sprintf('%02d', $i + 1) ?></span>
          </button>
          <div class="vlog-body">
            <h3><?= e($v['title']) ?></h3>
            <p class="muted"><?= e($v['desc']) ?></p>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
    <p class="center mt-8"><a href="<?= e($p['youtube']) ?>" target="_blank" rel="noopener" class="btn btn-ink"><i class="fab fa-youtube"></i> Full channel</a></p>
  </div>
</section>

<!-- ============ SYSTEMS ============ -->
<section id="systems" class="sec">
  <div class="wrap">
    <?= view('partials/section-title', ['num' => '05', 'eyebrow' => 'Systems', 'title' => 'Tech stack & modules', 'sub' => 'Pick a system to see what it runs.']) ?>
    <div class="systems">
      <div class="orbit reveal" aria-hidden="true">
        <div class="orbit-core"><span class="mono">STACK</span><b><?= count($allSkills) ?></b></div>
        <?php foreach ($allSkills as $i => $s):
          $ring = $i % 3;
          $angle = (int) round(360 / count($allSkills) * $i * 3) % 360; ?>
          <span class="orbit-node ring-<?= $ring ?>" style="--a:<?= $angle ?>deg"><span><?= e($s) ?></span></span>
        <?php endforeach; ?>
      </div>
      <div class="sys-panel reveal">
        <div class="sys-tabs" role="tablist">
          <?php foreach ($skills as $i => $s): ?>
            <button type="button" role="tab" class="sys-tab <?= $i === 0 ? 'is-active' : '' ?>" aria-selected="<?= $i === 0 ? 'true' : 'false' ?>" data-tab="sys-<?= $i ?>">
              <i class="fas <?= e($s['icon']) ?>"></i><span><?= e($s['title']) ?></span>
            </button>
          <?php endforeach; ?>
        </div>
        <?php foreach ($skills as $i => $s): ?>
          <div class="hud sys-body" id="sys-<?= $i ?>" role="tabpanel" <?= $i ? 'hidden' : '' ?>>
            <p class="kicker">module/<?= e(strtolower(preg_replace('/[^a-z]+/i', '-', $s['title']))) ?></p>
            <ul class="sys-items">
              <?php foreach ($s['items'] as $k => $item): ?>
                <li style="--i:<?= $k ?>"><span><?= e($item) ?></span><span class="bar"><i></i></span><span class="mono ok">OK</span></li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endforeach; ?>
        <p class="mt-8">
          <a href="<?= url('/resume') ?>" class="btn btn-primary"><i class="fas fa-id-card"></i> Resume</a>
          <a href="<?= e($p['resume_url']) ?>" target="_blank" rel="noopener" class="btn btn-ink">Dev profile</a>
        </p>
      </div>
    </div>
  </div>
</section>

<!-- ============ ARCHIVE ============ -->
<section id="archive" class="sec">
  <div class="wrap">
    <?= view('partials/section-title', ['num' => '·', 'eyebrow' => 'Archive', 'title' => 'Latest logs', 'sub' => 'Notes on work, AI and the road.']) ?>
    <div class="article-grid">
      <?php foreach ($blogs as $blog): ?><div><?= view('partials/blog-card', ['blog' => $blog]) ?></div><?php endforeach; ?>
    </div>
    <p class="center mt-8"><a href="<?= url('/blogs') ?>" class="btn btn-ink"><i class="fas fa-database"></i> Full archive</a></p>
  </div>
</section>
