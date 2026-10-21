<header id="main-header" class="fixed top-0 left-0 w-full z-50 flex justify-between items-center px-4 sm:px-6 py-4 text-white bg-black/70 backdrop-blur-lg shadow-md transition-all duration-300">
   
   <!-- Logo -->
   <div class="text-xl font-bold tracking-wider">
      <a aria-label="<?= e(OWNER_NAME) ?>: Full Stack Developer" href="<?= url('/') ?>"><?= e(OWNER_INITIALS) ?></a>
   </div>

   <!-- Desktop Navigation -->
   <nav class="space-x-4 hidden md:flex">
      <a aria-label="know more about <?= e(OWNER_NAME) ?>" href="<?= url('/#about') ?>" class="hover:text-blue-400">About</a>
      <a aria-label="read all blogs by <?= e(OWNER_NAME) ?>" href="<?= url('/blogs') ?>" class="hover:text-blue-400">Blogs</a>
      <a aria-label="check skills of <?= e(OWNER_NAME) ?>" href="<?= url('/#skills') ?>" class="hover:text-blue-400">Skills</a>
      <a aria-label="let's Contact with <?= e(OWNER_NAME) ?>" href="<?= url('/#contact') ?>" class="hover:text-blue-400">Contact</a>
   </nav>

   <!-- Mobile Hamburger -->
   <div class="md:hidden block">
      <button id="mobile-menu-toggle" class="text-white text-2xl focus:outline-none">
         <i class="fas fa-bars"></i>
      </button>
   </div>
</header>

<!-- Mobile Menu -->
<nav id="mobile-menu" 
   class="md:hidden hidden fixed top-16 left-0 w-full bg-black/90 text-white px-6 py-4 space-y-2 z-[60] shadow-lg transition-all duration-300">
   <a href="<?= url('/#about') ?>" class="block hover:text-blue-400">About</a>
   <a href="<?= url('/blogs') ?>" class="block hover:text-blue-400">Blogs</a>
   <a href="<?= url('/#skills') ?>" class="block hover:text-blue-400">Skills</a>
   <a href="<?= url('/#contact') ?>" class="block hover:text-blue-400">Contact</a>
</nav>


<!-- Toggle Script -->
<script>
  document.addEventListener('DOMContentLoaded', function () {
      const toggleBtn = document.getElementById('mobile-menu-toggle');
      const mobileMenu = document.getElementById('mobile-menu');

      if (toggleBtn && mobileMenu) {
         toggleBtn.addEventListener('click', () => {
            console.log('Toggle button clicked');
            mobileMenu.classList.toggle('hidden');
         });
      }
   });
</script>
