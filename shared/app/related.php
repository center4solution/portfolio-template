<?php
// "More posts" picker shared by every version (v1 includes this file directly).
// Scores each other published post: +1 per shared tag, +2 for the same category.
// Best matches come first; empty slots are filled with the newest posts.
if (!function_exists('pick_related_posts')) {
    function pick_related_posts(array $current, array $posts, int $limit = 3): array
    {
        $today = date('Y-m-d');
        $tags = array_map('strtolower', $current['tags'] ?? []);
        $candidates = [];
        foreach ($posts as $post) {
            if (($post['status'] ?? '') !== 'published' || ($post['date'] ?? '') > $today) continue;
            if ($post['slug'] === $current['slug']) continue;
            $score = count(array_intersect($tags, array_map('strtolower', $post['tags'] ?? [])))
                   + (($post['category'] ?? '') === ($current['category'] ?? null) ? 2 : 0);
            $candidates[] = [$score, $post['date'] ?? '', $post];
        }
        // Highest score first, then newest first (also the order of the fill-ins)
        usort($candidates, fn($a, $b) => [$b[0], $b[1]] <=> [$a[0], $a[1]]);
        return array_slice(array_column($candidates, 2), 0, $limit);
    }
}
