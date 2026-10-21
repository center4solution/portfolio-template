# Portfolio template — v6 "Chain"

A light, blockchain-explorer design (white panels, emerald and amber accents, a dark ticker bar) built on the same shared PHP engine as v1–v5 (router, `../shared/app/data`, blogs, secure contact form).

- **Ticker:** every skill scrolls along the top as a token with a balance and a 24h move.
- **Overview:** a wallet-style account card (photo, verified name, copyable address) and network stats. The search box (press `/`) highlights matching blocks, tokens, nodes and posts on the page.
- **Blocks:** each journey entry is a block with a height, hash and the previous block's hash, newest first, plus a "mining…" block for what's next.
- **Wallet:** skills grouped by chain as token cards, with an allocation donut.
- **Nodes:** every place in `places.php` is a node on an SVG network map, linked to its two nearest neighbours. Gold nodes have a vlog; hovering the map or the list highlights both. Vlogs are "media contracts".
- **Transactions:** blogs as a transaction feed (home shows the latest 6; `/blogs` shows all with method filters). A single post opens as "transaction details".
- **Send:** the contact form is a transaction with a wallet-style confirm step before it is submitted.

Hashes, balances, price moves and pings are made up but stable: `app/chain.php` derives them from the real content, so they don't change between page loads.

All styling is in `assets/css/app.css`, and the interactive code is in `assets/js/app.js`. There are no build tools and no frameworks.
