<?php
$p = data('profile');
$journey = data('journey');
usort($journey, fn($a, $b) => $a['year'] <=> $b['year']);
$typeIcon = ['work' => 'fa-briefcase', 'travel' => 'fa-mountain-sun', 'study' => 'fa-graduation-cap'];
$places = data('places');
$withVideo = count(array_filter($places, fn($pl) => $pl[3] !== ''));
$blogs = published_blogs(4);
$featured = array_shift($blogs);
$allSkills = array_merge(...array_column(data('skills'), 'items'));
$sides = [
    ['icon' => 'fa-code', 'title' => 'The Engineer', 'tone' => 'code', 'text' => 'Backends that scale, interfaces that feel right, and business logic understood before the first commit.', 'link' => '#skills', 'cta' => 'See the stack'],
    ['icon' => 'fa-camera-retro', 'title' => 'The Lens', 'tone' => 'lens', 'text' => 'Mountain light, old forts and crowded ghats, framed as honestly as I can.', 'link' => '#lens', 'cta' => 'View photos'],
    ['icon' => 'fa-route', 'title' => 'The Wanderer', 'tone' => 'road', 'text' => 'Solo trips, long rides and quiet mountain towns, logged as vlogs and pins on a map.', 'link' => '#travel', 'cta' => 'Open the map'],
];
$photos = [
    ['assets/imgs/2.jpg', 'Somewhere in the hills', -6],
    ['assets/imgs/3.jpg', 'On the road', 4],
    ['assets/imgs/4.jpg', 'Golden hour', -3],
    ['assets/imgs/5.jpg', 'Quiet corners', 6],
    ['assets/imgs/1.jpg', 'Behind the lens', -4],
];
?>

<!-- Hero -->
<section id="hero" class="hero relative min-h-[100svh] flex items-center text-white overflow-hidden">
  <div class="aurora" aria-hidden="true"><span></span><span></span><span></span></div>
  <div class="hero-grid" aria-hidden="true"></div>

  <div class="relative max-w-6xl mx-auto w-full px-4 sm:px-6 pt-28 pb-20 grid lg:grid-cols-[1.2fr_1fr] gap-14 items-center">
    <div>
      <p class="hero-in inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/5 px-3 py-1 text-xs text-white/80">
        <span class="pulse-dot"></span> Building &amp; wandering, based in <?= e(data('profile')['location']) ?>
      </p>
      <h1 class="font-serif text-5xl sm:text-6xl md:text-7xl font-semibold leading-[1.02] mt-6 hero-in" style="--d:.1s">
        Hi, I'm <span class="text-gradient"><?= e($p['name']) ?></span>
      </h1>
      <p class="mt-6 text-xl sm:text-2xl text-white/80 hero-in" style="--d:.2s">
        I'm a <span class="typer" data-words='<?= e(json_encode($p['roles'])) ?>'><?= e($p['roles'][0]) ?></span><span class="caret">|</span>
      </p>
      <p class="mt-5 max-w-lg text-white/65 leading-relaxed hero-in" style="--d:.3s"><?= e($p['intro']) ?></p>
      <div class="mt-9 flex flex-wrap gap-3 hero-in" style="--d:.4s">
        <a href="#sides" class="btn btn-primary magnetic">Explore my world <i class="fas fa-arrow-down text-xs"></i></a>
        <a href="<?= e($p['youtube']) ?>" target="_blank" rel="noopener" class="btn btn-ghost magnetic"><i class="fab fa-youtube"></i> Watch vlogs</a>
      </div>
    </div>

    <div class="hero-in relative mx-auto w-64 sm:w-72 lg:w-80" style="--d:.35s">
      <div class="photo-stack" data-tilt>
        <img src="<?= e(url('assets/imgs/3.jpg')) ?>" alt="" class="stack-back">
        <img src="<?= e(url($p['photo'])) ?>" alt="<?= e($p['name']) ?>" class="stack-front">
      </div>
      <span class="float-badge" style="--x:-2.5rem;--y:12%;--t:0s"><i class="fas fa-code"></i> code</span>
      <span class="float-badge" style="--x:calc(100% - 1rem);--y:38%;--t:-2s"><i class="fas fa-camera"></i> capture</span>
      <span class="float-badge" style="--x:-1.5rem;--y:78%;--t:-4s"><i class="fas fa-mountain"></i> explore</span>
    </div>
  </div>

  <a href="#sides" class="scroll-cue" aria-label="Scroll down"><span></span></a>
</section>

<!-- Skills marquee -->
<div class="marquee bg-brand text-ink" aria-hidden="true">
  <div class="marquee-track">
    <?php for ($i = 0; $i < 2; $i++): foreach ($allSkills as $s): ?>
      <span><?= e($s) ?></span><i class="fas fa-asterisk"></i>
    <?php endforeach; endfor; ?>
  </div>
</div>

<!-- Three sides -->
<section id="sides" class="section">
  <div class="max-w-6xl mx-auto px-4 sm:px-6">
    <?= view('partials/section-title', ['eyebrow' => 'About me', 'title' => 'Three sides, one story', 'sub' => 'Good code solves real problems, and good experiences start with curiosity. I bring the same drive to both.']) ?>
    <div class="grid md:grid-cols-3 gap-6">
      <?php foreach ($sides as $i => $s): ?>
        <a href="<?= e($s['link']) ?>" class="side-card tone-<?= e($s['tone']) ?> reveal" style="transition-delay: <?= $i * 0.1 ?>s">
          <span class="side-num">0<?= $i + 1 ?></span>
          <span class="side-icon"><i class="fas <?= e($s['icon']) ?>"></i></span>
          <h3 class="font-serif text-2xl font-semibold mt-6"><?= e($s['title']) ?></h3>
          <p class="mt-3 text-sm leading-relaxed opacity-80"><?= e($s['text']) ?></p>
          <span class="mt-6 inline-flex items-center gap-2 text-sm font-semibold"><?= e($s['cta']) ?> <i class="fas fa-arrow-right text-xs"></i></span>
        </a>
      <?php endforeach; ?>
    </div>

    <div class="mt-14 grid md:grid-cols-[1fr_1.3fr] gap-10 items-center">
      <blockquote class="font-serif text-2xl sm:text-3xl leading-snug text-ink reveal">
        <?= e($p['quote']) ?>
      </blockquote>
      <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 reveal">
        <?php foreach ($p['highlights'] as $h): ?>
          <div class="trait"><i class="fas <?= e($h['icon']) ?>"></i><span><?= e($h['label']) ?></span></div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<!-- Stats -->
<section class="bg-ink text-white py-14">
  <div class="max-w-6xl mx-auto px-4 sm:px-6 grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
    <?php foreach ([
        [(int) date('Y') - 2018, '+', 'years writing code'],
        [count($places), '', 'places pinned'],
        [count(published_blogs()), '', 'blogs written'],
        [count($allSkills), '+', 'tools in the kit'],
    ] as [$n, $suffix, $label]): ?>
      <div class="reveal">
        <p class="font-serif text-5xl md:text-6xl font-semibold text-gradient"><span data-count="<?= (int) $n ?>">0</span><?= e($suffix) ?></p>
        <p class="mt-2 text-xs uppercase tracking-[.2em] text-white/60"><?= e($label) ?></p>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- Journey -->
<section id="journey" class="section bg-paper">
  <div class="max-w-5xl mx-auto px-4 sm:px-6">
    <?= view('partials/section-title', ['eyebrow' => 'Journey', 'title' => 'Moments That Made Me', 'sub' => 'Highlights from the work and the road.']) ?>
    <ol class="road">
      <?php foreach ($journey as $i => $item): ?>
        <li class="road-stop <?= $i % 2 ? 'is-right' : 'is-left' ?> reveal" data-type="<?= e($item['type']) ?>">
          <span class="road-dot"><i class="fas <?= e($typeIcon[$item['type']] ?? 'fa-star') ?>"></i></span>
          <div class="road-card">
            <span class="road-year"><?= (int) $item['year'] ?></span>
            <h3 class="font-semibold text-ink text-lg"><?= e($item['title']) ?></h3>
            <p class="text-slate-600 text-sm mt-1 leading-relaxed"><?= e($item['desc']) ?></p>
          </div>
        </li>
      <?php endforeach; ?>
      <li class="road-end reveal"><span>To be continued…</span></li>
    </ol>
  </div>
</section>

<!-- Through my lens -->
<section id="lens" class="section overflow-hidden">
  <div class="max-w-6xl mx-auto px-4 sm:px-6">
    <?= view('partials/section-title', ['eyebrow' => 'Photography', 'title' => 'Through My Lens', 'sub' => 'A few frames from the road. Hover to straighten them, click to open.']) ?>
    <div class="polaroids">
      <?php foreach ($photos as [$src, $caption, $rot]): ?>
        <a href="<?= e(url($src)) ?>" class="polaroid reveal" style="--r:<?= (int) $rot ?>deg" data-lightbox>
          <img src="<?= e(url($src)) ?>" alt="<?= e($caption) ?>" loading="lazy">
          <span><?= e($caption) ?></span>
        </a>
      <?php endforeach; ?>
    </div>
    <p class="text-center mt-10">
      <a href="<?= e(data('profile')['instagram_photo']) ?>" target="_blank" rel="noopener" class="btn btn-outline"><i class="fab fa-instagram"></i> More on Instagram</a>
    </p>
  </div>
</section>

<!-- Travel map -->
<section id="travel" class="section bg-ink text-white relative overflow-hidden">
  <div class="aurora aurora-soft" aria-hidden="true"><span></span><span></span></div>
  <div class="relative max-w-6xl mx-auto px-4 sm:px-6">
    <div class="text-center mb-10 reveal">
      <p class="eyebrow text-brand">Travel</p>
      <h2 class="font-serif text-3xl md:text-4xl font-semibold">Where These Stories Happened</h2>
      <p class="text-white/60 mt-3">Each pin is a moment I lived and filmed. Green pins have a vlog.</p>
    </div>
    <div class="flex flex-wrap justify-center gap-3 mb-6 text-sm">
      <span class="stat-dark"><i class="fas fa-location-dot text-brand"></i> <b><?= count($places) ?></b> places</span>
      <span class="stat-dark"><i class="fas fa-video text-brand"></i> <b><?= $withVideo ?></b> with vlogs</span>
    </div>
    <div id="travel-map" class="map-frame h-[440px] md:h-[540px]"
         data-places='<?= e(json_encode($places, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)) ?>'></div>
  </div>
</section>

<!-- Vlogs -->
<section id="vlogs" class="section">
  <div class="max-w-6xl mx-auto px-4 sm:px-6">
    <?= view('partials/section-title', ['eyebrow' => 'Vlogs', 'title' => 'Vlogs & Visual Journeys', 'sub' => 'Stories captured in motion, raw and real.']) ?>
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
      <?php foreach (data('vlogs') as $i => $v): ?>
        <article class="vlog-card reveal" style="transition-delay: <?= $i * 0.1 ?>s">
          <button type="button" class="yt-lite aspect-video w-full relative bg-black" data-id="<?= e($v['id']) ?>" aria-label="Play: <?= e($v['title']) ?>">
            <img src="https://i.ytimg.com/vi/<?= e($v['id']) ?>/hqdefault.jpg" alt="" loading="lazy" class="w-full h-full object-cover">
            <span class="yt-play"><i class="fas fa-play"></i></span>
          </button>
          <div class="p-5">
            <h3 class="font-semibold text-ink"><?= e($v['title']) ?></h3>
            <p class="text-sm text-slate-600 mt-1"><?= e($v['desc']) ?></p>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
    <div class="text-center mt-10">
      <a href="<?= e($p['youtube']) ?>" target="_blank" rel="noopener" class="btn btn-red magnetic"><i class="fab fa-youtube"></i> Watch all on YouTube</a>
    </div>
  </div>
</section>

<!-- Skills -->
<section id="skills" class="section bg-paper">
  <div class="max-w-6xl mx-auto px-4 sm:px-6">
    <?= view('partials/section-title', ['eyebrow' => 'Skills', 'title' => 'Tech Stack & Expertise', 'sub' => 'The tools I use to design, build and ship full-stack products.']) ?>
    <div class="bento">
      <?php foreach (data('skills') as $i => $s): ?>
        <div class="bento-cell <?= $i === 0 ? 'bento-wide' : '' ?> reveal" data-spotlight>
          <div class="flex items-center gap-3 mb-5">
            <span class="icon-badge"><i class="fas <?= e($s['icon']) ?>"></i></span>
            <h3 class="font-semibold text-ink"><?= e($s['title']) ?></h3>
          </div>
          <div class="flex flex-wrap gap-2">
            <?php foreach ($s['items'] as $item): ?><span class="tag"><?= e($item) ?></span><?php endforeach; ?>
          </div>
        </div>
      <?php endforeach; ?>
      <a href="<?= url('/resume') ?>" class="bento-cell bento-cta reveal">
        <i class="fas fa-file-lines text-2xl"></i>
        <span class="font-serif text-2xl font-semibold mt-auto">Read my resume</span>
        <span class="text-sm opacity-80">Experience, skills and education on one page <i class="fas fa-arrow-right text-xs ml-1"></i></span>
      </a>
      <a href="<?= e($p['resume_url']) ?>" target="_blank" rel="noopener" class="bento-cell bento-cta reveal">
        <i class="fas fa-user-tie text-2xl"></i>
        <span class="font-serif text-2xl font-semibold mt-auto">Dev profile</span>
        <span class="text-sm opacity-80">My full developer profile <i class="fas fa-arrow-up-right-from-square text-xs ml-1"></i></span>
      </a>
    </div>
  </div>
</section>

<!-- Blogs -->
<section id="blogs" class="section">
  <div class="max-w-6xl mx-auto px-4 sm:px-6">
    <?= view('partials/section-title', ['eyebrow' => 'Writing', 'title' => 'Latest Blogs', 'sub' => 'Notes on work, AI and travel.']) ?>
    <?php if ($featured): ?>
      <a href="<?= url('/blogs/' . $featured['slug']) ?>" class="featured-post reveal">
        <img src="<?= e(url($featured['cover'])) ?>" alt="" loading="lazy">
        <div class="featured-body">
          <span class="badge-new">Latest</span>
          <p class="text-xs uppercase tracking-widest text-white/70 mt-4"><?= e($featured['category']) ?> · <?= e(date('M j, Y', strtotime($featured['date']))) ?></p>
          <h3 class="font-serif text-2xl sm:text-3xl font-semibold mt-2 leading-tight"><?= e($featured['title']) ?></h3>
          <p class="text-white/75 text-sm mt-3 line-clamp-2 max-w-2xl"><?= e($featured['description']) ?></p>
        </div>
      </a>
    <?php endif; ?>
    <div class="grid md:grid-cols-3 gap-6 mt-6">
      <?php foreach ($blogs as $blog): ?><?= view('partials/blog-card', ['blog' => $blog]) ?><?php endforeach; ?>
    </div>
    <div class="text-center mt-10"><a href="<?= url('/blogs') ?>" class="btn btn-outline">See all blogs</a></div>
  </div>
</section>

<!-- Lightbox -->
<div id="lightbox" class="lightbox" hidden>
  <button type="button" class="lightbox-close" aria-label="Close"><i class="fas fa-xmark"></i></button>
  <img src="" alt="">
</div>
