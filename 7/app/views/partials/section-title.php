<header class="sec-head reveal">
  <?php if (!empty($icon)): ?><span class="sec-icon"><i class="fas <?= e($icon) ?>"></i></span><?php endif; ?>
  <div>
    <?php if (!empty($eyebrow)): ?><p class="kicker"><?= e($eyebrow) ?></p><?php endif; ?>
    <h2 class="sec-title"><?= e($title) ?></h2>
    <?php if (!empty($sub)): ?><p class="sec-sub"><?= e($sub) ?></p><?php endif; ?>
  </div>
</header>
