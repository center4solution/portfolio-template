<header class="sec-head reveal">
  <?php if (!empty($num)): ?><span class="sec-num mono">[<?= e($num) ?>]</span><?php endif; ?>
  <?php if (!empty($eyebrow)): ?><p class="kicker"><?= e($eyebrow) ?></p><?php endif; ?>
  <h2 class="sec-title" data-scramble><?= e($title) ?></h2>
  <?php if (!empty($sub)): ?><p class="sec-sub"><?= e($sub) ?></p><?php endif; ?>
</header>
