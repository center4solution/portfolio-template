<?php
$socials = data('socials');
$ok = flash('contact_ok');
$error = flash('contact_error');
$lineColor = ['Personal' => 'red', 'Travel' => 'green', 'Photography' => 'orange'];
?>
<section id="contact" class="sec">
  <div class="wrap">
    <?= view('partials/section-title', ['code' => 'T', 'color' => 'pink', 'eyebrow' => 'Ticket counter', 'title' => 'Book a conversation', 'sub' => 'Messages go straight to my phone. I usually reply within a day.']) ?>
    <div class="contact-grid">
      <form action="<?= url('/contact') ?>" method="POST" class="ticket reveal" novalidate>
        <div class="ticket-head">
          <span class="roundel sm" aria-hidden="true"><span><?= e(OWNER_INITIALS) ?></span></span>
          <div><b>Single journey</b><small>To: <?= e(OWNER_NAME) ?> · Class: any</small></div>
          <span class="ticket-no mono">No. <?= e(strtoupper(substr(bin2hex(random_bytes(3)), 0, 6))) ?></span>
        </div>
        <?php if ($ok): ?><div class="alert alert-ok" role="status"><i class="fas fa-check"></i> <?= e($ok) ?></div><?php endif; ?>
        <?php if ($error): ?><div class="alert alert-error" role="alert"><i class="fas fa-xmark"></i> <?= e($error) ?></div><?php endif; ?>
        <input type="hidden" name="_token" value="<?= e(csrf_token()) ?>">
        <div class="hp" aria-hidden="true"><label>Website <input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>
        <div class="ticket-fields">
          <label class="field"><span>Passenger name<b>*</b></span><input type="text" name="name" required maxlength="100" autocomplete="name"></label>
          <label class="field"><span>Email<b>*</b></span><input type="email" name="email" required autocomplete="email"></label>
          <label class="field"><span>Phone</span><input type="tel" name="phone" maxlength="30" autocomplete="tel" placeholder="optional"></label>
          <label class="field field-full"><span>Message<b>*</b></span><textarea name="message" rows="5" required maxlength="4000"></textarea></label>
        </div>
        <div class="ticket-foot">
          <a href="<?= url('/privacy-policy') ?>" class="muted small">Conditions of carriage (privacy)</a>
          <button type="submit" class="btn btn-primary"><i class="fas fa-ticket"></i> Validate &amp; send</button>
        </div>
      </form>

      <div class="connections reveal">
        <p class="kicker">Connections from this station</p>
        <?php foreach ($socials as $group => $links): $c = $lineColor[$group] ?? 'blue'; ?>
          <div class="conn-group">
            <span class="line-badge sm c-<?= e($c) ?>"><?= e(substr($group, 0, 1)) ?></span>
            <div>
              <h3><?= e($group) ?> line</h3>
              <div class="conn-chips">
                <?php foreach ($links as [$label, $icon, $href]): ?>
                  <a href="<?= e($href) ?>" target="_blank" rel="noopener" class="conn c-<?= e($c) ?>" aria-label="<?= e($group . ' — ' . $label) ?>" title="<?= e($label) ?>"><i class="<?= e($icon) ?>"></i></a>
                <?php endforeach; ?>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<footer class="endline">
  <div class="wrap endline-inner">
    <span class="terminus"><span class="stop big" aria-hidden="true"></span> End of the line. Thanks for riding.</span>
    <span>© <?= date('Y') ?> <?= e(SITE_NAME) ?> · <?= e(OWNER_INITIALS) ?> Transit</span>
    <span class="muted">Mind the gap ☕</span>
  </div>
</footer>
