<?php
$nav = [
    'About'   => url('/#sides'),
    'Journey' => url('/#journey'),
    'Lens'    => url('/#lens'),
    'Travel'  => url('/#travel'),
    'Skills'  => url('/#skills'),
    'Blogs'   => url('/blogs'),
    'Contact' => url('/#contact'),
];
?>
<header id="site-header" class="site-header fixed inset-x-0 top-0 z-50">
  <div class="max-w-6xl mx-auto flex items-center justify-between px-4 sm:px-6 h-16">
    <a href="<?= url('/') ?>" class="font-serif text-xl font-semibold tracking-wide" aria-label="<?= e(OWNER_NAME) ?> — home"><?= e(OWNER_INITIALS) ?><span class="text-brand">.</span></a>

    <nav class="hidden md:flex items-center gap-7 text-sm font-medium" aria-label="Main">
      <?php foreach ($nav as $label => $href): ?>
        <a href="<?= e($href) ?>" class="nav-link"><?= e($label) ?></a>
      <?php endforeach; ?>
    </nav>

    <button id="menu-toggle" class="md:hidden text-xl p-2 -mr-2" aria-label="Open menu" aria-expanded="false" aria-controls="mobile-menu">
      <i class="fas fa-bars"></i>
    </button>
  </div>

  <nav id="mobile-menu" class="md:hidden hidden border-t border-white/10 bg-ink/95 backdrop-blur px-6 py-4 space-y-3" aria-label="Mobile">
    <?php foreach ($nav as $label => $href): ?>
      <a href="<?= e($href) ?>" class="block text-white/90 hover:text-brand"><?= e($label) ?></a>
    <?php endforeach; ?>
  </nav>
</header>
