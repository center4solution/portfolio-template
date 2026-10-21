# Portfolio template — v3 "The Logbook"

The same PHP engine as v2 (router, `app/data`, blog system, secure contact form) with a print-magazine design:

- **Cover:** an issue bar, the name set in huge type and numbered cover lines that link to each chapter.
- **Chapter I – The Engineer:** a two-column essay with a drop cap, a pull quote, a taped photo and lined "field notes".
- **Chapter II – Boarding passes:** each timeline entry is a ticket with a route, year, seat and barcode.
- **Chapter III – The passport:** every place in `places.php` becomes an ink stamp, grouped by region, plus the map and vlog reels.
- **Chapter IV – The stack:** a terminal that types `whoami --stack` and prints the skills.
- **Contents:** blogs listed like a table of contents, with a cover preview that follows the cursor.
- **Postcard:** the contact form is a postcard.
- **Paper / Night theme:** follows the system setting, with a toggle in the header that is remembered.

No Tailwind: all styling lives in `assets/css/app.css`, built on CSS variables.
Setup, routes and content editing are the same as in [v2's README](../2/README.md).
