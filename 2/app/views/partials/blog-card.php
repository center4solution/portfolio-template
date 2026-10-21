<a href="<?= url('/blogs/' . $blog['slug']) ?>" class="card group overflow-hidden flex flex-col reveal">
  <div class="aspect-[16/9] overflow-hidden bg-slate-100">
    <img src="<?= e(url($blog['cover'])) ?>" alt="" loading="lazy" class="w-full h-full object-cover transition duration-500 group-hover:scale-105">
  </div>
  <div class="p-5 flex flex-col flex-1">
    <p class="text-xs font-medium text-brand-dark uppercase tracking-wider mb-2"><?= e($blog['category']) ?> · <?= e(date('M j, Y', strtotime($blog['date']))) ?></p>
    <h3 class="font-semibold text-lg text-ink leading-snug mb-2 group-hover:text-brand-dark transition"><?= e($blog['title']) ?></h3>
    <p class="text-sm text-slate-600 line-clamp-3 flex-1"><?= e($blog['description']) ?></p>
    <span class="mt-4 text-sm font-semibold text-ink">Read article <i class="fas fa-arrow-right text-xs ml-1 transition group-hover:translate-x-1"></i></span>
  </div>
</a>
