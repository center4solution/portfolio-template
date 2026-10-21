# Portfolio template — v2

A rebuild of the portfolio in plain PHP 8.1 with no framework. It runs on the same cPanel hosting as v1.

## Structure

```
2/
├── index.php          # Front controller and router (all URLs come here)
├── .htaccess          # Sends requests to index.php, blocks app/ content/ storage/
├── .env.example       # Copy to .env (Telegram token, GA id, SITE_URL)
├── app/
│   ├── bootstrap.php  # env loader, helpers: e(), url(), asset(), view(), csrf
│   ├── blogs.php      # blog queries: published, find, related, reading time
│   ├── contact.php    # POST /contact: CSRF, honeypot, rate limit, Telegram
│   ├── data/          # all site content as PHP arrays (edit these)
│   └── views/         # layout, partials, home, pages, sitemap
├── content/blogs/     # blog bodies, one {slug}.html per post
├── assets/            # css, js, imgs
└── storage/           # rate-limit file (not web accessible)
```

## Routes

| URL | Page |
|-----|------|
| `/` | Home |
| `/blogs` | All blogs (`/blogs/all` redirects here) |
| `/blogs/{slug}` | Single post |
| `/resume`, `/privacy-policy` | Pages |
| `/sitemap.xml`, `/robots.txt` | Generated automatically |

## Common tasks

- **Add a blog post:** add an entry to `app/data/blogs.php`, put the body in `content/blogs/{slug}.html` and the cover image in `assets/imgs/`. Drafts and posts with a future date stay hidden, and the sitemap updates by itself.
- **Add a map pin:** add a row to `app/data/places.php`.
- **Edit text, skills, timeline, socials or vlogs:** change the matching file in `app/data/`.

## Local setup

1. `cp .env.example .env` and fill in `TELEGRAM_BOT_TOKEN` and `TELEGRAM_CHAT_ID`. Set `APP_ENV=local` to turn off Google Analytics and show errors.
2. Open `http://localhost/portfolio-template/2/`. The site works in a subfolder or at the domain root.

## Deploying

Point the domain's document root, or the CI rsync source, at this folder and create `.env` on the server with `APP_ENV=production`.
