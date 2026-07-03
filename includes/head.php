<?php
/**
 * HTML <head> + opening <body> tag
 * $pageTitle       — judul halaman (wajib)
 * $metaDescription — deskripsi halaman untuk Google/medsos (opsional; ada default)
 * $metaImage       — URL gambar Open Graph saat link dibagikan (opsional; default logo)
 * $metaType        — 'website' (default) atau 'article' untuk halaman berita
 */
$namaSekolah = setting('nama_sekolah') ?? 'SMA Putra Persada Batam';
$tagline     = setting('tagline') ?? 'Unggul & Berakhlak';
$fullTitle   = $pageTitle === $namaSekolah ? "$namaSekolah — $tagline" : "$pageTitle — $namaSekolah";

// ── SEO: bangun URL absolut otomatis (ikut domain / subfolder, tanpa hardcode) ──
$seoScheme = ((!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off')
  || (($_SERVER['SERVER_PORT'] ?? '') == 443)
  || (strtolower($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https')) ? 'https' : 'http';
$seoHost    = $_SERVER['HTTP_HOST'] ?? 'localhost';
$seoBaseDir = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');
$seoBaseUrl = $seoScheme . '://' . $seoHost . $seoBaseDir;
$seoPath    = strtok($_SERVER['REQUEST_URI'] ?? '/', '#');
$seoCanonical = $seoScheme . '://' . $seoHost . $seoPath;

// ── SEO: deskripsi ──
$defaultDesc = setting('meta_description')
  ?? ($namaSekolah . ' — ' . $tagline . '. Sekolah menengah atas islami di Batam yang menyeimbangkan prestasi akademik dan pembinaan karakter menuju perguruan tinggi terbaik.');
$metaDescription = (isset($metaDescription) && $metaDescription !== '')
  ? $metaDescription
  : ($pageTitle === $namaSekolah ? $defaultDesc : ($pageTitle . ' — ' . $defaultDesc));
$metaDescription = trim(preg_replace('/\s+/', ' ', strip_tags($metaDescription)));
if (function_exists('mb_strlen') && mb_strlen($metaDescription) > 160) {
  $metaDescription = rtrim(mb_substr($metaDescription, 0, 157)) . '…';
}

// ── SEO: gambar Open Graph (absolut) ──
$metaImage = (isset($metaImage) && $metaImage !== '')
  ? (preg_match('#^https?://#i', $metaImage) ? $metaImage : $seoBaseUrl . '/' . ltrim($metaImage, '/'))
  : $seoBaseUrl . '/assets/img/logo.jpeg';
$metaType = $metaType ?? 'website';

// ── SEO: keywords ──
$metaKeywords = setting('meta_keywords')
  ?? ($namaSekolah . ', SMA Islam Batam, sekolah menengah atas Batam, sekolah swasta Batam, PPDB Batam ' . date('Y') . ', SMA IPS Batam, tahfiz, ekstrakurikuler, sekolah berkarakter');

// ── SEO: data terstruktur (JSON-LD) untuk Google ──
$seoJsonLd = json_encode([
  '@context'    => 'https://schema.org',
  '@type'       => 'EducationalOrganization',
  'name'        => $namaSekolah,
  'url'         => $seoBaseUrl . '/',
  'logo'        => $seoBaseUrl . '/assets/img/logo.jpeg',
  'description' => $defaultDesc,
  'address'     => [
    '@type'           => 'PostalAddress',
    'streetAddress'   => setting('alamat') ?? '',
    'addressLocality' => 'Batam',
    'addressRegion'   => 'Kepulauan Riau',
    'addressCountry'  => 'ID',
  ],
  'telephone'   => setting('telepon') ?? '',
  'email'       => setting('email') ?? '',
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo esc($fullTitle); ?></title>

  <!-- SEO dasar -->
  <meta name="description" content="<?php echo esc($metaDescription); ?>">
  <meta name="keywords" content="<?php echo esc($metaKeywords); ?>">
  <meta name="author" content="<?php echo esc($namaSekolah); ?>">
  <meta name="robots" content="index, follow">
  <link rel="canonical" href="<?php echo esc($seoCanonical); ?>">

  <!-- Favicon -->
  <link rel="icon" type="image/svg+xml" href="assets/img/favicon.svg">
  <link rel="icon" type="image/jpeg" href="assets/img/logo.jpeg">
  <link rel="apple-touch-icon" href="assets/img/logo.jpeg">
  <meta name="theme-color" content="#0E3B2E">

  <!-- Open Graph (WhatsApp, Facebook, dll) -->
  <meta property="og:type" content="<?php echo esc($metaType); ?>">
  <meta property="og:site_name" content="<?php echo esc($namaSekolah); ?>">
  <meta property="og:title" content="<?php echo esc($fullTitle); ?>">
  <meta property="og:description" content="<?php echo esc($metaDescription); ?>">
  <meta property="og:url" content="<?php echo esc($seoCanonical); ?>">
  <meta property="og:image" content="<?php echo esc($metaImage); ?>">
  <meta property="og:locale" content="id_ID">

  <!-- Twitter Card -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="<?php echo esc($fullTitle); ?>">
  <meta name="twitter:description" content="<?php echo esc($metaDescription); ?>">
  <meta name="twitter:image" content="<?php echo esc($metaImage); ?>">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600;9..144,700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
  <!-- Tailwind di-bundel lokal agar tetap jalan tanpa internet/CDN -->
  <script nonce="<?php echo $cspNonce ?? ''; ?>" src="assets/js/tailwind.js"></script>
  <style type="text/tailwindcss">
    @custom-variant dark (&:where(.dark, .dark *));
    @theme {
      --color-pine: #0E3B2E;
      --color-pine-deep: #08291F;
      --color-leaf: #2F7D52;
      --color-cream: #F7F3E9;
      --color-cream-deep: #EFE8D6;
      --color-brass: #C9A227;
      --color-brass-light: #E0BC45;
      --font-serif: 'Fraunces', Georgia, serif;
      --font-sans: 'Plus Jakarta Sans', system-ui, sans-serif;
    }
  </style>
  <script nonce="<?php echo $cspNonce ?? ''; ?>">
    /* Tema: pakai pilihan tersimpan; jika belum ada, ikuti OS */
    (function(){
      var saved = localStorage.getItem('theme');
      var isDark = saved ? (saved === 'dark') : window.matchMedia('(prefers-color-scheme: dark)').matches;
      document.documentElement.classList.toggle('dark', isDark);
    })();
  </script>
  <link rel="stylesheet" href="assets/css/styles.css">

  <!-- Lenis smooth scroll -->
  <script nonce="<?php echo $cspNonce ?? ''; ?>" src="assets/js/lenis.min.js"></script>

  <!-- Data terstruktur untuk mesin pencari -->
  <script type="application/ld+json" nonce="<?php echo $cspNonce ?? ''; ?>"><?php echo $seoJsonLd; ?></script>
</head>
<body class="min-h-dvh antialiased bg-cream dark:bg-pine-deep text-pine dark:text-cream font-sans">
  <div id="site-header" class="sticky top-0 z-30"></div>
