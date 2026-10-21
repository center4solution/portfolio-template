<?php
// Social links come from shared/app/data/socials.php; icon colours are v1's own look
$iconColors = [
  'fab fa-instagram' => 'text-pink-500', 'fab fa-youtube' => 'text-red-600', 'fas fa-globe' => 'text-indigo-600',
  'fas fa-at' => 'text-gray-800', 'fab fa-facebook' => 'text-blue-600', 'fab fa-x-twitter' => 'text-black',
  'fab fa-snapchat-ghost' => 'text-yellow-400', 'fab fa-telegram-plane' => 'text-blue-400', 'fab fa-whatsapp' => 'text-green-500',
  'fab fa-tiktok' => 'text-black', 'fas fa-envelope' => 'text-purple-600', 'fab fa-linkedin' => 'text-[#0077B5]',
  'fab fa-medium' => 'text-gray-700', 'fab fa-reddit' => 'text-orange-600', 'fab fa-quora' => 'text-red-700',
];
$socialsByPurpose = [];
foreach (data('socials') as $purpose => $links) {
  foreach ($links as [$platform, $icon, $url]) {
    $socialsByPurpose[$purpose][] = ['platform' => $platform, 'icon' => $icon, 'url' => $url, 'color' => $iconColors[$icon] ?? 'text-gray-700'];
  }
}
$ok = flash('contact_ok');
$error = flash('contact_error');
?>

<section id="contact" class="py-20 bg-gray-100 text-gray-800">
  <div class="max-w-7xl mx-auto px-6">
    <div class="text-center mb-12">
      <h2 class="text-3xl md:text-4xl font-bold">Let’s Connect</h2>
      <p class="text-gray-600 mt-2">Find me across platforms or send a direct message below.</p>
      <div class="w-16 h-1 bg-blue-400 mx-auto mt-4 rounded"></div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
      <!-- Left: Social Media Icons Only -->
      <div class="space-y-10">
        <?php foreach ($socialsByPurpose as $purpose => $platforms): ?>
          <div>
            <h4 class="text-lg font-semibold mb-3"><?= $purpose ?></h4>
            <div class="flex flex-wrap gap-4">
              <?php foreach ($platforms as $link): ?>
                <a aria-label="Connect with <?= e(OWNER_NAME) ?> On <?= $link['platform']; ?>" href="<?= $link['url'] ?>" target="_blank" class="text-2xl <?= $link['color'] ?> hover:opacity-80 transition">
                  <i class="<?= $link['icon'] ?>"></i>
                </a>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <!-- Right: Message Form -->
      <div class="bg-white p-6 rounded-lg shadow-md">
        <form action="<?= url('/contact') ?>" method="POST" class="space-y-4">
          <input type="hidden" name="_token" value="<?= e(csrf_token()) ?>" />
          <div class="hidden" aria-hidden="true"><label>Website <input type="text" name="website" tabindex="-1" autocomplete="off" /></label></div>
          <div>
            <label class="block text-sm font-medium">Name *</label>
            <input type="text" name="name" required class="w-full border px-4 py-2 rounded-md focus:ring-2 focus:ring-blue-300" />
          </div>
          <div>
            <label class="block text-sm font-medium">Email *</label>
            <input type="email" name="email" required class="w-full border px-4 py-2 rounded-md focus:ring-2 focus:ring-blue-300" />
          </div>
          <div>
            <label class="block text-sm font-medium">Phone</label>
            <input type="text" name="phone" class="w-full border px-4 py-2 rounded-md focus:ring-2 focus:ring-blue-300" />
          </div>
          <div>
            <label class="block text-sm font-medium">Message *</label>
            <textarea name="message" rows="5" required class="w-full border px-4 py-2 rounded-md focus:ring-2 focus:ring-blue-300"></textarea>
          </div>
          <div class="text-right">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md shadow">
              Send Message
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Form result -->
    <?php if ($ok): ?>
      <div class="mt-6 bg-green-100 text-green-800 text-center py-3 px-4 rounded shadow-md" role="status">
        ✅ <?= e($ok) ?>
      </div>
    <?php endif; ?>
    <?php if ($error): ?>
      <div class="mt-6 bg-red-100 text-red-800 text-center py-3 px-4 rounded shadow-md" role="alert">
        <?= e($error) ?>
      </div>
    <?php endif; ?>
  </div>
</section>

<footer class="bg-gray-900 text-white py-6 text-center text-sm">
  <p>
    &copy; <?= date("Y"); ?> <?= e(OWNER_NAME) ?> — <code class="text-emerald-400">&lt;/crafted&gt;</code> with code ❤️ + chai ☕.
  </p>
</footer>
