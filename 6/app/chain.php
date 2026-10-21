<?php
// v6 "Chain" helpers: deterministic hashes, token symbols and numbers.
// Everything is derived from the real content, so it stays the same on every load.

function chain_hash(string $seed, int $len = 64): string
{
    return '0x' . substr(hash('sha256', 'brs:' . $seed), 0, $len);
}

function short_hash(string $hash, int $head = 8, int $tail = 6): string
{
    return substr($hash, 0, $head) . '…' . substr($hash, -$tail);
}

// A number in [min, max] picked from the hash of $seed
function chain_num(string $seed, int $min, int $max): int
{
    return $min + (hexdec(substr(hash('crc32b', $seed), 0, 7)) % ($max - $min + 1));
}

function token_symbol(string $name): string
{
    $word = preg_replace('/[^A-Za-z0-9]/', '', explode(' ', $name)[0]);
    $word = preg_replace('/js$/i', '', $word) ?: $word;
    return strtoupper(substr($word, 0, 5));
}

function token_balance(string $name): string
{
    return number_format(chain_num('bal:' . $name, 120, 9800) + chain_num('dec:' . $name, 0, 99) / 100, 2);
}

function token_change(string $name): float
{
    return (chain_num('chg:' . $name, 0, 240) - 60) / 10; // mostly up, sometimes down
}

function time_ago(string $date): string
{
    $days = (int) floor((time() - strtotime($date)) / 86400);
    if ($days < 1) return 'today';
    if ($days < 31) return $days . ' day' . ($days > 1 ? 's' : '') . ' ago';
    if ($days < 365) return floor($days / 30) . ' mo ago';
    return floor($days / 365) . ' yr ago';
}

function wallet_address(): string
{
    return chain_hash(data('profile')['full_name'], 40);
}
