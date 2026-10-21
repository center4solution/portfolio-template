<section class="bg-gray-50 text-gray-800 py-16">
  <div class="max-w-4xl mx-auto px-6">
    <h1 class="text-3xl md:text-4xl font-bold mb-6 text-center">Privacy Policy</h1>
    <p class="text-sm text-gray-500 text-center mb-10">Last updated: <?= date("F j, Y") ?></p>

    <div class="space-y-6 text-base leading-relaxed text-gray-700">
      <p>
        Thank you for visiting <strong><?= e(SITE_DOMAIN) ?></strong>. This is a personal website managed by <?= e(OWNER_NAME) ?>
        to share stories, experiences, and creative work.
      </p>

      <h2 class="text-xl font-semibold text-gray-900">1. Information We Collect</h2>
      <p>
        We do not collect any personal data unless you voluntarily submit it via the contact form.
        Information you may provide includes:
      </p>
      <ul class="list-disc pl-6 text-gray-700">
        <li>Your name</li>
        <li>Email address</li>
        <li>Phone number (optional)</li>
        <li>Message content</li>
      </ul>
      <p>
        This data is used solely to respond to your inquiry and is not stored long-term or shared.
      </p>

      <h2 class="text-xl font-semibold text-gray-900">2. Cookies & Analytics</h2>
      <p>
        This website does <strong>not</strong> use cookies, analytics, or tracking technologies.
      </p>

      <h2 class="text-xl font-semibold text-gray-900">3. External Links</h2>
      <p>
        Blog posts and social icons may link to external platforms (e.g., Instagram, YouTube).
        These sites have their own privacy practices which are independent of this site.
      </p>

      <h2 class="text-xl font-semibold text-gray-900">4. Data Sharing</h2>
      <p>
        Your personal data is never sold, shared, or disclosed to any third party. It is only used for direct communication initiated by you.
      </p>

      <h2 class="text-xl font-semibold text-gray-900">5. Contact</h2>
      <p>
        If you have questions about this privacy policy, feel free to reach out via the contact form or by email at:
        <a href="mailto:<?= e(data('profile')['email']) ?>" class="text-blue-600 hover:underline"><?= e(data('profile')['email']) ?></a>
      </p>
    </div>
  </div>
</section>

