<?php
// Timeline entries come from shared/app/data/journey.php; colours are v1's own look
$colors = ['amber-600', 'blue-600', 'green-600', 'cyan-600', 'emerald-600', 'pink-600', 'yellow-600', 'indigo-600'];
$journey = data('journey');
foreach ($journey as $i => &$item) {
    $item['color'] = $colors[$i % count($colors)];
}
unset($item);

usort($journey, fn($a, $b) => intval($a['year']) <=> intval($b['year']));

foreach ($journey as $i => &$item) {
    $item['align'] = ($i % 2 === 0) ? 'left' : 'right';
}
unset($item);
?>

<section id="journey" class="py-6 sm:py-6 md:py-10 bg-white text-gray-800 bg-gray-100">
   <div class="max-w-6xl mx-auto px-4">
      <div class="text-center mb-12">
         <h2 class="text-3xl md:text-4xl font-bold">Moments That Made Me</h2>
         <p class="text-gray-600 mt-2">Snapshots of a life lived with purpose and passion</p>
         <div class="w-16 h-1 bg-blue-400 mx-auto mt-4 rounded"></div>
      </div>

      <div class="relative">
         <!-- Timeline line for desktop -->
         <div class="hidden md:block absolute top-0 left-1/2 transform -translate-x-1/2 w-1 bg-gray-300 h-full"></div>

         <div class="space-y-8">
  <?php foreach ($journey as $item): ?>
    <div class="flex flex-col md:flex-row items-start md:items-center relative">
      
      <!-- For Desktop -->
      <?php if ($item['align'] === 'left'): ?>
        <div class="hidden md:block w-full md:w-1/2 text-right pr-6 order-1">
          <h3 class="text-lg sm:text-xl font-semibold text-<?= $item['color'] ?>"><?= $item['title'] ?> · <?= $item['year'] ?></h3>
          <p class="text-gray-600 mt-1"><?= $item['desc'] ?></p>
        </div>
        <div class="hidden md:flex relative z-10 order-2">
          <div class="w-5 h-5 bg-<?= $item['color'] ?> rounded-full border-4 border-white shadow-md mx-auto"></div>
        </div>
        <div class="hidden md:block w-full md:w-1/2 order-3"></div>

      <?php else: ?>
        <div class="hidden md:block w-full md:w-1/2 order-1"></div>
        <div class="hidden md:flex relative z-10 order-2">
          <div class="w-5 h-5 bg-<?= $item['color'] ?> rounded-full border-4 border-white shadow-md mx-auto"></div>
        </div>
        <div class="hidden md:block w-full md:w-1/2 text-left pl-6 order-3">
          <h3 class="text-lg sm:text-xl font-semibold text-<?= $item['color'] ?>"><?= $item['title'] ?> · <?= $item['year'] ?></h3>
          <p class="text-gray-600 mt-1"><?= $item['desc'] ?></p>
        </div>
      <?php endif; ?>

      <!-- Mobile Version (full width, dot on left) -->
      <div class="md:hidden flex items-start space-x-4">
        <div class="mt-1">
          <div class="w-4 h-4 bg-<?= $item['color'] ?> rounded-full border-2 border-white shadow-md"></div>
        </div>
        <div>
          <h3 class="text-base font-semibold text-<?= $item['color'] ?>"><?= $item['title'] ?> · <?= $item['year'] ?></h3>
          <p class="text-gray-600 text-sm mt-1"><?= $item['desc'] ?></p>
        </div>
      </div>

    </div>
  <?php endforeach; ?>
</div>


         
      </div>

   </div>
</section>
