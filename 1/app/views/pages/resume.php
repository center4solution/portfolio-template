<?php
// pages/resume.php — content comes from shared/app/data (profile, skills, journey)
$p = data('profile');
$e = fn($v) => htmlspecialchars((string) $v);
?>

<section class="bg-white text-gray-800 py-10 px-6 max-w-4xl mx-auto print:px-0 print:py-0">
  <!-- Header -->
  <header class="text-center border-b-2 border-gray-300 pb-4 mb-6">
    <h1 class="text-3xl md:text-4xl font-bold text-gray-900"><?= $e($p['full_name']) ?></h1>
    <p class="text-gray-600 text-sm md:text-base mt-1"><?= $e($p['headline']) ?></p>
    <div class="flex flex-wrap justify-center gap-4 text-sm mt-3">
      <a href="mailto:<?= $e($p['email']) ?>" class="hover:underline text-blue-600"><?= $e($p['email']) ?></a>
      <span class="text-gray-400">|</span>
      <a href="<?= e(SITE_URL) ?>" class="hover:underline text-blue-600"><?= e(SITE_DOMAIN) ?></a>
      <span class="text-gray-400">|</span>
      <a href="<?= $e($p['linkedin']) ?>" target="_blank" class="hover:underline text-blue-600">LinkedIn</a>
    </div>
  </header>

  <!-- Summary -->
  <section class="mb-6">
    <h2 class="text-xl font-semibold text-emerald-600 mb-2">Professional Summary</h2>
    <p class="text-gray-700 text-sm leading-relaxed"><?= $e($p['summary']) ?></p>
  </section>

  <!-- Skills -->
  <section class="mb-6">
    <h2 class="text-xl font-semibold text-emerald-600 mb-2">Skills & Technologies</h2>
    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 text-sm text-gray-700">
      <?php foreach (data('skills') as $skill): ?>
      <div>
        <h4 class="font-medium text-gray-900 mb-1"><?= $e($skill['title']) ?></h4>
        <p><?= $e(implode(', ', $skill['items'])) ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- Experience -->
  <section class="mb-6">
    <h2 class="text-xl font-semibold text-emerald-600 mb-2">Experience</h2>
    <div class="text-sm">
      <?php foreach (array_filter(data('journey'), fn($j) => $j['type'] === 'work') as $job): ?>
      <div class="mb-4">
        <h3 class="font-semibold text-gray-900"><?= $e($job['title']) ?></h3>
        <p class="text-gray-500 text-xs"><?= (int) $job['year'] ?></p>
        <p class="text-gray-700 mt-1"><?= $e($job['desc']) ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- Education -->
  <section class="mb-6">
    <h2 class="text-xl font-semibold text-emerald-600 mb-2">Education</h2>
    <div class="text-sm text-gray-700">
      <?php foreach ($p['education'] as $ed): ?>
      <p><strong><?= $e($ed['degree']) ?></strong></p>
      <p class="text-xs text-gray-500 mb-2"><?= $e($ed['school']) ?> · <?= (int) $ed['year'] ?></p>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- Additional Info -->
  <section class="mb-8 print:hidden">
    <a href="<?= $e($p['resume_url']) ?>" target="_blank" rel="noopener" class="inline-block bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2 rounded-md shadow text-sm font-medium">
      📥 Download PDF Resume
    </a>
  </section>
</section>

