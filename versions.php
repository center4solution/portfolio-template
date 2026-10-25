<?php
// Local preview: open each site version on its own. Blocked in production by .htaccess.
if (!in_array($_SERVER['REMOTE_ADDR'] ?? '', ['127.0.0.1', '::1'], true)) {
    http_response_code(403);
    exit;
}

$base = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
// Same rule as the root .htaccess: (minute mod 7) + 1
$today = (int) date('i') % 7 + 1;
$versions = [
    1 => ['Classic', 'The original Tailwind site'],
    2 => ['Dark gradient', 'Photo stack, typer, polaroids'],
    3 => ['The Logbook', 'Editorial / magazine style'],
    4 => ['DEV//OS', 'Neon terminal style'],
    5 => ['Transit Map', 'Metro map, departures board'],
    6 => ['Chain', 'Blockchain explorer, wallet, node map'],
    7 => ['Orbit', 'Space mission control, orrery, star map'],
];
$pages = ['' => 'Home', 'blogs' => 'Blogs', 'resume' => 'Resume', 'privacy-policy' => 'Privacy'];
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Site Versions</title>
<style>
  :root { --bg: #f6f7f9; --card: #fff; --ink: #14171f; --muted: #5d6573; --line: #e2e5ea; --accent: #2563eb; }
  @media (prefers-color-scheme: dark) { :root { --bg: #0f1218; --card: #171b23; --ink: #e8ebf0; --muted: #98a1b0; --line: #2a303b; --accent: #6b9cff; } }
  * { box-sizing: border-box; }
  body { margin: 0; background: var(--bg); color: var(--ink); font: 15px/1.5 system-ui, sans-serif; }
  main { max-width: 960px; margin: 0 auto; padding: 32px 16px; }
  h1 { margin: 0 0 4px; font-size: 22px; }
  p.sub { margin: 0 0 24px; color: var(--muted); }
  .grid { display: grid; gap: 16px; grid-template-columns: repeat(auto-fit, minmax(210px, 1fr)); }
  .card { background: var(--card); border: 1px solid var(--line); border-radius: 12px; padding: 18px; }
  .card.today { border-color: var(--accent); }
  .card h2 { margin: 0; font-size: 17px; }
  .tag { display: inline-block; margin-left: 6px; padding: 1px 8px; border-radius: 99px; background: var(--accent); color: #fff; font-size: 11px; vertical-align: middle; }
  .card p { margin: 4px 0 14px; color: var(--muted); font-size: 13px; }
  .open { display: block; text-align: center; padding: 8px; border-radius: 8px; background: var(--accent); color: #fff; text-decoration: none; font-weight: 600; }
  ul { list-style: none; margin: 12px 0 0; padding: 0; display: flex; flex-wrap: wrap; gap: 6px; }
  ul a { color: var(--accent); font-size: 13px; text-decoration: none; border: 1px solid var(--line); border-radius: 6px; padding: 2px 8px; }
  footer { margin-top: 24px; color: var(--muted); font-size: 13px; }
  footer a { color: var(--accent); }
</style>
</head>
<body>
<main>
  <h1>Site versions</h1>
  <p class="sub">Local preview only. Each version opens from its own folder.</p>
  <div class="grid">
    <?php foreach ($versions as $n => [$name, $desc]): ?>
      <div class="card<?= $n === $today ? ' today' : '' ?>">
        <h2><?= $n ?>. <?= htmlspecialchars($name) ?><?php if ($n === $today): ?><span class="tag">Live this minute</span><?php endif; ?></h2>
        <p><?= htmlspecialchars($desc) ?></p>
        <a class="open" href="<?= $base ?>/<?= $n ?>/" target="_blank">Open version <?= $n ?></a>
        <ul>
          <?php foreach ($pages as $path => $label): if ($path === '') continue; ?>
            <li><a href="<?= $base ?>/<?= $n ?>/<?= $path ?>" target="_blank"><?= $label ?></a></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endforeach; ?>
  </div>
  <footer>The real root <a href="<?= $base ?>/" target="_blank"><?= htmlspecialchars($base) ?>/</a> switches version every minute, the same as production.</footer>
</main>
</body>
</html>
