<?php
$urls = [
    ['loc' => SITE_URL . '/', 'priority' => '1.0', 'freq' => 'weekly'],
    ['loc' => SITE_URL . '/blogs', 'priority' => '0.9', 'freq' => 'weekly'],
    ['loc' => SITE_URL . '/resume', 'priority' => '0.6', 'freq' => 'monthly'],
    ['loc' => SITE_URL . '/privacy-policy', 'priority' => '0.3', 'freq' => 'yearly'],
];
foreach (published_blogs() as $b) {
    $urls[] = ['loc' => SITE_URL . '/blogs/' . $b['slug'], 'priority' => '0.8', 'freq' => 'monthly', 'lastmod' => $b['date']];
}
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php foreach ($urls as $u): ?>
  <url>
    <loc><?= e($u['loc']) ?></loc>
<?php if (!empty($u['lastmod'])): ?>    <lastmod><?= e($u['lastmod']) ?></lastmod>
<?php endif; ?>    <changefreq><?= $u['freq'] ?></changefreq>
    <priority><?= $u['priority'] ?></priority>
  </url>
<?php endforeach; ?>
</urlset>
