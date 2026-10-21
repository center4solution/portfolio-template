<?php
// Blog metadata. The body of each post is content/blogs/{slug}.html
// status: 'published' | 'draft'. Posts with a future date stay hidden until that day.
return [
    [
        'slug' => 'hello-world',
        'title' => 'Hello World: Your First Post',
        'cover' => 'assets/imgs/hello-world.png',
        'date' => '2025-01-01',
        'category' => 'Getting Started',
        'description' => 'A sample post that shows how blog posts work in this template. Replace it with your own writing.',
        'tags' => ['sample', 'getting-started'],
        'status' => 'published',
    ],
];
