<?php
require_once VERSION_DIR . '/app/chain.php';
$socials = data('socials');
$ok = flash('contact_ok');
$error = flash('contact_error');
$height = count(data('journey')) + count(published_blogs());
?>
<section id="send" class="sec">
  <div class="wrap">
    <?= view('partials/section-title', ['icon' => 'fa-paper-plane', 'eyebrow' => 'Send transaction', 'title' => 'Start a conversation', 'sub' => 'Messages go straight to my phone. No gas fee, and I usually reply within a day.']) ?>
    <div class="send-grid">
      <form action="<?= url('/contact') ?>" method="POST" class="panel send-form reveal" id="send-form" novalidate>
        <div class="send-head">
          <span class="mono muted">To</span>
          <span class="addr mono"><i class="fas fa-wallet"></i> <?= e(short_hash(wallet_address(), 10, 6)) ?></span>
          <span class="chip-ok mono"><span class="pulse"></span> Verified</span>
        </div>
        <?php if ($ok): ?><div class="alert alert-ok" role="status"><i class="fas fa-circle-check"></i> <?= e($ok) ?></div><?php endif; ?>
        <?php if ($error): ?><div class="alert alert-error" role="alert"><i class="fas fa-triangle-exclamation"></i> <?= e($error) ?></div><?php endif; ?>
        <input type="hidden" name="_token" value="<?= e(csrf_token()) ?>">
        <div class="hp" aria-hidden="true"><label>Website <input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>
        <div class="fields">
          <label class="field"><span>From (your name)<b>*</b></span><input type="text" name="name" required maxlength="100" autocomplete="name"></label>
          <label class="field"><span>Email<b>*</b></span><input type="email" name="email" required autocomplete="email"></label>
          <label class="field"><span>Phone</span><input type="tel" name="phone" maxlength="30" autocomplete="tel" placeholder="optional"></label>
          <label class="field field-full"><span>Memo (message)<b>*</b></span><textarea name="message" rows="5" required maxlength="4000"></textarea></label>
        </div>
        <div class="fee mono"><span>Network fee</span><span class="ok">0.00 <?= e(OWNER_INITIALS) ?> · free</span></div>
        <div class="send-foot">
          <a href="<?= url('/privacy-policy') ?>" class="muted small">Privacy policy</a>
          <button type="submit" class="btn btn-primary"><i class="fas fa-signature"></i> Sign &amp; send</button>
        </div>
      </form>

      <div class="bridges reveal">
        <p class="kicker">Bridges to other networks</p>
        <?php foreach ($socials as $group => $links): ?>
          <div class="panel bridge">
            <h3><?= e($group) ?> <span class="mono muted">[<?= count($links) ?>]</span></h3>
            <div class="bridge-chips">
              <?php foreach ($links as [$label, $icon, $href]): ?>
                <a href="<?= e($href) ?>" target="_blank" rel="noopener" class="bridge-link" aria-label="<?= e($group . ' — ' . $label) ?>" title="<?= e($label) ?>"><i class="<?= e($icon) ?>"></i></a>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<div class="modal" id="confirm" hidden role="dialog" aria-modal="true" aria-labelledby="confirm-title">
  <div class="panel modal-box">
    <p class="kicker"><i class="fas fa-shield-halved"></i> Confirm transaction</p>
    <h3 id="confirm-title">Send this message?</h3>
    <dl class="confirm-list mono">
      <div><dt>From</dt><dd data-c="name"></dd></div>
      <div><dt>Email</dt><dd data-c="email"></dd></div>
      <div><dt>To</dt><dd><?= e(short_hash(wallet_address(), 10, 6)) ?></dd></div>
      <div><dt>Memo</dt><dd data-c="message"></dd></div>
      <div><dt>Fee</dt><dd class="ok">0.00 <?= e(OWNER_INITIALS) ?></dd></div>
    </dl>
    <div class="modal-actions">
      <button type="button" class="btn btn-ghost" data-cancel>Reject</button>
      <button type="button" class="btn btn-primary" data-confirm><i class="fas fa-check"></i> Confirm</button>
    </div>
  </div>
</div>

<footer class="footer">
  <div class="wrap footer-inner mono">
    <span><span class="pulse"></span> Synced · block #<?= number_format(18000000 + $height * 1337) ?></span>
    <span>© <?= date('Y') ?> <?= e(SITE_NAME) ?> · <?= e(OWNER_INITIALS) ?> Chain</span>
    <span class="muted">Gas paid in chai ☕</span>
  </div>
</footer>
