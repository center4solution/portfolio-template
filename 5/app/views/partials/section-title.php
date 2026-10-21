<header class="sec-head reveal">
  <?php if (!empty($code)): ?><span class="line-badge c-<?= e($color ?? 'red') ?>"><?= e($code) ?></span><?php endif; ?>
  <div>
    <?php if (!empty($eyebrow)): ?><p class="kicker"><?= e($eyebrow) ?></p><?php endif; ?>
    <h2 class="sec-title"><?= e($title) ?></h2>
    <?php if (!empty($sub)): ?><p class="sec-sub"><?= e($sub) ?></p><?php endif; ?>
  </div>
</header>
