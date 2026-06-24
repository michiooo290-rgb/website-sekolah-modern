<?php
/**
 * Database connection — PDO with prepared statements
 * SMA Putra Persada Batam
 */

/* ── Security Headers ─────────────────────────────── */
header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('Permissions-Policy: camera=(), microphone=(), geolocation=()');
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-eval' 'unsafe-inline' https://cdn.jsdelivr.net https://unpkg.com https://fonts.googleapis.com; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.jsdelivr.net https://fonts.gstatic.com; img-src 'self' data: https: blob:; font-src 'self' https://fonts.gstatic.com https://fonts.googleapis.com data:; frame-src https://maps.google.com https://www.google.com");

/* ── Session Cookie Security ──────────────────────── */
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'secure'   => isset($_SERVER['HTTPS']),
        'httponly'  => true,
        'samesite'  => 'Lax',
    ]);
}

/* ── Load .env ───────────────────────────────────── */
$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        if (str_starts_with(trim($line), '#') || !str_contains($line, '=')) continue;
        [$k, $v] = explode('=', $line, 2);
        $_ENV[trim($k)] = trim($v);
        putenv(trim($k) . '=' . trim($v));
    }
}

/* ── Database Config ──────────────────────────────── */
define('DB_HOST',   $_ENV['DB_HOST']   ?? getenv('DB_HOST')   ?: '127.0.0.1');
define('DB_NAME',   $_ENV['DB_NAME']   ?? getenv('DB_NAME')   ?: 'sma_putra_persada');
define('DB_USER',   $_ENV['DB_USER']   ?? getenv('DB_USER')   ?: 'root');
define('DB_PASS',   $_ENV['DB_PASS']   ?? getenv('DB_PASS')   ?: '');
define('DB_CHARSET', $_ENV['DB_CHARSET'] ?? getenv('DB_CHARSET') ?: 'utf8mb4');

function db(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
    }
    return $pdo;
}

/**
 * Fetch site setting by key
 */
function setting(string $kunci): ?string {
    $stmt = db()->prepare('SELECT nilai FROM pengaturan WHERE kunci = ?');
    $stmt->execute([$kunci]);
    $row = $stmt->fetch();
    return $row ? $row['nilai'] : null;
}

/**
 * Sanitize output for HTML
 */
function esc(string $str): string {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

/**
 * Sanitize HTML content — whitelist allowed tags only
 * Prevents Stored XSS while allowing safe formatting tags
 */
function sanitize_html(string $html): string {
    // Whitelist: allowed tags and their allowed attributes
    $allowedTags = ['p','br','h3','h4','ul','ol','li','strong','b','em','i',
                    'a','img','blockquote','pre','code','span','div',
                    'table','thead','tbody','tr','th','td'];
    $allowedAttrs = [
        'a'   => ['href','title'],
        'img' => ['src','alt','width','height'],
        'td'  => ['colspan','rowspan'],
        'th'  => ['colspan','rowspan'],
    ];

    // First pass: strip tags to whitelist
    $html = strip_tags($html, '<' . implode('><', $allowedTags) . '>');

    // Use DOMDocument for attribute-level sanitization (no regex bypass)
    $doc = new DOMDocument('1.0', 'UTF-8');
    libxml_use_internal_errors(true);
    $doc->loadHTML('<?xml encoding="UTF-8"><div id="__sanitize_root__">' . $html . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    libxml_clear_errors();

    $xpath = new DOMXPath($doc);

    // Capture our wrapper node BEFORE attribute stripping (its id would be
    // removed by the loop below, so we keep a direct reference).
    $root = $xpath->query('//*[@id="__sanitize_root__"]')->item(0);

    foreach ($xpath->query('//*') as $node) {
        if ($node === $root) { continue; }
        $tag = strtolower($node->nodeName);
        $keep = $allowedAttrs[$tag] ?? [];

        // Remove all attributes not in whitelist, plus any on* event handlers
        $remove = [];
        foreach ($node->attributes as $attr) {
            $name = strtolower($attr->nodeName);
            if ($name === 'xmlns') continue;
            if (str_starts_with($name, 'on')) { $remove[] = $attr; continue; }
            if (!in_array($name, $keep)) { $remove[] = $attr; continue; }
            // Block dangerous URIs in href/src
            if (in_array($name, ['href','src'])) {
                $val = strtolower(trim($attr->nodeValue));
                if (preg_match('/^\s*(javascript|vbscript|data)\s*:/i', $val)) {
                    $remove[] = $attr;
                }
            }
        }
        foreach ($remove as $attr) $node->removeAttributeNode($attr);
    }

    // Extract sanitized content (serialize children of our wrapper).
    // Note: with LIBXML_HTML_NOIMPLIED there is no <body>, so we wrapped the
    // input in a known <div> and return its inner HTML.
    if (!$root) {
        return '';
    }
    $out = '';
    foreach ($root->childNodes as $child) {
        $out .= $doc->saveHTML($child);
    }
    return $out;
}

/**
 * Format date for display
 */
function tglIndo(string $tgl): string {
    $bulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    $d = new DateTime($tgl);
    return $d->format('d') . ' ' . $bulan[(int)$d->format('m') - 1] . ' ' . $d->format('Y');
}

/**
 * Format date short (e.g., "12 Jun 2026")
 */
function tglPendek(string $tgl): string {
    $bulan = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des'];
    $d = new DateTime($tgl);
    return $d->format('d') . ' ' . $bulan[(int)$d->format('m') - 1] . ' ' . $d->format('Y');
}
