<?php
$socials = data('socials');
$ok = flash('contact_ok');
$error = flash('contact_error');
?>
<section id="comms" class="sec">
  <div class="wrap">
    <?= view('partials/section-title', ['icon' => 'fa-satellite-dish', 'eyebrow' => 'Open comms', 'title' => 'Send a message to the station', 'sub' => 'Messages land straight on my phone. Light lag is low: I usually reply within a day.']) ?>
    <div class="comms-grid">
      <form action="<?= url('/contact') ?>" method="POST" class="panel comms-form reveal" id="comms-form" novalidate>
        <p class="comms-head mono"><span><span class="pulse"></span> Channel open</span><span class="muted">uplink · encrypted</span></p>
        <?php if ($ok): ?><div class="alert alert-ok" role="status"><i class="fas fa-circle-check"></i> <?= e($ok) ?></div><?php endif; ?>
        <?php if ($error): ?><div class="alert alert-error" role="alert"><i class="fas fa-triangle-exclamation"></i> <?= e($error) ?></div><?php endif; ?>
        <input type="hidden" name="_token" value="<?= e(csrf_token()) ?>">
        <div class="hp" aria-hidden="true"><label>Website <input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>
        <div class="fields">
          <label class="field"><span>Call sign (your name)<b>*</b></span><input type="text" name="name" required maxlength="100" autocomplete="name"></label>
          <label class="field"><span>Email<b>*</b></span><input type="email" name="email" required autocomplete="email"></label>
          <label class="field"><span>Phone</span><input type="tel" name="phone" maxlength="30" autocomplete="tel" placeholder="optional"></label>
          <label class="field field-full"><span>Message<b>*</b></span><textarea name="message" rows="5" required maxlength="4000"></textarea></label>
        </div>
        <div class="comms-foot">
          <a href="<?= url('/privacy-policy') ?>" class="muted small">Privacy policy</a>
          <button type="submit" class="btn btn-primary"><i class="fas fa-rocket"></i> Launch message</button>
        </div>
      </form>

      <div class="relays reveal">
        <p class="kicker">Relay stations</p>
        <?php foreach ($socials as $group => $links): ?>
          <div class="panel relay">
            <h3><?= e($group) ?> <span class="mono muted">[<?= count($links) ?>]</span></h3>
            <div class="relay-chips">
              <?php foreach ($links as [$label, $icon, $href]): ?>
                <a href="<?= e($href) ?>" target="_blank" rel="noopener" class="relay-link" aria-label="<?= e($group . ' — ' . $label) ?>" title="<?= e($label) ?>"><i class="<?= e($icon) ?>"></i></a>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<div class="countdown" id="countdown" hidden role="status" aria-live="assertive">
  <p class="kicker mono">Launch sequence</p>
  <p class="count-num" id="count-num">3</p>
  <i class="fas fa-rocket count-rocket" aria-hidden="true"></i>
</div>

<footer class="footer">
  <div class="wrap footer-inner mono">
    <span><span class="pulse"></span> Signal locked</span>
    <span>© <?= date('Y') ?> <?= e(SITE_NAME) ?> · <?= e(OWNER_INITIALS) ?> Orbit</span>
    <span class="muted">Fuelled by chai ☕</span>
  </div>
</footer>
