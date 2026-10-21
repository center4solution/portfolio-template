<?php
$seo = $seo ?? [];
$profile = data('profile');
$metaTitle = $seo['title'] ?? OWNER_INITIALS . '//OS | ' . OWNER_NAME . ' — Engineer, Photographer & Explorer';
$metaDesc  = $seo['description'] ?? "Boot into " . OWNER_NAME . "'s personal OS: full-stack engineering, photography, travel vlogs and writing.";
$metaImage = $seo['image'] ?? SITE_URL . '/assets/imgs/banner.png';
$metaUrl   = $seo['url'] ?? SITE_URL . request_path();
$ogType    = $seo['type'] ?? 'website';
$palette = [
    ['Home', url('/'), 'fa-house'],
    ['Identity', url('/#identity'), 'fa-fingerprint'],
    ['Mission log', url('/#log'), 'fa-satellite'],
    ['Globe', url('/#globe'), 'fa-earth-asia'],
    ['Transmissions (vlogs)', url('/#vlogs'), 'fa-video'],
    ['Systems (skills)', url('/#systems'), 'fa-microchip'],
    ['Archive (all blogs)', url('/blogs'), 'fa-database'],
    ['Resume', url('/resume'), 'fa-id-card'],
    ['Open channel (contact)', url('/#contact'), 'fa-tower-broadcast'],
    ['YouTube channel', $profile['youtube'], 'fa-youtube'],
];
foreach (published_blogs() as $b) $palette[] = ['Read: ' . $b['title'], url('/blogs/' . $b['slug']), 'fa-file-lines'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($metaTitle) ?></title>
  <meta name="description" content="<?= e($metaDesc) ?>">
  <?php if (!empty($seo['keywords'])): ?><meta name="keywords" content="<?= e($seo['keywords']) ?>"><?php endif; ?>
  <link rel="canonical" href="<?= e($metaUrl) ?>">
  <meta name="theme-color" content="#05060f">

  <meta property="og:type" content="<?= e($ogType) ?>">
  <meta property="og:site_name" content="<?= e(SITE_NAME) ?>">
  <meta property="og:title" content="<?= e($metaTitle) ?>">
  <meta property="og:description" content="<?= e($metaDesc) ?>">
  <meta property="og:image" content="<?= e($metaImage) ?>">
  <meta property="og:image:width" content="1200">
  <meta property="og:image:height" content="630">
  <meta property="og:url" content="<?= e($metaUrl) ?>">
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="<?= e($metaTitle) ?>">
  <meta name="twitter:description" content="<?= e($metaDesc) ?>">
  <meta name="twitter:image" content="<?= e($metaImage) ?>">

  <link rel="icon" href="<?= asset('imgs/fav.jpg') ?>" type="image/jpeg">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;700;900&family=Sora:wght@300;400;600;700&family=JetBrains+Mono:wght@400;600&display=swap">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="<?= asset('css/app.css') ?>">

  <?= schema_site() ?>

  <?php if (GA_ID && IS_PROD): ?>
  <script async src="https://www.googletagmanager.com/gtag/js?id=<?= e(GA_ID) ?>"></script>
  <script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','<?= e(GA_ID) ?>');</script>
  <?php endif; ?>
</head>
<body class="<?= e($bodyClass ?? '') ?>">
  <a href="#main" class="skip">Skip to content</a>
  <canvas id="starfield" aria-hidden="true"></canvas>
  <div class="scanlines" aria-hidden="true"></div>
  <div class="cursor-glow" aria-hidden="true"></div>

  <?php if (($bodyClass ?? '') === 'home-page'): ?>
  <div id="boot" class="boot" aria-hidden="true">
    <pre class="boot-log"></pre>
  </div>
  <?php endif; ?>

  <?= view('partials/header') ?>
  <main id="main"><?= $pageContent ?></main>
  <?= view('partials/footer') ?>

  <div id="palette" class="palette" hidden role="dialog" aria-modal="true" aria-label="Command palette">
    <div class="palette-box hud">
      <div class="palette-input"><i class="fas fa-terminal"></i><input type="text" placeholder="Type a command or search…" aria-label="Search commands" autocomplete="off"><kbd>esc</kbd></div>
      <ul class="palette-list" role="listbox">
        <?php foreach ($palette as [$label, $href, $icon]): ?>
          <li><a href="<?= e($href) ?>" role="option" <?= str_starts_with($href, 'http') ? 'target="_blank" rel="noopener"' : '' ?>>
            <i class="<?= str_contains($icon, 'youtube') ? 'fab' : 'fas' ?> <?= e($icon) ?>"></i><span><?= e($label) ?></span><i class="fas fa-arrow-turn-down fa-rotate-90 enter"></i>
          </a></li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>

  <script src="<?= asset('js/app.js') ?>" defer></script>
</body>
</html>
