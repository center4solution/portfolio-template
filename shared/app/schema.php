<?php
// JSON-LD structured data shared by every version (search engines and AI answer engines read it).
// Entities link to each other by @id so the author, site and posts form one graph.

const SCHEMA_PERSON_ID = SITE_URL . '/#person';
const SCHEMA_SITE_ID = SITE_URL . '/#website';

function json_ld(array $data): string
{
    $json = json_encode(['@context' => 'https://schema.org'] + $data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
    return '<script type="application/ld+json">' . $json . '</script>';
}

function schema_person(): array
{
    $profile = data('profile');
    return [
        '@type' => 'Person',
        '@id' => SCHEMA_PERSON_ID,
        'name' => $profile['full_name'],
        'alternateName' => [$profile['name'], $profile['initials']],
        'url' => SITE_URL,
        'image' => SITE_URL . '/' . $profile['photo'],
        'jobTitle' => $profile['job_title'],
        'description' => strip_tags($profile['about'][0] ?? $profile['intro']),
        'email' => 'mailto:' . $profile['email'],
        'knowsAbout' => $profile['knows_about'] ?? [],
        'sameAs' => $profile['same_as'],
    ] + (empty($profile['nationality']) ? [] : ['nationality' => ['@type' => 'Country', 'name' => $profile['nationality']]]);
}

// Person + WebSite, printed once per page from the layout
function schema_site(): string
{
    return json_ld(['@graph' => [
        schema_person(),
        [
            '@type' => 'WebSite',
            '@id' => SCHEMA_SITE_ID,
            'url' => SITE_URL,
            'name' => SITE_NAME,
            'inLanguage' => 'en',
            'publisher' => ['@id' => SCHEMA_PERSON_ID],
        ],
    ]]);
}

// BlogPosting + BreadcrumbList for a single post
function schema_blog(array $blog, string $content = ''): string
{
    $link = SITE_URL . '/blogs/' . $blog['slug'];
    $words = str_word_count(strip_tags($content));
    $post = [
        '@type' => 'BlogPosting',
        '@id' => $link . '#article',
        'headline' => $blog['title'],
        'description' => $blog['description'],
        'image' => SITE_URL . '/' . $blog['cover'],
        'datePublished' => $blog['date'],
        'dateModified' => $blog['updated'] ?? $blog['date'],
        'author' => ['@id' => SCHEMA_PERSON_ID],
        'publisher' => ['@id' => SCHEMA_PERSON_ID],
        'isPartOf' => ['@id' => SCHEMA_SITE_ID],
        'mainEntityOfPage' => $link,
        'articleSection' => $blog['category'],
        'keywords' => implode(', ', $blog['tags']),
        'inLanguage' => 'en',
    ];
    if ($words) $post['wordCount'] = $words;

    $graph = [$post, [
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => SITE_URL . '/'],
            ['@type' => 'ListItem', 'position' => 2, 'name' => 'Blogs', 'item' => SITE_URL . '/blogs'],
            ['@type' => 'ListItem', 'position' => 3, 'name' => $blog['title'], 'item' => $link],
        ],
    ]];

    // Optional 'faq' => [['q' => ..., 'a' => ...]] in blog data becomes an FAQPage (answer engines lift these)
    if (!empty($blog['faq'])) {
        $graph[] = [
            '@type' => 'FAQPage',
            'mainEntity' => array_map(fn($f) => [
                '@type' => 'Question',
                'name' => $f['q'],
                'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f['a']],
            ], $blog['faq']),
        ];
    }
    return json_ld(['@graph' => $graph]);
}

// /llms.txt: a plain-text map of the site for AI assistants and crawlers
function llms_txt(): string
{
    $profile = data('profile');
    $out = "# " . SITE_NAME . "\n\n> " . strip_tags($profile['about'][0] ?? $profile['intro']) . "\n\n";
    $out .= "## Pages\n\n";
    $out .= "- [Home](" . SITE_URL . "/): about, skills, journey, travel and vlogs\n";
    $out .= "- [Blogs](" . SITE_URL . "/blogs): all articles\n";
    $out .= "- [Resume](" . SITE_URL . "/resume): experience and skills\n\n";
    $out .= "## Articles\n\n";
    foreach (published_blogs() as $b) {
        $out .= "- [{$b['title']}](" . SITE_URL . "/blogs/{$b['slug']}): {$b['description']}\n";
    }
    $out .= "\n## Elsewhere\n\n";
    foreach ($profile['same_as'] as $link) $out .= "- {$link}\n";
    return $out;
}
