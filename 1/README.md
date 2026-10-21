# Portfolio template — v1 "Classic Portfolio"

The original site design: Tailwind (CDN) and a Leaflet travel map.
It runs on the same shared router as v2–v5; code, data, blog content and images live in `../shared`.

## Structure

```
1/
├── .htaccess              # routes everything that isn't a real file to index.php
├── index.php              # front controller → ../shared/app/router.php
├── app/
│   ├── data/profile.php   # v1's own trait icons (overrides the shared highlights)
│   └── views/
│       ├── layout.php     # page shell + header show/hide script
│       ├── home/          # index.php + hero, about, journey, travel-map, vlogs, skills, blogs
│       ├── pages/         # blogs, blog, resume, privacy, 404
│       └── partials/      # head, header, footer (contact form → shared POST /contact)
└── assets/css/app.css
```

## Notes

- Old v1 URLs keep working through the shared router: `/imgs/*` is still served,
  `/blogs/all` and `/pages/*.php` redirect to the new paths.
- The contact form reads `TELEGRAM_BOT_TOKEN` and `TELEGRAM_CHAT_ID` from `../shared/.env` (git-ignored).

## Adding a blog post

1. Add an entry to `../shared/app/data/blogs.php`.
2. Put the body in `../shared/content/blogs/{slug}.html`.
3. Put the cover image in `../shared/assets/imgs/`.
