<?php
// Blog repository: metadata lives in data/blogs.php, bodies in content/blogs/*.html

function all_blogs(): array
{
    $blogs = data('blogs');
    usort($blogs, fn($a, $b) => strcmp($b['date'], $a['date']));
    return $blogs;
}

function published_blogs(?int $limit = null): array
{
    $blogs = array_values(array_filter(
        all_blogs(),
        fn($b) => $b['status'] === 'published' && $b['date'] <= date('Y-m-d')
    ));
    return $limit ? array_slice($blogs, 0, $limit) : $blogs;
}

function find_blog(string $slug): ?array
{
    foreach (published_blogs() as $blog) {
        if ($blog['slug'] === $slug) return $blog;
    }
    return null;
}

function blog_content(array $blog): string
{
    $file = CONTENT_DIR . '/blogs/' . $blog['slug'] . '.html';
    return is_file($file) ? file_get_contents($file) : '<p>Content not available.</p>';
}

function reading_time(string $html): int
{
    return max(1, (int) ceil(str_word_count(strip_tags($html)) / 220));
}

require_once __DIR__ . '/related.php';

function related_blogs(array $blog, int $limit = 3): array
{
    return pick_related_posts($blog, all_blogs(), $limit);
}
