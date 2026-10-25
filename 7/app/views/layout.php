<?php
require_once VERSION_DIR . '/app/orbit.php';
$seo = $seo ?? [];
$metaTitle = $seo['title'] ?? OWNER_INITIALS . ' Orbit | ' . OWNER_NAME . ' — Engineer, Photographer & Explorer';
$metaDesc  = $seo['description'] ?? "Follow " . OWNER_NAME . "'s mission log: career launches, skills in orbit, a star map of travels and transmissions from the road.";
$metaImage = $seo['image'] ?? SITE_URL . '/assets/imgs/banner.png';
$metaUrl   = $seo['url'] ?? SITE_URL . request_path();
$ogType    = $seo['type'] ?? 'website';
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
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;700;900&family=Exo+2:wght@300;400;600&family=Space+Mono:wght@400;700&display=swap">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="<?= asset('css/app.css') ?>">

  <?= schema_site() ?>

  <?php if (GA_ID && IS_PROD): ?>
  <script async src="https://www.googletagmanager.com/gtag/js?id=<?= e(GA_ID) ?>"></script>
  <script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','<?= e(GA_ID) ?>');</script>
  <?php endif; ?>
</head>
<body class="<?= e($bodyClass ?? '') ?>">
  <canvas id="space" aria-hidden="true"></canvas>
  <div class="nebula" aria-hidden="true"></div>
  <a href="#main" class="skip">Skip to content</a>
  <?= view('partials/header') ?>
  <main id="main"><?= $pageContent ?></main>
  <?= view('partials/footer') ?>
  <script src="<?= asset('js/app.js') ?>" defer></script>
</body>
</html>
