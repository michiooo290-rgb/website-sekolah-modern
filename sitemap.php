<?php
/**
 * sitemap.php — Peta situs XML dinamis untuk mesin pencari (Google, Bing, dll)
 * Akses: https://domain-anda/sitemap.php
 * Daftarkan URL ini di Google Search Console.
 */
require_once __DIR__ . '/config/koneksi.php';

$scheme = ((!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off')
  || (($_SERVER['SERVER_PORT'] ?? '') == 443)
  || (strtolower($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https')) ? 'https' : 'http';
$host    = $_SERVER['HTTP_HOST'] ?? 'localhost';
$baseDir = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');
$base    = $scheme . '://' . $host . $baseDir;

$pages = [
    ['loc' => 'index.php',           'priority' => '1.0', 'freq' => 'weekly'],
    ['loc' => 'tentang.php',         'priority' => '0.8', 'freq' => 'monthly'],
    ['loc' => 'visi-misi.php',       'priority' => '0.6', 'freq' => 'yearly'],
    ['loc' => 'ekstrakurikuler.php', 'priority' => '0.7', 'freq' => 'monthly'],
    ['loc' => 'berita.php',          'priority' => '0.9', 'freq' => 'daily'],
    ['loc' => 'ppdb.php',            'priority' => '0.9', 'freq' => 'weekly'],
    ['loc' => 'kontak.php',          'priority' => '0.6', 'freq' => 'yearly'],
];

$berita = [];
try {
    $berita = db()->query('SELECT slug, tanggal FROM berita ORDER BY tanggal DESC')->fetchAll();
} catch (Throwable $e) {
    $berita = [];
}

header('Content-Type: application/xml; charset=utf-8');
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php foreach ($pages as $p): ?>
  <url>
    <loc><?php echo htmlspecialchars($base . '/' . $p['loc'], ENT_XML1); ?></loc>
    <changefreq><?php echo $p['freq']; ?></changefreq>
    <priority><?php echo $p['priority']; ?></priority>
  </url>
<?php endforeach; ?>
<?php foreach ($berita as $b): ?>
  <url>
    <loc><?php echo htmlspecialchars($base . '/berita-detail.php?slug=' . urlencode($b['slug']), ENT_XML1); ?></loc>
    <lastmod><?php echo htmlspecialchars(date('Y-m-d', strtotime((string)$b['tanggal'])), ENT_XML1); ?></lastmod>
    <changefreq>monthly</changefreq>
    <priority>0.7</priority>
  </url>
<?php endforeach; ?>
</urlset>
