<?php
$socials = data('socials');
$ok = flash('contact_ok');
$error = flash('contact_error');
?>
<section id="contact" class="section bg-slate-50">
  <div class="max-w-6xl mx-auto px-4 sm:px-6">
    <?= view('partials/section-title', ['title' => 'Let’s Connect', 'sub' => 'Find me across platforms or send a direct message.']) ?>

    <div class="grid md:grid-cols-2 gap-10">
      <div class="space-y-8">
        <?php foreach ($socials as $group => $links): ?>
          <div>
            <h3 class="text-xs font-semibold uppercase tracking-widest text-slate-500 mb-3"><?= e($group) ?></h3>
            <div class="flex flex-wrap gap-2">
              <?php foreach ($links as [$label, $icon, $href]): ?>
                <a href="<?= e($href) ?>" target="_blank" rel="noopener" class="social-chip" aria-label="<?= e($group . ' — ' . $label) ?>" title="<?= e($label) ?>">
                  <i class="<?= e($icon) ?>"></i>
                </a>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <form action="<?= url('/contact') ?>" method="POST" class="card p-6 space-y-4" novalidate>
        <?php if ($ok): ?><div class="alert alert-ok" role="status"><?= e($ok) ?></div><?php endif; ?>
        <?php if ($error): ?><div class="alert alert-error" role="alert"><?= e($error) ?></div><?php endif; ?>

        <input type="hidden" name="_token" value="<?= e(csrf_token()) ?>">
        <div class="hidden" aria-hidden="true"><label>Website <input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>

        <div class="grid sm:grid-cols-2 gap-4">
          <label class="field"><span>Name *</span><input type="text" name="name" required maxlength="100" autocomplete="name"></label>
          <label class="field"><span>Email *</span><input type="email" name="email" required autocomplete="email"></label>
        </div>
        <label class="field"><span>Phone</span><input type="tel" name="phone" maxlength="30" autocomplete="tel"></label>
        <label class="field"><span>Message *</span><textarea name="message" rows="5" required maxlength="4000"></textarea></label>
        <div class="flex items-center justify-between gap-4">
          <a href="<?= url('/privacy-policy') ?>" class="text-xs text-slate-500 hover:underline">Privacy policy</a>
          <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Send message</button>
        </div>
      </form>
    </div>
  </div>
</section>

<footer class="bg-ink text-white/70 py-8 text-center text-sm">
  <p>&copy; <?= date('Y') ?> <?= e(OWNER_NAME) ?> — <code class="text-brand">&lt;/crafted&gt;</code> with code ❤️ + chai ☕</p>
</footer>
