<header class="chapter-head reveal">
  <?php if (!empty($num)): ?><span class="chapter-num"><?= e($num) ?></span><?php endif; ?>
  <div>
    <?php if (!empty($eyebrow)): ?><p class="kicker"><?= e($eyebrow) ?></p><?php endif; ?>
    <h2 class="chapter-title"><?= e($title) ?></h2>
    <?php if (!empty($sub)): ?><p class="chapter-sub"><?= e($sub) ?></p><?php endif; ?>
  </div>
</header>
