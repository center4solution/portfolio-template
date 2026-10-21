<div class="text-center mb-12 reveal">
  <?php if (!empty($eyebrow)): ?><p class="eyebrow"><?= e($eyebrow) ?></p><?php endif; ?>
  <h2 class="font-serif text-3xl md:text-4xl font-semibold text-ink"><?= e($title) ?></h2>
  <?php if (!empty($sub)): ?><p class="text-slate-600 mt-3 max-w-2xl mx-auto"><?= e($sub) ?></p><?php endif; ?>
  <div class="w-14 h-1 bg-brand mx-auto mt-5 rounded-full"></div>
</div>
