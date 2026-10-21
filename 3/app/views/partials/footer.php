<?php
$socials = data('socials');
$ok = flash('contact_ok');
$error = flash('contact_error');
?>
<section id="contact" class="chapter">
  <div class="wrap">
    <?= view('partials/section-title', ['eyebrow' => 'Last page', 'title' => 'Send me a postcard', 'sub' => 'Work, a collab, a travel tip or just hello. It reaches my phone directly.']) ?>

    <form action="<?= url('/contact') ?>" method="POST" class="postcard reveal" novalidate>
      <div class="postcard-left">
        <?php if ($ok): ?><div class="alert alert-ok" role="status"><?= e($ok) ?></div><?php endif; ?>
        <?php if ($error): ?><div class="alert alert-error" role="alert"><?= e($error) ?></div><?php endif; ?>
        <input type="hidden" name="_token" value="<?= e(csrf_token()) ?>">
        <div class="hp" aria-hidden="true"><label>Website <input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>
        <label class="ink-field"><span>Dear <?= e(OWNER_FIRST_NAME) ?>,</span>
          <textarea name="message" rows="7" required maxlength="4000" placeholder="Write your message here…"></textarea>
        </label>
      </div>
      <div class="postcard-right">
        <div class="stamp-slot" aria-hidden="true">
          <span class="stamp-art"><i class="fas fa-paper-plane"></i><small><?= e(OWNER_INITIALS) ?> · <?= date('Y') ?></small></span>
        </div>
        <span class="postmark" aria-hidden="true">SENT<br>WITH ♥</span>
        <label class="line-field"><span>From</span><input type="text" name="name" required maxlength="100" autocomplete="name" placeholder="Your name"></label>
        <label class="line-field"><span>Email</span><input type="email" name="email" required autocomplete="email" placeholder="you@example.com"></label>
        <label class="line-field"><span>Phone</span><input type="tel" name="phone" maxlength="30" autocomplete="tel" placeholder="optional"></label>
        <button type="submit" class="btn btn-primary w-full">Post it <i class="fas fa-arrow-right"></i></button>
        <a href="<?= url('/privacy-policy') ?>" class="fine-print">Privacy policy</a>
      </div>
    </form>
  </div>
</section>

<footer class="colophon">
  <div class="wrap colophon-grid">
    <div>
      <p class="bar-logo">The&nbsp;<?= e(OWNER_INITIALS) ?>&nbsp;<em>Logbook</em></p>
      <p class="muted mt-2">Hand-made, fuelled by chai ☕. Every issue is still being written.</p>
    </div>
    <?php foreach ($socials as $group => $links): ?>
      <div>
        <p class="kicker"><?= e($group) ?></p>
        <ul class="colophon-links">
          <?php foreach ($links as [$label, $icon, $href]): ?>
            <li><a href="<?= e($href) ?>" target="_blank" rel="noopener"><i class="<?= e($icon) ?>"></i> <?= e($label) ?></a></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endforeach; ?>
  </div>
  <p class="wrap colophon-base">© <?= date('Y') ?> <?= e(SITE_NAME) ?> · <span class="mono">&lt;/crafted&gt;</span></p>
</footer>
