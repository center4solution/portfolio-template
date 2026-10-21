# Portfolio template — v5 "Transit Map"

A metro-map design built on the same PHP engine as v2–v4 (router, `app/data`, blogs, secure contact form).

- **Header line:** the nav is a coloured line of stations. The station you're at lights up as you scroll, and a rainbow bar shows ride progress.
- **Network map:** the hero has an SVG map where each line runs from "Central" to a terminus. Click a terminus to jump to its section.
- **Origin station:** about text, photo, a yellow "passenger notice" quote and the highlight cards.
- **The route:** the journey timeline is a line with stops (blue study, red work, green travel), and a train moves along it as you scroll.
- **Travel line:** every place in `places.php` is a station on a green line. Places with a ▶ link to their vlog. Vlogs play on "platform screens".
- **Skill lines:** each skill group is a line diagram like the ones above train doors.
- **Departures board:** blogs are listed on a split-flap style board (home shows the latest 5; `/blogs` shows all with platform filters).
- **Ticket counter:** the contact form is a ticket, and social links are "connections".
- **Day / night service:** follows the system setting, with a header toggle that is remembered.

All styling is in `assets/css/app.css`, and the interactive code is in `assets/js/app.js`. There are no build tools and no frameworks.
Setup, routes and content editing are the same as in [v2's README](../2/README.md).
