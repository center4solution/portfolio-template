<?php $p = data("profile"); ?>
<section class="sec page-top">
  <div class="wrap narrow">
    <div class="paper-sheet">
      <header class="resume-head">
        <h1 class="story-title"><?= e($p["full_name"]) ?></h1>
        <p class="kicker"><?= e($p["headline"]) ?></p>
        <p class="resume-links mono">
          <a href="mailto:<?= e($p["email"]) ?>"><?= e($p["email"]) ?></a>
          <a href="<?= e(SITE_URL) ?>"><?= e(SITE_DOMAIN) ?></a>
          <a href="<?= e($p["linkedin"]) ?>" target="_blank" rel="noopener">LinkedIn</a>
        </p>
      </header>

      <h2 class="resume-h">Summary</h2>
      <p><?= e($p["summary"]) ?></p>

      <h2 class="resume-h">Skills</h2>
      <dl class="resume-skills">
        <?php foreach (data('skills') as $s): ?><dt><?= e($s['title']) ?></dt><dd><?= e(implode(', ', $s['items'])) ?></dd><?php endforeach; ?>
      </dl>

      <h2 class="resume-h">Experience</h2>
      <?php foreach (array_filter(data('journey'), fn($j) => $j['type'] === 'work') as $j): ?>
        <p><b><?= e($j['title']) ?></b> <span class="mono muted">· <?= (int) $j['year'] ?></span><br><span class="muted"><?= e($j['desc']) ?></span></p>
      <?php endforeach; ?>

      <h2 class="resume-h">Education</h2>
      <?php foreach ($p["education"] as $ed): ?><p><b><?= e($ed["degree"]) ?></b>, <?= e($ed["school"]) ?> · <?= (int) $ed["year"] ?></p><?php endforeach; ?>

      <p class="no-print mt-8"><a href="<?= e($p["resume_url"]) ?>" target="_blank" rel="noopener" class="btn btn-primary"><i class="fas fa-print"></i> Print / Save as PDF</a></p>
    </div>
  </div>
</section>
