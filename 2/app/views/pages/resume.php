<?php $p = data("profile"); ?>
<section class="page-top section">
  <div class="max-w-3xl mx-auto px-4 sm:px-6">
    <div class="card p-8 sm:p-10 print:shadow-none print:border-0">
      <header class="text-center border-b border-slate-200 pb-6 mb-6">
        <h1 class="font-serif text-3xl md:text-4xl font-semibold text-ink"><?= e($p["full_name"]) ?></h1>
        <p class="text-slate-600 mt-1"><?= e($p["headline"]) ?></p>
        <p class="flex flex-wrap justify-center gap-x-4 gap-y-1 text-sm mt-3">
          <a href="mailto:<?= e($p["email"]) ?>" class="text-brand-dark hover:underline"><?= e($p["email"]) ?></a>
          <a href="<?= e(SITE_URL) ?>" class="text-brand-dark hover:underline"><?= e(SITE_DOMAIN) ?></a>
          <a href="<?= e($p["linkedin"]) ?>" target="_blank" rel="noopener" class="text-brand-dark hover:underline">LinkedIn</a>
        </p>
      </header>

      <h2 class="resume-h">Professional Summary</h2>
      <p class="text-sm text-slate-700 leading-relaxed mb-6"><?= e($p["summary"]) ?></p>

      <h2 class="resume-h">Skills &amp; Technologies</h2>
      <div class="grid sm:grid-cols-2 gap-4 text-sm mb-6">
        <?php foreach (data('skills') as $s): ?>
          <div><h3 class="font-medium text-ink"><?= e($s['title']) ?></h3><p class="text-slate-600"><?= e(implode(', ', $s['items'])) ?></p></div>
        <?php endforeach; ?>
      </div>

      <h2 class="resume-h">Experience</h2>
      <div class="text-sm space-y-4 mb-6">
        <?php foreach (array_filter(data('journey'), fn($j) => $j['type'] === 'work') as $j): ?>
          <div><h3 class="font-semibold text-ink"><?= e($j['title']) ?> <span class="text-slate-500 font-normal">· <?= (int) $j['year'] ?></span></h3><p class="text-slate-600"><?= e($j['desc']) ?></p></div>
        <?php endforeach; ?>
      </div>

      <h2 class="resume-h">Education</h2>
      <?php foreach ($p["education"] as $ed): ?>
        <p class="text-sm text-slate-700"><strong><?= e($ed["degree"]) ?></strong> — <?= e($ed["school"]) ?> · <?= (int) $ed["year"] ?></p>
      <?php endforeach; ?>

      <div class="mt-8 print:hidden"><a href="<?= e($p["resume_url"]) ?>" target="_blank" rel="noopener" class="btn btn-primary"><i class="fas fa-print"></i> Print / Save as PDF</a></div>
    </div>
  </div>
</section>
