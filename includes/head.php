<?php
/**
 * HTML <head> + opening <body> tag — identical to original index.html
 * $pageTitle — page title string
 */
$namaSekolah = setting('nama_sekolah') ?? 'SMA Putra Persada Batam';
$tagline     = setting('tagline') ?? 'Unggul & Berakhlak';
$fullTitle   = $pageTitle === $namaSekolah ? "$namaSekolah — $tagline" : "$pageTitle — $namaSekolah";
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo esc($fullTitle); ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600;9..144,700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
  <!-- Tailwind di-bundel lokal agar tetap jalan tanpa internet/CDN -->
  <script src="assets/js/tailwind.js"></script>
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
  <script>
    /* Tema: pakai pilihan tersimpan; jika belum ada, ikuti OS */
    (function(){
      var saved = localStorage.getItem('theme');
      var isDark = saved ? (saved === 'dark') : window.matchMedia('(prefers-color-scheme: dark)').matches;
      document.documentElement.classList.toggle('dark', isDark);
    })();
  </script>
  <link rel="stylesheet" href="assets/css/styles.css">

  <!-- Lenis smooth scroll -->
  <script src="https://unpkg.com/lenis@1.1.18/dist/lenis.min.js"></script>
</head>
<body class="min-h-dvh antialiased bg-cream dark:bg-pine-deep text-pine dark:text-cream font-sans">
  <div id="site-header"></div>
