<?php
// POST /contact — validates, rate-limits and forwards the message to Telegram.

const CONTACT_LIMIT = 5;        // messages
const CONTACT_WINDOW = 3600;    // per hour, per IP

function handle_contact(): never
{
    $back = '/#contact';

    if (!hash_equals(csrf_token(), (string) ($_POST['_token'] ?? ''))) {
        contact_fail('Your session expired. Please try again.', $back);
    }

    // Honeypot: real users never fill this hidden field
    if (!empty($_POST['website'])) {
        flash('contact_ok', 'Thanks! Your message has been sent.');
        redirect($back);
    }

    $name    = trim((string) ($_POST['name'] ?? ''));
    $email   = trim((string) ($_POST['email'] ?? ''));
    $phone   = trim((string) ($_POST['phone'] ?? ''));
    $message = trim((string) ($_POST['message'] ?? ''));

    if ($name === '' || $message === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        contact_fail('Please fill in your name, a valid email and a message.', $back);
    }
    if (mb_strlen($name) > 100 || mb_strlen($phone) > 30 || mb_strlen($message) > 4000) {
        contact_fail('That message is too long.', $back);
    }
    if (!rate_limit_ok($_SERVER['REMOTE_ADDR'] ?? 'unknown')) {
        contact_fail('Too many messages. Please try again in an hour.', $back);
    }

    $text = "<b>📬 New contact form message</b>\n"
          . '<b>Name:</b> ' . e($name) . "\n"
          . '<b>Email:</b> ' . e($email) . "\n"
          . '<b>Phone:</b> ' . e($phone ?: '—') . "\n"
          . "<b>Message:</b>\n" . e($message);

    if (!send_telegram($text)) {
        contact_fail('Sorry, the message could not be sent. Please email me directly.', $back);
    }

    flash('contact_ok', 'Thanks! Your message has been sent.');
    redirect($back);
}

function contact_fail(string $error, string $back): never
{
    flash('contact_error', $error);
    redirect($back);
}

function rate_limit_ok(string $ip): bool
{
    $file = STORAGE_DIR . '/rate_limit.json';
    $now = time();
    $fp = fopen($file, 'c+');
    if (!$fp) return true;

    flock($fp, LOCK_EX);
    $hits = json_decode(stream_get_contents($fp) ?: '{}', true) ?: [];
    $key = hash('sha256', $ip);
    $hits = array_map(fn($times) => array_values(array_filter($times, fn($t) => $t > $now - CONTACT_WINDOW)), $hits);
    $hits = array_filter($hits);
    $allowed = count($hits[$key] ?? []) < CONTACT_LIMIT;
    if ($allowed) $hits[$key][] = $now;

    ftruncate($fp, 0);
    rewind($fp);
    fwrite($fp, json_encode($hits));
    flock($fp, LOCK_UN);
    fclose($fp);
    return $allowed;
}

function send_telegram(string $text): bool
{
    $token = env('TELEGRAM_BOT_TOKEN');
    $chatId = env('TELEGRAM_CHAT_ID');
    if (!$token || !$chatId) {
        error_log('Contact form: TELEGRAM_BOT_TOKEN / TELEGRAM_CHAT_ID missing in .env');
        return false;
    }

    $context = stream_context_create(['http' => [
        'method'  => 'POST',
        'header'  => "Content-Type: application/x-www-form-urlencoded\r\n",
        'content' => http_build_query(['chat_id' => $chatId, 'text' => $text, 'parse_mode' => 'HTML']),
        'timeout' => 10,
    ]]);
    $result = @file_get_contents("https://api.telegram.org/bot{$token}/sendMessage", false, $context);
    return $result !== false && (json_decode($result, true)['ok'] ?? false);
}
