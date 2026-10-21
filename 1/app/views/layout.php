<!DOCTYPE html>
<html lang="en">
   <head>
      <?= view('partials/head', ['seo' => $seo ?? []]) ?>
   </head>
   <body class="font-inter text-gray-800 bg-white <?= e($bodyClass ?? '') ?>">
      <?= view('partials/header') ?>

      <main class="min-h-screen">
            <?= $pageContent ?>
      </main>

      <?= view('partials/footer') ?>

   </body>

   <script>
      document.addEventListener('DOMContentLoaded', () => {
         const isHome = document.body.classList.contains('home-page');
         const header = document.getElementById('main-header');
         const hero = document.getElementById('hero');

         if (!header) return;

         function updateHeaderVisibility() {
            if (isHome && hero) {
               const heroBottom = hero.getBoundingClientRect().bottom;

               if (heroBottom <= 0 || window.scrollY > window.innerHeight * 0.6) {
               // Show header
               header.classList.remove('hidden');
               header.classList.remove('hidden-header');
               header.classList.add('visible-header');
               } else {
               // Hide header while hero is visible
               header.classList.add('hidden');
               header.classList.add('hidden-header');
               header.classList.remove('visible-header');
               }
            } else {
               // Other pages: always show header
               header.classList.remove('hidden');
               header.classList.remove('hidden-header');
               header.classList.add('visible-header');
            }
         }

         // Run on load and on scroll
         updateHeaderVisibility();
         if (isHome && hero) {
            window.addEventListener('scroll', updateHeaderVisibility);
         }
      });
   </script>
</html>
