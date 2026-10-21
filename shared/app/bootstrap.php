<?php
declare(strict_types=1);

// SHARED_DIR: code, data, content, images used by every version.
// ROOT_DIR:   the active version folder (its own views + css/js).
define('SHARED_DIR', dirname(__DIR__));
define('ROOT_DIR', defined('VERSION_DIR') ? VERSION_DIR : SHARED_DIR);
define('APP_DIR', __DIR__);
define('VIEWS_DIR', ROOT_DIR . '/app/views');
define('CONTENT_DIR', SHARED_DIR . '/content');
define('STORAGE_DIR', SHARED_DIR . '/storage');
define('IMAGES_DIR', SHARED_DIR . '/assets/imgs');

// ---- Environment -----------------------------------------------------------
function load_env(string $file): void
{
    if (!is_file($file)) return;
    foreach (file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#' || !str_contains($line, '=')) continue;
        [$key, $value] = array_map('trim', explode('=', $line, 2));
        $_ENV[$key] = trim($value, "\"'");
    }
}
load_env(SHARED_DIR . '/.env');

function env(string $key, ?string $default = null): ?string
{
    $value = $_ENV[$key] ?? getenv($key);
    return ($value === false || $value === '') ? $default : $value;
}

define('SITE_URL', rtrim(env('SITE_URL', 'https://example.com'), '/'));
define('SITE_DOMAIN', parse_url(SITE_URL, PHP_URL_HOST) ?: 'example.com');
define('GA_ID', env('GA_ID', ''));
define('IS_PROD', env('APP_ENV', 'production') === 'production');

// Base path lets the site run in a subfolder (e.g. /portfolio-template/2) or at the domain root.
// When served through the root daily switch (see ../.htaccess), the version folder is hidden from URLs.
$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/'));
if (preg_grep('/^(REDIRECT_)*BRS_VERSION$/', array_keys($_SERVER))) {
    $scriptDir = dirname($scriptDir);
}
define('BASE_PATH', rtrim(str_replace('\\', '/', $scriptDir), '/'));

ini_set('display_errors', IS_PROD ? '0' : '1');
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE && !str_contains($_SERVER['REQUEST_URI'] ?? '', '/assets/imgs/')) {
    session_start([
        'cookie_httponly' => true,
        'cookie_samesite' => 'Lax',
        'cookie_secure'   => !empty($_SERVER['HTTPS']),
    ]);
}

require APP_DIR . '/blogs.php';
require APP_DIR . '/schema.php';

// ---- Helpers ---------------------------------------------------------------
function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function url(string $path = '/'): string
{
    return BASE_PATH . '/' . ltrim($path, '/');
}

function asset(string $path): string
{
    $file = ROOT_DIR . '/assets/' . ltrim($path, '/');
    if (!is_file($file)) $file = SHARED_DIR . '/assets/' . ltrim($path, '/');
    $version = is_file($file) ? '?v=' . filemtime($file) : '';
    // Under the root version switch, pin this version's own css/js to its folder
    // so a page never loads another version's files after the switch.
    if (defined('VERSION_DIR') && str_starts_with($file, VERSION_DIR) && BASE_PATH !== rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? '/'), '/\\')) {
        return BASE_PATH . '/' . basename(VERSION_DIR) . '/assets/' . ltrim($path, '/') . $version;
    }
    return url('assets/' . ltrim($path, '/')) . $version;
}

function request_path(): string
{
    $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    if (BASE_PATH !== '' && str_starts_with($path, BASE_PATH)) {
        $path = substr($path, strlen(BASE_PATH));
    }
    $path = '/' . ltrim($path, '/');
    return $path === '/index.php' ? '/' : $path;
}

function redirect(string $path, int $code = 302): never
{
    header('Location: ' . (str_starts_with($path, 'http') ? $path : url($path)), true, $code);
    exit;
}

function data(string $name): array
{
    static $cache = [];
    if (isset($cache[$name])) return $cache[$name];

    // Shared data, with optional per-version overrides of top-level keys
    // (e.g. 3/app/data/profile.php returning ['highlights' => [...]]).
    $shared = require APP_DIR . "/data/{$name}.php";
    $override = ROOT_DIR . "/app/data/{$name}.php";
    return $cache[$name] = is_file($override) ? array_replace($shared, require $override) : $shared;
}

// Owner name, used across every version's views. Edit app/data/profile.php to change it.
$owner = data('profile');
define('SITE_NAME', $owner['full_name']);
define('OWNER_NAME', $owner['name']);
define('OWNER_FIRST_NAME', strtok($owner['name'], ' '));
define('OWNER_LAST_NAME', trim(substr($owner['name'], strlen(OWNER_FIRST_NAME))));
define('OWNER_INITIALS', $owner['initials']);
unset($owner);

function view(string $name, array $vars = []): string
{
    extract($vars);
    ob_start();
    require VIEWS_DIR . "/{$name}.php";
    return ob_get_clean();
}

function serve_shared_image(string $name): never
{
    $file = realpath(IMAGES_DIR . '/' . $name);
    $types = ['jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png', 'webp' => 'image/webp', 'gif' => 'image/gif', 'svg' => 'image/svg+xml'];
    $ext = strtolower(pathinfo((string) $file, PATHINFO_EXTENSION));
    if (!$file || !str_starts_with($file, realpath(IMAGES_DIR) . DIRECTORY_SEPARATOR) || !isset($types[$ext])) {
        not_found();
    }

    $mtime = filemtime($file);
    $etag = '"' . dechex($mtime) . '-' . dechex(filesize($file)) . '"';
    header('Content-Type: ' . $types[$ext]);
    header('Cache-Control: public, max-age=2592000');
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $mtime) . ' GMT');
    header('ETag: ' . $etag);
    if (($_SERVER['HTTP_IF_NONE_MATCH'] ?? '') === $etag) {
        http_response_code(304);
        exit;
    }
    header('Content-Length: ' . filesize($file));
    readfile($file);
    exit;
}

function render(string $name, array $vars = []): void
{
    $vars['pageContent'] = view($name, $vars);
    echo view('layout', $vars);
}

function not_found(): never
{
    http_response_code(404);
    render('pages/404', ['seo' => ['title' => 'Page not found | ' . OWNER_NAME]]);
    exit;
}

function csrf_token(): string
{
    return $_SESSION['csrf'] ??= bin2hex(random_bytes(32));
}

function flash(string $key, ?string $value = null): ?string
{
    if ($value !== null) {
        $_SESSION['flash'][$key] = $value;
        return null;
    }
    $message = $_SESSION['flash'][$key] ?? null;
    unset($_SESSION['flash'][$key]);
    return $message;
}
