<section class="sec page-top">
  <div class="story-body prose">
    <p class="kicker">Fine print</p>
    <h1 class="story-title">Privacy Policy</h1>
    <p class="mono muted">Last updated: <?= e(date('F j, Y', filemtime(__FILE__))) ?></p>

    <p>Thank you for visiting <strong><?= e(SITE_DOMAIN) ?></strong>, a personal website run by <?= e(OWNER_NAME) ?> to share stories, experiences and creative work.</p>

    <h2>1. Information collected</h2>
    <p>Personal data is collected only when you send it through the contact form: your name, email address, phone number (optional) and message. It is forwarded privately to me and used only to reply to you.</p>

    <h2>2. Analytics &amp; cookies</h2>
    <p>This site uses Google Analytics to understand anonymous, aggregate traffic (pages visited, device type, approximate region). Google may set cookies for this. A session cookie is also used to protect the contact form from abuse, and your theme choice is remembered in your browser. You can block cookies in your browser settings.</p>

    <h2>3. Embedded content</h2>
    <p>Videos are loaded from YouTube only when you press play, and the star map is drawn locally with no third-party map service. These services have their own privacy policies.</p>

    <h2>4. Data sharing</h2>
    <p>Your personal data is never sold or shared with third parties.</p>

    <h2>5. Contact</h2>
    <p>Questions? Use the contact form or email <a href="mailto:<?= e(data('profile')['email']) ?>"><?= e(data('profile')['email']) ?></a>.</p>
  </div>
</section>
