<?php
// v7 "Orbit" helpers: mission codes, signal numbers and the star map.
// Numbers are made up but derived from the real content, so they stay the same on every load.

// A number in [min, max] picked from the hash of $seed
function orbit_num(string $seed, int $min, int $max): int
{
    return $min + (hexdec(substr(hash('crc32b', $seed), 0, 7)) % ($max - $min + 1));
}

function mission_code(int $n): string
{
    return OWNER_INITIALS . '-' . str_pad((string) $n, 2, '0', STR_PAD_LEFT);
}

// Radio frequency for a blog post, e.g. "8.412 GHz"
function signal_freq(string $slug): string
{
    return number_format(orbit_num('f:' . $slug, 2200, 9999) / 1000, 3) . ' GHz';
}

function days_ago(string $date): string
{
    $days = (int) floor((time() - strtotime($date)) / 86400);
    if ($days < 1) return 'today';
    if ($days < 31) return $days . ' day' . ($days > 1 ? 's' : '') . ' ago';
    if ($days < 365) return floor($days / 30) . ' mo ago';
    return floor($days / 365) . ' yr ago';
}

