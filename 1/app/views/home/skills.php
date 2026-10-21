<?php
// Skill groups come from shared/app/data/skills.php; colours are v1's own look
$colors = ['red', 'blue', 'yellow', 'emerald', 'purple'];
$skills = data('skills');
foreach ($skills as $i => &$skill) {
    $c = $colors[$i % count($colors)];
    $skill['color'] = "$c-600";
    $skill['bg'] = "bg-$c-100 text-$c-700";
}
unset($skill);
?>

<!-- Skills Section -->
<section id="skills" class="py-14 bg-white text-gray-800">
    <div class="max-w-7xl mx-auto px-4">
        <!-- Section Header -->
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold">Tech Stack & Expertise</h2>
            <p class="text-sm sm:text-base text-emerald-700 font-medium mt-2 max-w-3xl mx-auto">
                Tools and technologies I use to design, develop, and deploy full-stack applications with performance, scalability, and creativity in mind.
            </p>
            <div class="w-16 h-1 bg-emerald-400 mx-auto mt-4 rounded"></div>
        </div>

        <!-- Skills Flex -->
        <div class="flex flex-wrap justify-center gap-6">
            <?php foreach ($skills as $skill): ?>
                <div class="w-full sm:w-[48%] lg:w-[30%] p-6 bg-gray-50 border border-gray-200 shadow-md rounded-2xl hover:shadow-lg transition duration-300 text-center">
                    <h3 class="text-lg font-semibold text-<?= $skill['color'] ?> mb-4 uppercase"><?= $skill['title'] ?></h3>
                    <div class="flex flex-wrap justify-center gap-2 text-sm font-medium">
                        <?php foreach ($skill['items'] as $item): ?>
                            <span class="<?= $skill['bg'] ?> px-3 py-1 rounded-full shadow-sm"><?= $item ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Resume Button -->
        <div class="mt-12 text-center">
            <a aria-label="check resume of <?= e(OWNER_NAME) ?>"href="<?= e(data('profile')['resume_url']) ?>" target="_blank" class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md shadow transition">
                👨‍💻 My Developer Profile
            </a>
        </div>
    </div>
</section>
