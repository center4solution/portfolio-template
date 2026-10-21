<?php
$seo = $seo ?? [];
$profile = data('profile');
$metaTitle = $seo['title'] ?? 'The ' . OWNER_INITIALS . ' Logbook | ' . OWNER_NAME . ' — Developer, Photographer & Explorer';
$metaDesc  = $seo['description'] ?? OWNER_NAME . "'s logbook: full-stack engineering, photography, travel vlogs and writing.";
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

  <script>
    // Apply the saved theme before paint to avoid a flash
    try { const t = localStorage.getItem('brs-theme'); if (t) document.documentElement.dataset.theme = t; } catch (_) {}
  </script>
  <link rel="icon" href="<?= asset('imgs/fav.jpg') ?>" type="image/jpeg">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,600;0,9..144,800;1,9..144,400&family=Space+Grotesk:wght@400;500;700&family=JetBrains+Mono:wght@400;600&family=Caveat:wght@600&display=swap">
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
  <div class="grain" aria-hidden="true"></div>
  <?= view('partials/header') ?>
  <main id="main"><?= $pageContent ?></main>
  <?= view('partials/footer') ?>
  <script src="<?= asset('js/app.js') ?>" defer></script>
</body>
</html>
