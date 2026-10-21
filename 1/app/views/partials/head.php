<?php
$seo = $seo ?? [];
$metaTitle = $seo['title'] ?? OWNER_NAME . ' | Developer, Photographer & Explorer';
$metaDescription = $seo['description'] ?? "Explore " . OWNER_NAME . "'s journey as a Full Stack Developer, Photographer, and Traveler. Dive into his projects, blogs, and creative work.";
$metaImage = $seo['image'] ?? SITE_URL . '/assets/imgs/banner.png'; // must be an absolute URL
$metaUrl = $seo['url'] ?? SITE_URL . request_path();
$metaKeywords = $seo['keywords'] ?? SITE_NAME . ', ' . OWNER_INITIALS . ', full stack developer, portfolio website, personal portfolio, travel photographer, software developer India, React developer, FastAPI developer, web developer portfolio, frontend developer, backend developer, photography blog, Indian traveler, tech blogs, travel blogs, developer photographer, coding and travel, full stack projects, personal journey, creative technologist';
$ogType = $seo['type'] ?? 'website';
$imageTypes = ['jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png', 'gif' => 'image/gif', 'webp' => 'image/webp'];
$imageType = $imageTypes[strtolower(pathinfo((string) parse_url($metaImage, PHP_URL_PATH), PATHINFO_EXTENSION))] ?? 'image/png';
?>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<!-- Essential Meta -->
<title><?= e($metaTitle) ?></title>

<link rel="canonical" href="<?= e($metaUrl) ?>">

<!-- SEO Meta -->
<meta name="description" content="<?= e($metaDescription) ?>"/>
<meta name="keywords" content="<?= e($metaKeywords) ?>">

<!-- Open Graph / Facebook -->
<meta property="og:type" content="<?= e($ogType) ?>">
<meta property="og:site_name" content="<?= e(SITE_NAME) ?>">
<meta property="og:title" content="<?= e($metaTitle) ?>" />
<meta property="og:description" content="<?= e($metaDescription) ?>" />
<meta property="og:image" content="<?= e($metaImage) ?>" />
<meta property="og:image:secure_url" content="<?= e($metaImage) ?>" />
<meta property="og:url" content="<?= e($metaUrl) ?>" />
<meta property="og:image:width" content="1200" />
<meta property="og:image:height" content="630" />
<meta property="og:image:type" content="<?= e($imageType) ?>" />
<meta property="og:image:alt" content="<?= e($metaTitle) ?>" />
<meta property="og:locale" content="en_US" />

<!-- Twitter -->
<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:title" content="<?= e($metaTitle) ?>" />
<meta name="twitter:description" content="<?= e($metaDescription) ?>" />
<meta name="twitter:image" content="<?= e($metaImage) ?>" />
<meta name="twitter:url" content="<?= e($metaUrl) ?>">

<!-- Favicon & Fonts -->
<link rel="icon" href="<?= asset('imgs/fav.jpg') ?>" type="image/jpeg" />

<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="<?= asset('css/app.css') ?>" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script defer src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<?php if (GA_ID && IS_PROD): ?>
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=<?= e(GA_ID) ?>"></script>
<script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','<?= e(GA_ID) ?>');</script>
<?php endif; ?>

<?= schema_site() ?>
