# Portfolio template — v7 "Orbit"

A deep-space mission-control design (dark sky, sun orange and ice blue) built on the same shared PHP engine as v1–v6 (router, `../shared/app/data`, blogs, secure contact form).

- **Sky:** a canvas starfield with three depth layers that twinkle, drift with scroll and throw the odd shooting star. The top bar shows a ticking mission elapsed time (MET) since 2018.
- **Launch:** the hero is an orrery. The photo is the planet and each skill group is a moon on its own orbit (hover to pause).
- **Crew profile:** about text plus trait cards as mission patches.
- **Mission log:** each journey entry is a planet on its own tilted orbit around an origin star, oldest closest in. A console shows the selected mission file (latest by default), with prev/next buttons and a year strip below. The outermost orbit is the empty "next mission".
- **Satellite tracking:** each skill group is a constellation flying a wavy ground track across a gridded chart, with a radar sweep in the middle. Picking a group lights its track, labels its satellites and fills the tracking table beside it.
- **Landing sites:** a canvas scene: the night side of the Earth hangs from the top and rolls toward you, with every place in `places.php` as a cluster of city lights. Satellites orbit underneath, and a telescope on a rocky ledge (bottom right) follows each place as it crosses a hidden focus line and lights up with its name; when a satellite is there too, a link beam joins them. Gold places have a vlog (click to watch); a screen-reader list carries the same places. The vlog cards sit below under "Travel vlogs".
- **Transmissions:** blogs as received signals, each with a stable radio frequency.
- **Comms:** the contact form runs a 3-2-1 launch countdown before it is submitted.

Blog frequencies and mission numbers are made up but stable: `app/orbit.php` derives them from the real content.

All styling is in `assets/css/app.css`, and the interactive code is in `assets/js/app.js`. There are no build tools and no frameworks. Reduced-motion users get a still sky and no countdown.
