<?php
// Shared router: each version's index.php defines VERSION_DIR and requires this.
require __DIR__ . '/bootstrap.php';

$path = request_path();

// Images are shared by all versions, so they're served from ../shared/assets/imgs
if (str_starts_with($path, '/assets/imgs/')) {
    serve_shared_image(substr($path, strlen('/assets/imgs/')));
}
// Old v1 image URLs (/imgs/*) are still linked from shared posts and cached previews
if (str_starts_with($path, '/imgs/')) {
    serve_shared_image(substr($path, strlen('/imgs/')));
}

// Old v1 page URLs
$legacy = ['/pages/resume.php' => '/resume', '/pages/privacy-policy.php' => '/privacy-policy'];
if (isset($legacy[$path])) redirect($legacy[$path], 301);

// Redirect trailing slashes (except root) to the canonical URL
if ($path !== '/' && str_ends_with($path, '/')) {
    redirect(rtrim($path, '/'), 301);
}

switch (true) {
    case $path === '/':
        render('home/index', ['bodyClass' => 'home-page']);
        break;

    case $path === '/blogs' || $path === '/blogs/all':
        if ($path === '/blogs/all') redirect('/blogs', 301);
        render('pages/blogs', [
            'blogs' => published_blogs(),
            'seo'   => ['title' => 'All Blogs | ' . OWNER_NAME, 'url' => SITE_URL . '/blogs'],
        ]);
        break;

    case (bool) preg_match('#^/blogs/([a-z0-9-]+)$#', $path, $m):
        $blog = find_blog($m[1]);
        if (!$blog) not_found();
        render('pages/blog', [
            'blog'    => $blog,
            'content' => blog_content($blog),
            'related' => related_blogs($blog),
            'seo'     => [
                'title'       => $blog['title'],
                'description' => $blog['description'],
                'keywords'    => implode(',', $blog['tags']),
                'image'       => SITE_URL . '/' . $blog['cover'],
                'url'         => SITE_URL . '/blogs/' . $blog['slug'],
                'type'        => 'article',
            ],
        ]);
        break;

    case $path === '/resume':
        render('pages/resume', ['seo' => ['title' => 'Resume | ' . SITE_NAME, 'url' => SITE_URL . '/resume']]);
        break;

    case $path === '/privacy-policy':
        render('pages/privacy', ['seo' => ['title' => 'Privacy Policy | ' . OWNER_NAME, 'url' => SITE_URL . '/privacy-policy']]);
        break;

    case $path === '/contact' && $_SERVER['REQUEST_METHOD'] === 'POST':
        require APP_DIR . '/contact.php';
        handle_contact();
        break;

    case $path === '/sitemap.xml':
        header('Content-Type: application/xml; charset=UTF-8');
        require APP_DIR . '/views/sitemap.php';
        break;

    case $path === '/robots.txt':
        header('Content-Type: text/plain; charset=UTF-8');
        // Explicitly welcome AI search/answer crawlers so posts can be cited (GEO/AEO)
        $bots = ['GPTBot', 'OAI-SearchBot', 'ChatGPT-User', 'ClaudeBot', 'Claude-SearchBot', 'PerplexityBot', 'Google-Extended', 'Applebot-Extended', 'Bingbot'];
        foreach ($bots as $bot) echo "User-agent: {$bot}\nAllow: /\n\n";
        echo "User-agent: *\nAllow: /\n\nSitemap: " . SITE_URL . "/sitemap.xml\n";
        break;

    case $path === '/llms.txt':
        header('Content-Type: text/plain; charset=UTF-8');
        echo llms_txt();
        break;

    default:
        not_found();
}
