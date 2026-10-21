<?php
$socials = data('socials');
$ok = flash('contact_ok');
$error = flash('contact_error');
?>
<section id="contact" class="sec">
  <div class="wrap">
    <?= view('partials/section-title', ['num' => '06', 'eyebrow' => 'Open channel', 'title' => 'Send a transmission', 'sub' => 'Messages go straight to my phone. I usually reply within a day.']) ?>
    <div class="contact-grid">
      <form action="<?= url('/contact') ?>" method="POST" class="hud console reveal" novalidate>
        <div class="console-bar"><span class="led"></span> uplink://<?= e(strtolower(OWNER_FIRST_NAME)) ?> <span class="muted">— secure</span></div>
        <?php if ($ok): ?><div class="alert alert-ok" role="status">✔ <?= e($ok) ?></div><?php endif; ?>
        <?php if ($error): ?><div class="alert alert-error" role="alert">✖ <?= e($error) ?></div><?php endif; ?>
        <input type="hidden" name="_token" value="<?= e(csrf_token()) ?>">
        <div class="hp" aria-hidden="true"><label>Website <input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>
        <label class="cfield"><span>&gt; name<b>*</b></span><input type="text" name="name" required maxlength="100" autocomplete="name"></label>
        <label class="cfield"><span>&gt; email<b>*</b></span><input type="email" name="email" required autocomplete="email"></label>
        <label class="cfield"><span>&gt; phone</span><input type="tel" name="phone" maxlength="30" autocomplete="tel" placeholder="optional"></label>
        <label class="cfield"><span>&gt; message<b>*</b></span><textarea name="message" rows="5" required maxlength="4000"></textarea></label>
        <div class="console-foot">
          <a href="<?= url('/privacy-policy') ?>" class="muted mono small">privacy.txt</a>
          <button type="submit" class="btn btn-primary"><i class="fas fa-satellite-dish"></i> Transmit</button>
        </div>
      </form>

      <div class="signals reveal">
        <?php foreach ($socials as $group => $links): ?>
          <div class="hud signal-group">
            <p class="kicker"><?= e($group) ?> <span class="muted">[<?= count($links) ?>]</span></p>
            <div class="signal-chips">
              <?php foreach ($links as [$label, $icon, $href]): ?>
                <a href="<?= e($href) ?>" target="_blank" rel="noopener" class="signal" aria-label="<?= e($group . ' — ' . $label) ?>" title="<?= e($label) ?>"><i class="<?= e($icon) ?>"></i></a>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<footer class="sysbar">
  <div class="wrap sysbar-inner mono">
    <span><span class="led"></span> All systems nominal</span>
    <span>© <?= date('Y') ?> <?= e(SITE_NAME) ?> · <?= e(OWNER_INITIALS) ?>//OS</span>
    <span class="muted">Powered by chai ☕</span>
  </div>
</footer>
