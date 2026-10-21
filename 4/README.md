# Portfolio template — v4 "DEV//OS"

A futuristic, operating-system-style design built on the same PHP engine as v2 and v3 (router, `app/data`, blogs, secure contact form).

- **Boot sequence:** a short terminal boot on the first homepage visit of each session. Click to skip; it never shows when reduced motion is on.
- **Live starfield:** a canvas background that reacts to scroll speed, plus scanlines and a glow that follows the cursor.
- **Hero:** your name decodes from random characters, with an animated photo orb, orbit rings, satellites, a live IST clock and stat counters.
- **Command palette:** <kbd>Ctrl</kbd>/<kbd>⌘</kbd> + <kbd>K</kbd> searches every section and blog post, with arrow-key navigation.
- **Identity:** bento-style glass cards with a mouse spotlight.
- **Mission log:** a horizontally scrolling timeline (drag, mouse-wheel or swipe).
- **3D globe:** an orthographic projection drawn on canvas with no map library. Drag to rotate; hover a place in the list to fly to it; click a pink (vlog) pin to watch.
- **Systems:** skills orbiting a core, plus tabbed modules with loading bars.
- **Contact:** a "transmission" console form.

All styling is in `assets/css/app.css`, and the interactive code is in `assets/js/app.js`. There are no build tools and no frameworks.
Setup, routes and content editing are the same as in [v2's README](../2/README.md).
