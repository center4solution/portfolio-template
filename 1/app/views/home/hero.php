<section id="hero" class="relative min-h-screen bg-cover bg-center" style="background-image: url('<?= e(url(data('profile')['hero_bg'])) ?>')">

  <!-- Hero Content -->
  <div class="absolute left-1/2 transform -translate-x-1/2 w-[92%] max-w-3xl
        bottom-20 sm:bottom-10 md:py-12 
        px-4 sm:px-6 
        py-2 sm:py-6 md:py-10 
        bg-white/10 backdrop-blur-lg border border-blue-300/30 rounded-3xl 
        shadow-[0_8px_32px_rgba(0,0,0,0.25)] text-center hover:shadow-blue-500/20 
        transition-all duration-500">
        
   <h1 class="text-[clamp(1.5rem,4vw,2rem)] font-serif font-bold text-white leading-snug mb-6 drop-shadow-sm">
  Hello, I'm
  <br class="block sm:hidden">
  <span class="bg-blue-600/90 px-3 py-1.5 rounded-lg text-white shadow-md inline-block">
    <?= e(OWNER_NAME) ?>
  </span>
</h1>




    <!-- Divider -->
    <div class="w-16 h-1 bg-blue-400 mx-auto rounded mb-4"></div>
    
    <p class="text-sm sm:text-base text-white/80 font-semibold mb-3 tracking-wide leading-relaxed text-center whitespace-nowrap overflow-auto">
      <span class="inline text-amber-400">Software Engineer</span>
      <span class="hidden sm:inline"> • </span>
      <span class="hidden sm:inline text-emerald-400">Photographer</span>
      <span class="inline"> • </span>
      <span class="inline text-red-600">Travel Enthusiast</span>
   </p>



    <!-- <p class="text-sm sm:text-base text-white/80 font-semibold mb-3 tracking-wide leading-relaxed text-center">
        <span class="block sm:inline text-amber-400">Software Engineer</span>
        <span class="hidden sm:inline"> • </span>
        <span class="block sm:inline text-emerald-400">Photographer</span>
        <span class="hidden sm:inline"> • </span>
        <span class="block sm:inline text-red-600">Travel Enthusiast</span>
    </p> -->

    <p class="text-sm sm:text-base text-white/80 leading-relaxed mb-4 px-2">
      I craft digital experiences with logic and curiosity —<br />
      blending code, travel to keep evolving.
    </p>

    <p class="text-base sm:text-lg text-blue-300 font-semibold italic tracking-wide">
      Engineer by craft. Explorer by heart.
    </p>
  </div>

  <!-- Scroll Down Icon -->
<a aria-label="know more about <?= e(OWNER_NAME) ?>" href="#about" id="scrollDownIcon" class="absolute bottom-6 left-1/2 transform -translate-x-1/2 text-white text-2xl animate-bounce">
  <i class="fas fa-chevron-down"></i>
</a>
</section>


<script>
  document.getElementById('scrollDownIcon').addEventListener('click', function (e) {
    e.preventDefault();
    document.querySelector('#about').scrollIntoView({ behavior: 'smooth' });
  });
</script>
