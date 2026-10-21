<?php
$seo = $seo ?? [];
$profile = data('profile');
$metaTitle = $seo['title'] ?? OWNER_NAME . ' | Developer, Photographer & Explorer';
$metaDesc  = $seo['description'] ?? "Explore " . OWNER_NAME . "'s journey as a Full Stack Developer, Photographer, and Traveler — projects, blogs, vlogs and creative work.";
$metaImage = $seo['image'] ?? SITE_URL . '/assets/imgs/banner.png';
$metaUrl   = $seo['url'] ?? SITE_URL . request_path();
$ogType    = $seo['type'] ?? 'website';
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($metaTitle) ?></title>
  <meta name="description" content="<?= e($metaDesc) ?>">
  <?php if (!empty($seo['keywords'])): ?><meta name="keywords" content="<?= e($seo['keywords']) ?>"><?php endif; ?>
  <link rel="canonical" href="<?= e($metaUrl) ?>">
  <meta name="theme-color" content="#0b1220">

  <meta property="og:type" content="<?= e($ogType) ?>">
  <meta property="og:site_name" content="<?= e(SITE_NAME) ?>">
  <meta property="og:title" content="<?= e($metaTitle) ?>">
  <meta property="og:description" content="<?= e($metaDesc) ?>">
  <meta property="og:image" content="<?= e($metaImage) ?>">
  <meta property="og:image:width" content="1200">
  <meta property="og:image:height" content="630">
  <meta property="og:url" content="<?= e($metaUrl) ?>">
  <meta property="og:locale" content="en_US">
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="<?= e($metaTitle) ?>">
  <meta name="twitter:description" content="<?= e($metaDesc) ?>">
  <meta name="twitter:image" content="<?= e($metaImage) ?>">

  <link rel="icon" href="<?= asset('imgs/fav.jpg') ?>" type="image/jpeg">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,600;1,500&family=Caveat:wght@500&display=swap">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <script src="https://cdn.tailwindcss.com/3.4.5"></script>
  <script>
    tailwind.config = { theme: { extend: {
      fontFamily: { sans: ['Inter', 'system-ui', 'sans-serif'], serif: ['"Playfair Display"', 'Georgia', 'serif'] },
      colors: { ink: '#0b1220', brand: { DEFAULT: '#10b981', dark: '#059669' } }
    } } };
  </script>
  <link rel="stylesheet" href="<?= asset('css/app.css') ?>">

  <?= schema_site() ?>
  <?= $headExtra ?? '' ?>

  <?php if (GA_ID && IS_PROD): ?>
  <script async src="https://www.googletagmanager.com/gtag/js?id=<?= e(GA_ID) ?>"></script>
  <script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','<?= e(GA_ID) ?>');</script>
  <?php endif; ?>
</head>
<body class="<?= e($bodyClass ?? '') ?> font-sans text-slate-800 bg-white antialiased">
  <a href="#main" class="sr-only focus:not-sr-only focus:fixed focus:top-2 focus:left-2 focus:z-[100] bg-white px-3 py-2 rounded">Skip to content</a>
  <?= view('partials/header') ?>
  <main id="main"><?= $pageContent ?></main>
  <?= view('partials/footer') ?>
  <script src="<?= asset('js/app.js') ?>" defer></script>
</body>
</html>
